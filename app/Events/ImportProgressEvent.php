<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ImportProgressEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public int $importLogId;
    public int $processedRows;
    public int $totalRows;
    public int $percentage;

    public function __construct(int $importLogId, int $processedRows, int $totalRows)
    {
        $this->importLogId   = $importLogId;
        $this->processedRows = $processedRows;
        $this->totalRows     = $totalRows;
        $this->percentage    = $totalRows > 0
            ? (int) round(($processedRows / $totalRows) * 100)
            : 0;
    }

    /**
     * Broadcast on a private channel scoped to this import job.
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel("import.{$this->importLogId}"),
        ];
    }

    /**
     * Event name for the frontend Echo listener.
     */
    public function broadcastAs(): string
    {
        return 'import.progress';
    }

    /**
     * Payload sent to the client.
     */
    public function broadcastWith(): array
    {
        return [
            'import_log_id'  => $this->importLogId,
            'processed_rows' => $this->processedRows,
            'total_rows'     => $this->totalRows,
            'percentage'     => $this->percentage,
        ];
    }
}
