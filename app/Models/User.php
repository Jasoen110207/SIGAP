<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    public const ROLE_USER = 'user';
    public const ROLE_ADMIN = 'admin';
    public const ROLE_PETUGAS = 'petugas';

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'category_id',
        'phone',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Laporan yang dipegang / diselesaikan petugas ini.
     */
    public function handledReports(): HasMany
    {
        return $this->hasMany(Report::class, 'petugas_id');
    }

    /**
     * Kategori pekerjaan (khusus petugas).
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function reports(): HasMany
    {
        return $this->hasMany(Report::class);
    }

    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function isPetugas(): bool
    {
        return $this->role === self::ROLE_PETUGAS;
    }

    public function isWarga(): bool
    {
        return $this->role === self::ROLE_USER;
    }
}
