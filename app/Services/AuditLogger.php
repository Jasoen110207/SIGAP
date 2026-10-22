<?php

namespace App\Services;

use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AuditLogger
{
    public function log(string $action, ?string $subjectType = null, ?int $subjectId = null, string $description = '', ?array $old = null, ?array $new = null): void
    {
        try {
            $request = request();
            $user = Auth::user();

            $payload = [
                'user_id' => $user?->id,
                'user_name' => $user?->name,
                'user_role' => $user?->role,
                'user_email' => $user?->email,
                'action' => $action,
                'subject_type' => $subjectType,
                'subject_id' => $subjectId,
                'description' => $description ?: $this->defaultDescription($action),
                'old_values' => $this->sanitize($old),
                'new_values' => $this->sanitize($new),
                'ip_address' => $request?->ip(),
                'user_agent' => $request?->header('User-Agent'),
            ];

            AuditLog::create($payload);
        } catch (\Throwable $e) {
            Log::warning('Gagal mencatat audit log: ' . $e->getMessage(), [
                'action' => $action,
                'subject_type' => $subjectType,
                'subject_id' => $subjectId,
            ]);
            report($e);
        }
    }

    private function defaultDescription(string $action): string
    {
        return match ($action) {
            'auth.login' => 'Login berhasil.',
            'auth.login_failed' => 'Login gagal.',
            'auth.logout' => 'Logout berhasil.',
            'auth.register' => 'Registrasi akun baru.',
            'auth.blocked_inactive' => 'Akun dinonaktifkan saat sesi aktif.',
            default => 'Aksi dicatat.'
        };
    }

    private function sanitize(?array $values): ?array
    {
        if ($values === null || $values === []) {
            return $values;
        }

        $sanitized = [];

        foreach ($values as $key => $value) {
            $normalizedKey = strtolower((string) $key);

            if (in_array($normalizedKey, ['password', 'password_confirmation', '_token', 'remember_token'], true)) {
                continue;
            }

            $sanitized[$key] = $this->normalizeValue($value);
        }

        return $sanitized === [] ? null : $sanitized;
    }

    private function normalizeValue(mixed $value): mixed
    {
        if (is_array($value)) {
            $result = [];
            foreach ($value as $key => $item) {
                $normalizedKey = strtolower((string) $key);

                if (in_array($normalizedKey, ['password', 'password_confirmation', '_token', 'remember_token'], true)) {
                    continue;
                }

                $result[$key] = $this->normalizeValue($item);
            }

            return $result;
        }

        if (is_string($value) && strlen($value) > 150) {
            return substr($value, 0, 150);
        }

        return $value;
    }
}
