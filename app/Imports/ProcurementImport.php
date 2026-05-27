<?php

namespace App\Imports;

use App\Models\Procurement;
use App\Models\ImportLog;
use App\Events\ImportProgressEvent;
use App\Events\ImportCompletedEvent;
use App\Exports\FailedRowsExport;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithUpserts;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Validators\Failure;
use Maatwebsite\Excel\Events\BeforeImport;
use Maatwebsite\Excel\Events\AfterImport;
use Maatwebsite\Excel\Events\AfterChunk;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Throwable;

class ProcurementImport implements
    ToModel,
    WithHeadingRow,
    WithChunkReading,
    WithValidation,
    WithUpserts,
    WithBatchInserts,
    WithEvents,
    SkipsOnError,
    SkipsOnFailure,
    ShouldQueue
{
    /**
     * ImportLog ID for tracking progress.
     */
    public int $importLogId;

    /**
     * Counter for rows processed within current chunk.
     * Resets per chunk since ShouldQueue serializes between chunks.
     */
    private int $chunkRowCount = 0;

    public function __construct(int $importLogId)
    {
        $this->importLogId = $importLogId;
    }

    /**
     * Map each Excel row to a Procurement model.
     * Status is computed manually because upsert bypasses Eloquent events.
     */
    public function model(array $row): Procurement
    {
        $this->chunkRowCount++;

        $procurement = new Procurement([
            'kode_pengadaan' => $row['kode_pengadaan'],
            'nama_barang'    => $row['nama_barang'],
            'vendor'         => $row['vendor'],
            'tanggal_te'     => $row['tanggal_te'] ?: null,
            'tanggal_rete'   => $row['tanggal_rete'] ?: null,
            'tanggal_po'     => $row['tanggal_po'] ?: null,
        ]);

        // Manually compute status since upsert bypasses model boot events
        $procurement->status = $procurement->computeStatus();

        return $procurement;
    }

    /**
     * Unique key for upsert — prevents duplicate kode_pengadaan.
     */
    public function uniqueBy(): string
    {
        return 'kode_pengadaan';
    }

    /**
     * Batch insert size for efficiency.
     */
    public function batchSize(): int
    {
        return 500;
    }

    /**
     * Chunk reading size — each chunk becomes a separate queued job.
     */
    public function chunkSize(): int
    {
        return 1000;
    }

    /**
     * Validation rules for each row.
     */
    public function rules(): array
    {
        return [
            'kode_pengadaan' => ['required', 'string', 'max:255'],
            'nama_barang'    => ['required', 'string', 'max:255'],
            'vendor'         => ['required', 'string', 'max:255'],
            'tanggal_te'     => ['nullable', 'date_format:Y-m-d'],
            'tanggal_rete'   => ['nullable', 'date_format:Y-m-d'],
            'tanggal_po'     => ['nullable', 'date_format:Y-m-d'],
        ];
    }

    /**
     * Custom validation messages (Indonesian).
     */
    public function customValidationMessages(): array
    {
        return [
            'kode_pengadaan.required' => 'Kode Pengadaan wajib diisi.',
            'nama_barang.required'    => 'Nama Barang wajib diisi.',
            'vendor.required'         => 'Vendor wajib diisi.',
            'tanggal_te.date_format'  => 'Format Tanggal TE harus YYYY-MM-DD.',
            'tanggal_rete.date_format' => 'Format Tanggal RE-TE harus YYYY-MM-DD.',
            'tanggal_po.date_format'  => 'Format Tanggal PO harus YYYY-MM-DD.',
        ];
    }

    /**
     * Handle validation failures — persist to JSONL file for cross-chunk collection.
     * Overrides default SkipsOnFailure behavior which only stores in memory.
     */
    public function onFailure(Failure ...$failures): void
    {
        $lines = '';
        foreach ($failures as $failure) {
            $lines .= json_encode([
                'row'       => $failure->row(),
                'attribute' => $failure->attribute(),
                'errors'    => $failure->errors(),
                'values'    => $failure->values(),
            ]) . "\n";
        }

        $path = $this->getFailuresFilePath();
        file_put_contents($path, $lines, FILE_APPEND | LOCK_EX);

        // Update failure count in ImportLog
        ImportLog::where('id', $this->importLogId)
            ->increment('failure_count', count($failures));
    }

    /**
     * Handle model/database errors — persist to same JSONL file.
     */
    public function onError(Throwable $e): void
    {
        $line = json_encode([
            'row'       => null,
            'attribute' => 'database',
            'errors'    => [$e->getMessage()],
            'values'    => [],
        ]) . "\n";

        $path = $this->getFailuresFilePath();
        file_put_contents($path, $line, FILE_APPEND | LOCK_EX);

        ImportLog::where('id', $this->importLogId)
            ->increment('failure_count');

        Log::error("ProcurementImport error on ImportLog #{$this->importLogId}: {$e->getMessage()}");
    }

    /**
     * Register import lifecycle events.
     */
    public function registerEvents(): array
    {
        return [
            BeforeImport::class => function (BeforeImport $event) {
                $totalRows = $event->getReader()->getTotalRows();
                // Get the row count of the first sheet dynamically
                $firstSheetRows = count($totalRows) > 0 ? reset($totalRows) : 0;
                $total = max(0, $firstSheetRows - 1);

                ImportLog::where('id', $this->importLogId)
                    ->update(['total_rows' => $total]);
            },

            AfterChunk::class => function (AfterChunk $event) {
                $importLog = ImportLog::find($this->importLogId);
                if ($importLog) {
                    $processed = min($importLog->total_rows, $importLog->processed_rows + $this->chunkRowCount);
                    $importLog->update(['processed_rows' => $processed]);

                    // Broadcast real-time progress via WebSocket
                    try {
                        event(new ImportProgressEvent(
                            $this->importLogId,
                            $processed,
                            $importLog->total_rows
                        ));
                    } catch (Throwable $e) {
                        Log::warning("Could not broadcast ImportProgressEvent: {$e->getMessage()}");
                    }
                }

                // Reset chunk row counter for next chunk job
                $this->chunkRowCount = 0;
            },

            AfterImport::class => function (AfterImport $event) {
                $this->handleImportCompletion();
            },
        ];
    }

    /**
     * After all chunks complete: generate error log, update ImportLog, broadcast.
     */
    private function handleImportCompletion(): void
    {
        $importLog = ImportLog::find($this->importLogId);
        if (!$importLog) {
            return;
        }

        // Read collected failures from JSONL file
        $failures = $this->readFailuresFromFile();
        $failureCount = count($failures);

        // Calculate success count
        $successCount = max(0, $importLog->total_rows - $failureCount);

        // Generate Error Log Excel if there are failures
        $errorFilePath = null;
        if ($failureCount > 0) {
            $errorFileName = "imports/error_log_{$this->importLogId}.xlsx";
            Excel::store(new FailedRowsExport($failures), $errorFileName, 'local');
            $errorFilePath = $errorFileName;
        }

        // Update processed_rows to match total (import is done)
        $importLog->update(['processed_rows' => $importLog->total_rows]);

        // Mark completed
        $importLog->markCompleted($successCount, $failureCount, $errorFilePath);

        // Broadcast completion event (Event class created in Phase 3)
        try {
            event(new ImportCompletedEvent(
                $this->importLogId,
                $successCount,
                $failureCount,
                $errorFilePath
            ));
        } catch (Throwable $e) {
            Log::warning("Could not broadcast ImportCompletedEvent: {$e->getMessage()}");
        }

        // Cleanup temp failures file
        $tempPath = $this->getFailuresFilePath();
        if (file_exists($tempPath)) {
            unlink($tempPath);
        }
    }

    /**
     * Get the file path for storing failures across chunks.
     */
    private function getFailuresFilePath(): string
    {
        $dir = storage_path('app/imports');
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
        return $dir . "/failures_{$this->importLogId}.jsonl";
    }

    /**
     * Read all failures from the JSONL temp file.
     */
    private function readFailuresFromFile(): array
    {
        $path = $this->getFailuresFilePath();
        if (!file_exists($path)) {
            return [];
        }

        $failures = [];
        $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            $decoded = json_decode($line, true);
            if ($decoded) {
                $failures[] = $decoded;
            }
        }

        return $failures;
    }
}
