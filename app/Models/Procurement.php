<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Procurement extends Model
{
    /**
     * The table associated with the model.
     */
    protected $table = 'procurements';

    /**
     * Mass-assignable attributes.
     */
    protected $fillable = [
        'kode_pengadaan',
        'nama_barang',
        'vendor',
        'tanggal_te',
        'tanggal_rete',
        'tanggal_po',
        'tanggal_in',
        'tanggal_out',
        'status',
    ];

    /**
     * Attribute casting.
     */
    protected $casts = [
        'tanggal_te'   => 'date',
        'tanggal_rete' => 'date',
        'tanggal_po'   => 'date',
        'tanggal_in'   => 'date',
        'tanggal_out'  => 'date',
    ];

    /**
     * Boot method — register model event for auto-computing status.
     *
     * Priority logic (highest wins):
     *   tanggal_po filled   → "PO"
     *   tanggal_rete filled → "RE-TE"
     *   tanggal_te filled   → "TE"
     *   none filled         → "RP"
     */
    protected static function booted(): void
    {
        static::saving(function (Procurement $procurement) {
            $procurement->status = $procurement->computeStatus();
        });
    }

    /**
     * Compute the procurement status based on which date fields are filled.
     */
    public function computeStatus(): string
    {
        if (!is_null($this->tanggal_po)) {
            return 'PO';
        }

        if (!is_null($this->tanggal_rete)) {
            return 'RE-TE';
        }

        if (!is_null($this->tanggal_te)) {
            return 'TE';
        }

        return 'RP';
    }

    /**
     * Get the active/latest phase date.
     */
    public function getTanggalAttribute()
    {
        return $this->tanggal_po 
            ?? $this->tanggal_rete 
            ?? $this->tanggal_te 
            ?? $this->tanggal_in 
            ?? $this->created_at;
    }

    /**
     * Status constants for reference throughout the application.
     */
    const STATUS_RP   = 'RP';
    const STATUS_TE   = 'TE';
    const STATUS_RETE = 'RE-TE';
    const STATUS_PO   = 'PO';

    /**
     * Get all valid statuses.
     */
    public static function validStatuses(): array
    {
        return [
            self::STATUS_RP,
            self::STATUS_TE,
            self::STATUS_RETE,
            self::STATUS_PO,
        ];
    }
}