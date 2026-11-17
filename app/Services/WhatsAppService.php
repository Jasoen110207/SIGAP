<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    public static function sendMessage(?string $target, string $message): bool
    {
        $phone = self::normalizePhone($target);
        $token = config('services.whatsapp.token');
        $url = config('services.whatsapp.url', 'https://api.fonnte.com/send');

        if (blank($phone) || blank($token) || blank($url)) {
            return false;
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => $token,
            ])
                ->withoutVerifying()
                ->timeout(8)
                ->asForm()
                ->post($url, [
                    'target' => $phone,
                    'message' => $message,
                ]);

            if ($response->failed()) {
                Log::warning('WhatsApp gateway request failed.', [
                    'target' => $phone,
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);

                return false;
            }

            return true;
        } catch (\Throwable $e) {
            Log::warning('WhatsApp gateway exception.', [
                'target' => $phone,
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            return false;
        }
    }

    protected static function normalizePhone(?string $target): ?string
    {
        if (blank($target)) {
            return null;
        }

        $digits = preg_replace('/\D+/', '', $target);

        if (blank($digits)) {
            return null;
        }

        if (str_starts_with($digits, '62')) {
            return $digits;
        }

        if (str_starts_with($digits, '0')) {
            return '62' . substr($digits, 1);
        }

        if (str_starts_with($digits, '8')) {
            return '62' . $digits;
        }

        return $digits;
    }
}