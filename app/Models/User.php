<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    // ─── ROLE CONSTANTS ───
    const ROLE_ADMIN          = 'admin';
    const ROLE_PEKERJA        = 'pekerja';
    const ROLE_SUPERVISOR     = 'supervisor';
    const ROLE_SAFETY_OFFICER = 'safety officer';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'department_id',
        'sub_department',
        'role',
        'status',
        'foto',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // ─── RELASI ───

    /**
     * Departemen tempat user ini bekerja.
     */
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    /**
     * Permit yang diajukan oleh user ini (sebagai pekerja).
     */
    public function permits(): HasMany
    {
        return $this->hasMany(Permit::class, 'user_id');
    }

    /**
     * Permit yang ditangani user ini sebagai supervisor.
     */
    public function supervisedPermits(): HasMany
    {
        return $this->hasMany(Permit::class, 'supervisor_id');
    }

    /**
     * Permit yang ditangani user ini sebagai safety officer.
     */
    public function reviewedPermits(): HasMany
    {
        return $this->hasMany(Permit::class, 'safety_officer_id');
    }

    // ─── HELPER ───

    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function isPekerja(): bool
    {
        return $this->role === self::ROLE_PEKERJA;
    }

    public function isSupervisor(): bool
    {
        return $this->role === self::ROLE_SUPERVISOR;
    }

    public function isSafetyOfficer(): bool
    {
        return $this->role === self::ROLE_SAFETY_OFFICER;
    }
}