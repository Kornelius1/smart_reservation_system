<?php

namespace App\Helpers;

class DokuSignatureHelper
{
    /**
     * Generate DOKU Signature (untuk request & webhook verification)
     *
     * @param string $clientId
     * @param string $secretKey
     * @param string $requestId
     * @param string $isoTimestamp
     * @param string $requestTarget
     * @param string $bodyJson
     * @return string
     */
    public static function generateSignature(
        string $clientId,
        string $secretKey,
        string $requestId,
        string $isoTimestamp,
        string $requestTarget,
        string $bodyJson
    ): string {
        // 1️⃣ Buat Digest dari body JSON
        $digest = base64_encode(hash('sha256', $bodyJson, true));

        // 2️⃣ Buat String-To-Sign sesuai urutan wajib DOKU
        $stringToSign =
            "Client-Id:" . $clientId . "\n" .
            "Request-Id:" . $requestId . "\n" .
            "Request-Timestamp:" . $isoTimestamp . "\n" .
            "Request-Target:" . $requestTarget . "\n" .
            "Digest:" . $digest;

        // 3️⃣ Generate HMAC-SHA256 Signature
        $hmac = hash_hmac('sha256', $stringToSign, $secretKey, true);
        $signature = base64_encode($hmac);

        // 4️⃣ Kembalikan dengan prefix sesuai dokumentasi
        return "HMACSHA256=" . $signature;
    }
}
