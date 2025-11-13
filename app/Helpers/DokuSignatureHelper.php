<?php

namespace App\Helpers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DokuSignatureHelper
{
/**
     * Menghasilkan header tanda tangan DOKU untuk permintaan POST/PUT.
     *
     * @param string $jsonBody Body permintaan dalam bentuk STRING JSON yang sudah di-encode. // <-- DIPERBAIKI
     * @param string $requestTarget Path endpoint API (misal: /checkout/v1/payment)
     * @return array Array yang berisi header untuk HTTP Client.
     * @throws \Exception Jika Client ID atau Secret Key tidak diatur.
     */
    public static function generate(string $jsonBody, string $requestTarget): array // <-- DIPERBAIKI
    {
        // 1. Ambil Kredensial dari file config
        $clientId = config('doku.client_id');
        $secretKey = config('doku.secret_key');

        if (!$clientId || !$secretKey) {
            // Ini adalah error fatal. Hentikan eksekusi.
            throw new \Exception('DOKU_CLIENT_ID atau DOKU_SECRET_KEY belum diatur di .env');
        }

        // 2. Buat Komponen Dinamis
        $requestId = Str::uuid()->toString(); // Menggunakan UUID untuk jaminan keunikan
        $requestTimestamp = gmdate("Y-m-d\TH:i:s\Z"); // Format ISO8601 UTC

        // 3. Buat Digest
        // [DIPERBAIKI] Kita tidak perlu json_encode lagi,
        // karena kita menerimanya sebagai string dari controller.
        $digest = self::generateDigest($jsonBody);

        // 4. Susun String-to-Sign
        $stringToSign = "Client-Id:" . $clientId . "\n" .
                        "Request-Id:" . $requestId . "\n" .
                        "Request-Timestamp:" . $requestTimestamp . "\n" .
                        "Request-Target:" . $requestTarget . "\n" .
                        "Digest:" . $digest;

        // 5. Buat Tanda Tangan (Signature)
        $signature = self::generateHmac($stringToSign, $secretKey);

        // 6. Kembalikan array header yang siap pakai
        return [
            'Client-Id' => $clientId,
            'Request-Id' => $requestId,
            'Request-Timestamp' => $requestTimestamp,
            'Signature' => $signature
        ];
    }
    /**
     * Helper privat untuk membuat Digest (SHA256 -> Base64)
     */
    private static function generateDigest(string $jsonBody): string
    {
        // Parameter 'true' di hash() mengembalikan data biner mentah
        $hashedBody = hash('sha256', $jsonBody, true);
        return base64_encode($hashedBody);
    }

    /**
     * Helper privat untuk membuat Signature (HMAC-SHA256 -> Base64 -> Prefix)
     */
    private static function generateHmac(string $stringToSign, string $secretKey): string
    {
        // Parameter 'true' di hash_hmac() mengembalikan data biner mentah
        $hmac = hash_hmac('sha256', $stringToSign, $secretKey, true);
        $base64Hmac = base64_encode($hmac);
        
        // Tambahkan prefix sesuai dokumentasi
        return "HMACSHA256=" . $base64Hmac;
    }


    /**
     * [FUNGSI BARU]
     * Memvalidasi tanda tangan notifikasi yang masuk dari DOKU.
     *
     * @param Request $request Request yang masuk dari DOKU
     * @return bool True jika tanda tangan valid
     */
    public static function validate(Request $request): bool
    {
        try {
            // 1. Ambil "Bahan Baku" dari Header DOKU
            $dokuClientId = $request->header('Client-Id');
            $dokuRequestId = $request->header('Request-Id');
            $dokuTimestamp = $request->header('Response-Timestamp'); // DOKU mengirim 'Response-Timestamp'
            $dokuSignature = $request->header('Signature');

            // 2. Ambil "Bahan Baku" dari Server Kita
            $secretKey = config('doku.secret_key');
            $requestTarget = '/' . $request->path(); // mis. '/api/doku/notification'
            $jsonBody = $request->getContent(); // Ambil body mentah

            if (!$dokuClientId || !$dokuRequestId || !$dokuTimestamp || !$dokuSignature || !$secretKey) {
                Log::warning('DOKU Validate: Missing required headers or secret key.');
                return false;
            }

            // 3. Buat ulang 'Digest'
            $ourDigest = self::generateDigest($jsonBody);

            // 4. Buat ulang 'String-to-Sign' (HARUS SESUAI DOKUMENTASI RESPONSE)
            $stringToSign = "Client-Id:" . $dokuClientId . "\n" .
                            "Request-Id:" . $dokuRequestId . "\n" .
                            "Response-Timestamp:" . $dokuTimestamp . "\n" . // <-- Kuncinya "Response-..."
                            "Request-Target:" . $requestTarget . "\n" .
                            "Digest:" . $ourDigest;

            // 5. Buat ulang 'Signature'
            // (Kita panggil helper 'generateHmac' yang sudah ada)
            $ourSignature = self::generateHmac($stringToSign, $secretKey);
            
            // 6. Bandingkan dengan aman (mencegah timing attacks)
            return hash_equals($ourSignature, $dokuSignature);

        } catch (\Exception $e) {
            Log::error('DOKU Signature Validation Exception', ['error' => $e->getMessage()]);
            return false;
        }
    }
}