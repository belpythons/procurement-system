<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Permit extends Model
{
    use HasFactory;

    // ─── STATUS CONSTANTS ───
    const STATUS_PENDING   = 'Pending';
    const STATUS_DISETUJUI = 'Disetujui';
    const STATUS_DITOLAK   = 'Ditolak';
    const STATUS_SELESAI   = 'Selesai';

    protected $fillable = [
        'nomor_permit',
        'user_id',
        'department_id',
        'supervisor_id',
        'safety_officer_id',
        'jenis_pekerjaan',
        'nama_pekerjaan',
        'deskripsi',
        'tanggal_kerja',
        'jam_mulai',
        'jam_selesai',
        'gedung',
        'area',
        'lokasi',
        'tingkat_risiko',
        'apd',
        'status',
        'catatan_supervisor',
        'catatan_safety',
        'evaluasi_risiko',
    ];

    protected $casts = [
        'apd'           => 'array',
        'tanggal_kerja' => 'date',
    ];

    // ─── RELASI ───

    /**
     * Pekerja yang mengajukan permit ini.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Alias: pekerja yang mengajukan.
     */
    public function pekerja(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Departemen terkait.
     */
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    /**
     * Supervisor yang menangani permit ini.
     */
    public function supervisor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'supervisor_id');
    }

    /**
     * Safety Officer yang menangani permit ini.
     */
    public function safetyOfficer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'safety_officer_id');
    }

    // ─── HELPER ───

    /**
     * Generate nomor permit berikutnya: PRM-001, PRM-002, ...
     */
    public static function generateNomorPermit(): string
    {
        $last = static::orderByDesc('id')->first();
        $nextId = $last ? $last->id + 1 : 1;
        return 'PRM-' . str_pad($nextId, 3, '0', STR_PAD_LEFT);
    }
}