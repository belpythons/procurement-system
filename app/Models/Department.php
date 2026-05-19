<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Department extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_departemen',
        'supervisor_id',
        'safety_officer_id',
    ];

    // ─── RELASI ───

    /**
     * Supervisor yang bertanggung jawab atas departemen ini.
     */
    public function supervisor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'supervisor_id');
    }

    /**
     * Safety Officer yang bertanggung jawab atas departemen ini.
     */
    public function safetyOfficer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'safety_officer_id');
    }

    /**
     * Semua user (pekerja) yang berada di departemen ini.
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    /**
     * Semua permit yang berasal dari departemen ini.
     */
    public function permits(): HasMany
    {
        return $this->hasMany(Permit::class);
    }
}
