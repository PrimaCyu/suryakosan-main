<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TurnstileRule implements ValidationRule
{
    /**
     * Jalankan validasi respon token Cloudflare Turnstile.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // 1. Jika fitur Turnstile dinonaktifkan di .env / config, bypass secara aman
        if (!config('services.turnstile.enabled', true)) {
            return;
        }

        $secretKey = config('services.turnstile.secret_key');

        // Jika secret key belum diisi, lewati agar sistem tidak macet
        if (empty($secretKey)) {
            return;
        }

        // 2. Token wajib ada dari input browser
        if (empty($value) || !is_string($value)) {
            $fail('Verifikasi keamanan bot (Cloudflare Turnstile) wajib diselesaikan.');
            return;
        }

        // 3. Verifikasi token ke API resmi Cloudflare Turnstile
        try {
            $response = Http::asForm()
                ->timeout(5)
                ->post('https://challenges.cloudflare.com/turnstile/v0/siteverify', [
                    'secret'   => $secretKey,
                    'response' => $value,
                    'remoteip' => request()->ip(),
                ]);

            if ($response->successful()) {
                $result = $response->json();

                if (!isset($result['success']) || $result['success'] !== true) {
                    Log::warning('Turnstile verification rejected by Cloudflare', [
                        'ip'          => request()->ip(),
                        'error-codes' => $result['error-codes'] ?? [],
                    ]);
                    $fail('Verifikasi bot gagal atau tiket keamanan telah kadaluarsa. Silakan coba kembali.');
                }
            } else {
                // Cloudflare endpoint mengembalikan status HTTP non-200 (misal 500/502).
                // Fail-open: catat log peringatan tanpa memblokir pengguna asli jika server Cloudflare sedang gangguan.
                Log::warning('Cloudflare Turnstile API returned HTTP status: ' . $response->status());
            }
        } catch (\Throwable $e) {
            // Jika terjadi timeout jaringan atau DNS failure pada koneksi Cloudflare,
            // fail-open agar sistem tidak mengalami crash fatal 500.
            Log::warning('Cloudflare Turnstile connection failed: ' . $e->getMessage());
        }
    }
}
