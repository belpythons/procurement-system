<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ImportLog extends Model
{
    /**
     * The table associated with the model.
     */
    protected $table = 'import_logs';

    /**
     * Mass-assignable attributes.
     */
    protected $fillable = [
        'user_id',
        'file_name',
        'total_rows',
        'processed_rows',
        'success_count',
        'failure_count',
        'error_file_path',
        'status',
    ];

    /**
     * Attribute casting.
     */
    protected $casts = [
        'total_rows'     => 'integer',
        'processed_rows' => 'integer',
        'success_count'  => 'integer',
        'failure_count'  => 'integer',
    ];

    /**
     * Status constants.
     */
    const STATUS_PROCESSING = 'processing';
    const STATUS_COMPLETED  = 'completed';
    const STATUS_FAILED     = 'failed';

    /**
     * Relationship: belongs to User.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Check if this import has an error log file available.
     */
    public function hasErrorLog(): bool
    {
        return !is_null($this->error_file_path)
            && $this->failure_count > 0;
    }

    /**
     * Mark import as completed.
     */
    public function markCompleted(int $successCount, int $failureCount, ?string $errorFilePath = null): void
    {
        $this->update([
            'status'          => self::STATUS_COMPLETED,
            'success_count'   => $successCount,
            'failure_count'   => $failureCount,
            'error_file_path' => $errorFilePath,
        ]);
    }

    /**
     * Mark import as failed.
     */
    public function markFailed(): void
    {
        $this->update([
            'status' => self::STATUS_FAILED,
        ]);
    }
}
