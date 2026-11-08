<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;


class User extends Authenticatable
{
    use HasFactory, Notifiable;

    public const ROLE_USER = 'user';
    public const ROLE_ADMIN = 'admin';
    public const ROLE_PETUGAS = 'petugas';

    protected $fillable = [
        'name',
        'email',
        'login_token',
        'password',
        'role',
        'category_id',
        'phone',
        'address',
        'is_active',
        'password_changed_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'password_changed_at' => 'datetime',
    ];

    /**
     * Riwayat password yang pernah dipakai pengguna.
     */
    public function passwordHistories(): HasMany
    {
        return $this->hasMany(PasswordHistory::class);
    }

    /**
     * Mengecek apakah password sudah melebihi masa berlaku demo (5 detik).
     * Digunakan untuk presentasi, bukan untuk produksi.
     */
    public function passwordExpiredForDemo(): bool
    {
        return $this->password_changed_at !== null && $this->password_changed_at->diffInSeconds(now()) > 5;
    }

    /**
     * Mengecek apakah password sudah lebih dari 90 hari sejak terakhir diubah.
     */
    public function passwordExpired(): bool
    {
        return $this->password_changed_at !== null && $this->password_changed_at->lessThan(now()->subDays(90));
    }

    /**
     * Mengecek apakah password baru sudah pernah dipakai sebelumnya.
     */
    public function hasUsedPassword(string $password): bool
    {
        if (Hash::check($password, $this->password)) {
            return true;
        }

        foreach ($this->passwordHistories()->get() as $history) {
            if (Hash::check($password, $history->password_hash)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Menyimpan password yang baru ke riwayat password.
     */
    public function recordPasswordHistory(string $password): void
    {
        $this->passwordHistories()->create([
            'password_hash' => Hash::make($password),
        ]);
    }

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
