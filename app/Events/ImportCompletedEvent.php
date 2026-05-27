<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ImportCompletedEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public int $importLogId;
    public int $successCount;
    public int $failureCount;
    public ?string $errorFileUrl;

    public function __construct(
        int $importLogId,
        int $successCount,
        int $failureCount,
        ?string $errorFileUrl = null
    ) {
        $this->importLogId  = $importLogId;
        $this->successCount = $successCount;
        $this->failureCount = $failureCount;
        $this->errorFileUrl = $errorFileUrl;
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
        return 'import.completed';
    }

    /**
     * Payload sent to the client.
     */
    public function broadcastWith(): array
    {
        return [
            'import_log_id'  => $this->importLogId,
            'success_count'  => $this->successCount,
            'failure_count'  => $this->failureCount,
            'error_file_url' => $this->errorFileUrl,
        ];
    }
}
