<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use LogicException;

class AuditLog extends Model
{
    public $timestamps = false;

    protected $attributes = [
        'user_id' => null,
        'user_name' => null,
        'user_role' => null,
        'user_email' => null,
        'action' => null,
        'subject_type' => null,
        'subject_id' => null,
        'description' => '',
        'old_values' => null,
        'new_values' => null,
        'ip_address' => null,
        'user_agent' => null,
    ];

    protected $fillable = [
        'user_id',
        'user_name',
        'user_role',
        'user_email',
        'action',
        'subject_type',
        'subject_id',
        'description',
        'old_values',
        'new_values',
        'ip_address',
        'user_agent',
        'created_at',
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
        'created_at' => 'datetime',
    ];

    public static function actionLabel(string $action): string
    {
        return match ($action) {
            'auth.login' => 'Login berhasil',
            'auth.login_failed' => 'Login gagal',
            'auth.logout' => 'Logout',
            'auth.register' => 'Registrasi warga',
            'auth.blocked_inactive' => 'Akun dinonaktifkan',
            'report.created' => 'Laporan dibuat',
            'report.validated' => 'Laporan disetujui',
            'report.rejected' => 'Laporan ditolak',
            'report.claimed' => 'Laporan diambil petugas',
            'report.released' => 'Laporan dikembalikan ke antrean',
            'report.completed' => 'Laporan selesai',
            'report.rated' => 'Penilaian laporan',
            'petugas.created' => 'Petugas dibuat',
            'petugas.updated' => 'Petugas diperbarui',
            'petugas.deactivated' => 'Petugas dinonaktifkan',
            'petugas.activated' => 'Petugas diaktifkan',
            default => ucfirst(str_replace(['.', '_'], ' ', $action)),
        };
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getDisplayObjectAttribute(): string
    {
        $subjectType = data_get($this, 'subject_type');
        $subjectId = data_get($this, 'subject_id');

        if (! $subjectType || $subjectId === null) {
            return '—';
        }

        return match ($subjectType) {
            'report' => 'Laporan #' . $subjectId,
            'user' => 'Akun #' . $subjectId,
            'petugas' => 'Petugas #' . $subjectId,
            default => ucfirst($subjectType) . ' #' . $subjectId,
        };
    }

    public function getActorLabelAttribute(): string
    {
        $userName = trim((string) data_get($this, 'user_name', ''));
        $userRole = (string) data_get($this, 'user_role', 'tanpa peran');

        if ($userName !== '') {
            return trim($userName . ' (' . $userRole . ')');
        }

        return 'Sistem';
    }

    public function getCreatedAtLabelAttribute(): string
    {
        $createdAt = data_get($this, 'created_at');

        return $createdAt
            ? $createdAt->locale('id')->translatedFormat('d F Y, H:i:s')
            : '—';
    }

    protected static function booted(): void
    {
        static::updating(function (self $model) {
            throw new LogicException('Audit log tidak dapat diubah.');
        });

        static::deleting(function (self $model) {
            throw new LogicException('Audit log tidak dapat dihapus.');
        });
    }
}
