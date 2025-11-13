<?php

namespace App\Helpers;

use Illuminate\Support\Str;
use Illuminate\Http\Request; // <-- Pastikan 'use' statement ini ada
use Illuminate\Support\Facades\Log; // <-- Tambahkan 'use' statement ini

class DokuSignatureHelper
{
    /**
     * [FUNGSI YANG SUDAH ADA]
     * Menghasilkan header tanda tangan DOKU untuk permintaan POST/PUT.
     * (Tidak ada perubahan di sini, ini sudah benar)
     */
    public static function generate(string $jsonBody, string $requestTarget): array
    {
        // 1. Ambil Kredensial
        $clientId = config('doku.client_id');
        $secretKey = config('doku.secret_key');
        if (!$clientId || !$secretKey) {
            throw new \Exception('DOKU_CLIENT_ID atau DOKU_SECRET_KEY belum diatur di .env');
        }

        // 2. Buat Komponen Dinamis
        $requestId = Str::uuid()->toString();
        $requestTimestamp = gmdate("Y-m-d\TH:i:s\Z"); 

        // 3. Buat Digest
        $digest = self::generateDigest($jsonBody);

        // 4. Susun String-to-Sign
        $stringToSign = "Client-Id:" . $clientId . "\n" .
                        "Request-Id:" . $requestId . "\n" .
                        "Request-Timestamp:" . $requestTimestamp . "\n" .
                        "Request-Target:" . $requestTarget . "\n" .
                        "Digest:" . $digest;

        // 5. Buat Tanda Tangan (Signature)
        $signature = self::generateHmac($stringToSign, $secretKey);

        // 6. Kembalikan array header
        return [
            'Client-Id' => $clientId,
            'Request-Id' => $requestId,
            'Request-Timestamp' => $requestTimestamp,
            'Signature' => $signature
        ];
    }


    /**
     * [FUNGSI YANG DIPERBARUI]
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
            
            // [PERBAIKAN]
            // Dokumentasi Best Practice Notifikasi (Non-SNAP)
            // secara spesifik meminta 'Request-Timestamp', BUKAN 'Response-Timestamp'.
            $dokuTimestamp = $request->header('Request-Timestamp'); 
            
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

            // 4. [PERBAIKAN] Buat ulang 'String-to-Sign'
            // Menggunakan 'Request-Timestamp' sesuai dokumentasi
            $stringToSign = "Client-Id:" . $dokuClientId . "\n" .
                            "Request-Id:" . $dokuRequestId . "\n" .
                            "Request-Timestamp:" . $dokuTimestamp . "\n" . // <-- PERBAIKAN DI SINI
                            "Request-Target:" . $requestTarget . "\n" .
                            "Digest:" . $ourDigest;

            // 5. Buat ulang 'Signature'
            $ourSignature = self::generateHmac($stringToSign, $secretKey);
            
            // 6. Bandingkan dengan aman (mencegah timing attacks)
            return hash_equals($ourSignature, $dokuSignature);

        } catch (\Exception $e) {
            Log::error('DOKU Signature Validation Exception', ['error' => $e->getMessage()]);
            return false;
        }
    }


    /**
     * [FUNGSI PRIVAT - TIDAK BERUBAH]
     */
    private static function generateDigest(string $jsonBody): string
    {
        $hashedBody = hash('sha256', $jsonBody, true);
        return base64_encode($hashedBody);
    }

    /**
     * [FUNGSI PRIVAT - TIDAK BERUBAH]
     */
    private static function generateHmac(string $stringToSign, string $secretKey): string
    {
        $hmac = hash_hmac('sha256', $stringToSign, $secretKey, true);
        $base64Hmac = base64_encode($hmac);
        return "HMACSHA256=" . $base64Hmac;
    }
}