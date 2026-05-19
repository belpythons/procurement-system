<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Permit;
use App\Models\User;
use OpenSpout\Writer\XLSX\Writer as XlsxWriter;
use OpenSpout\Reader\XLSX\Reader as XlsxReader;
use OpenSpout\Common\Entity\Row;

class ImportController extends Controller
{
    // ═══════════════════════════════════════════════════════════
    // DOWNLOAD TEMPLATE EXCEL KOSONG
    // ═══════════════════════════════════════════════════════════

    /**
     * Generate dan download template Excel kosong untuk import.
     *
     * Kolom header sesuai skema tabel permits, dengan tambahan
     * `username_pekerja` untuk identifikasi user.
     */
    public function downloadTemplate()
    {
        $filename = 'template_import_permit.xlsx';
        $tempPath = storage_path('app/' . $filename);

        $writer = new XlsxWriter();
        $writer->openToFile($tempPath);

        // ── Header Row ──
        $writer->addRow(Row::fromValues([
            'nomor_permit',          // A: PRM-XXX (opsional, akan di-generate otomatis jika kosong)
            'username_pekerja',      // B: username dari tabel users
            'jenis_pekerjaan',       // C: Hot Work, Cold Work, Confined Space, dll
            'nama_pekerjaan',        // D: Judul pekerjaan
            'deskripsi',             // E: Deskripsi lengkap
            'tanggal_kerja',         // F: Format: YYYY-MM-DD
            'jam_mulai',             // G: Format: HH:MM
            'jam_selesai',           // H: Format: HH:MM
            'gedung',                // I: Nama gedung
            'area',                  // J: Area kerja
            'lokasi',                // K: Detail lokasi
            'tingkat_risiko',        // L: Risiko Rendah / Sedang / Tinggi
            'status',                // M: HANYA "Selesai" atau "Ditolak"
            'catatan_supervisor',     // N: Catatan dari supervisor (opsional)
            'catatan_safety',         // O: Catatan dari safety officer (opsional)
        ]));

        // ── Baris contoh (untuk panduan format) ──
        $writer->addRow(Row::fromValues([
            'PRM-100',
            'mira.a',
            'Hot Work',
            'Pengelasan rangka mesin',
            'Perbaikan rangka mesin dengan metode pengelasan.',
            '2026-01-15',
            '08:00',
            '12:00',
            'CB (Control Building)',
            'Workshop',
            'UNIT 1500 - CONTROL BUILDING (Line 1)',
            'Risiko Tinggi',
            'Selesai',
            'Pekerjaan telah selesai dilaksanakan.',
            'Area kerja bersih dan aman.',
        ]));

        $writer->close();

        return response()->download($tempPath, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ])->deleteFileAfterSend(true);
    }

    // ═══════════════════════════════════════════════════════════
    // IMPORT EXCEL (Khusus Admin)
    // ═══════════════════════════════════════════════════════════

    /**
     * Proses import file Excel yang diunggah oleh Admin.
     *
     * Aturan validasi ketat:
     * 1. Kolom `username_pekerja` harus cocok dengan user di database.
     * 2. `department_id`, `supervisor_id`, `safety_officer_id` diisi otomatis
     *    dari departemen pekerja (Audit Trail).
     * 3. Hanya status "Selesai" atau "Ditolak" yang diterima.
     *    Status lain (Pending, Disetujui, teks random) → baris di-skip.
     * 4. Baris yang gagal validasi akan di-skip tanpa menghentikan proses.
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx|max:5120', // max 5MB
        ]);

        $file     = $request->file('file');
        $filePath = $file->getRealPath();

        $reader = new XlsxReader();
        $reader->open($filePath);

        $imported = 0;
        $skipped  = 0;
        $errors   = [];

        $allowedStatuses = [
            'selesai'  => Permit::STATUS_SELESAI,
            'ditolak'  => Permit::STATUS_DITOLAK,
        ];

        foreach ($reader->getSheetIterator() as $sheet) {
            $rowIndex = 0;

            foreach ($sheet->getRowIterator() as $row) {
                $rowIndex++;

                // Skip baris header (baris pertama)
                if ($rowIndex === 1) {
                    continue;
                }

                $cells = $row->toArray();

                // Skip baris kosong
                if (empty(array_filter($cells, fn ($v) => $v !== null && $v !== ''))) {
                    continue;
                }

                // ── Mapping kolom ──
                $nomorPermit       = trim((string) ($cells[0] ?? ''));
                $usernamePekerja   = trim((string) ($cells[1] ?? ''));
                $jenisPekerjaan    = trim((string) ($cells[2] ?? ''));
                $namaPekerjaan     = trim((string) ($cells[3] ?? ''));
                $deskripsi         = trim((string) ($cells[4] ?? ''));
                $tanggalKerja      = trim((string) ($cells[5] ?? ''));
                $jamMulai          = trim((string) ($cells[6] ?? ''));
                $jamSelesai        = trim((string) ($cells[7] ?? ''));
                $gedung            = trim((string) ($cells[8] ?? ''));
                $area              = trim((string) ($cells[9] ?? ''));
                $lokasi            = trim((string) ($cells[10] ?? ''));
                $tingkatRisiko     = trim((string) ($cells[11] ?? ''));
                $status            = trim((string) ($cells[12] ?? ''));
                $catatanSupervisor = trim((string) ($cells[13] ?? ''));
                $catatanSafety     = trim((string) ($cells[14] ?? ''));

                // ── Validasi 1: Status harus "Selesai" atau "Ditolak" ──
                $normalizedStatus = strtolower($status);
                if (!isset($allowedStatuses[$normalizedStatus])) {
                    $skipped++;
                    $errors[] = "Baris {$rowIndex}: Status \"{$status}\" tidak valid (hanya Selesai/Ditolak). Dilewati.";
                    continue;
                }

                // ── Validasi 2: Username pekerja harus ada di database ──
                if (empty($usernamePekerja)) {
                    $skipped++;
                    $errors[] = "Baris {$rowIndex}: Username pekerja kosong. Dilewati.";
                    continue;
                }

                $pekerja = User::where('username', $usernamePekerja)->first();
                if (!$pekerja) {
                    $skipped++;
                    $errors[] = "Baris {$rowIndex}: Username \"{$usernamePekerja}\" tidak ditemukan. Dilewati.";
                    continue;
                }

                // ── Validasi 3: Pekerja harus punya departemen ──
                $department = $pekerja->department;
                if (!$department) {
                    $skipped++;
                    $errors[] = "Baris {$rowIndex}: User \"{$usernamePekerja}\" tidak memiliki departemen. Dilewati.";
                    continue;
                }

                // ── Validasi 4: Departemen harus punya supervisor & safety officer ──
                if (!$department->supervisor_id || !$department->safety_officer_id) {
                    $skipped++;
                    $errors[] = "Baris {$rowIndex}: Departemen \"{$department->nama_departemen}\" tidak memiliki atasan lengkap. Dilewati.";
                    continue;
                }

                // ── Validasi 5: Field wajib ──
                if (empty($jenisPekerjaan) || empty($namaPekerjaan) || empty($tanggalKerja)) {
                    $skipped++;
                    $errors[] = "Baris {$rowIndex}: Jenis/nama pekerjaan atau tanggal kerja kosong. Dilewati.";
                    continue;
                }

                // ── Validasi tanggal ──
                $parsedDate = date_create($tanggalKerja);
                if (!$parsedDate) {
                    $skipped++;
                    $errors[] = "Baris {$rowIndex}: Format tanggal \"{$tanggalKerja}\" tidak valid. Dilewati.";
                    continue;
                }

                // ── Generate nomor permit jika kosong ──
                $finalNomor = $nomorPermit ?: Permit::generateNomorPermit();

                // ── Cek duplikat nomor permit ──
                if (Permit::where('nomor_permit', $finalNomor)->exists()) {
                    $finalNomor = Permit::generateNomorPermit();
                }

                // ── INSERT (Auto-assign audit trail dari departemen) ──
                Permit::create([
                    'nomor_permit'      => $finalNomor,
                    'user_id'           => $pekerja->id,
                    'department_id'     => $department->id,
                    'supervisor_id'     => $department->supervisor_id,      // ← Audit Trail
                    'safety_officer_id' => $department->safety_officer_id,  // ← Audit Trail
                    'jenis_pekerjaan'   => $jenisPekerjaan,
                    'nama_pekerjaan'    => $namaPekerjaan,
                    'deskripsi'         => $deskripsi ?: null,
                    'tanggal_kerja'     => $parsedDate->format('Y-m-d'),
                    'jam_mulai'         => $jamMulai ?: null,
                    'jam_selesai'       => $jamSelesai ?: null,
                    'gedung'            => $gedung ?: null,
                    'area'              => $area ?: null,
                    'lokasi'            => $lokasi ?: null,
                    'tingkat_risiko'    => $tingkatRisiko ?: null,
                    'apd'               => [],
                    'status'            => $allowedStatuses[$normalizedStatus],
                    'catatan_supervisor' => $catatanSupervisor ?: null,
                    'catatan_safety'     => $catatanSafety ?: null,
                ]);

                $imported++;
            }

            // Hanya proses sheet pertama
            break;
        }

        $reader->close();

        // ── Build response message ──
        $message = "{$imported} data berhasil diimport.";
        if ($skipped > 0) {
            $message .= " {$skipped} baris dilewati.";
        }

        return redirect()->route('admin.laporan')
            ->with('success', $message)
            ->with('import_errors', $errors);
    }
}
