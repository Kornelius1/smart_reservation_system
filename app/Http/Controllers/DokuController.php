<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http; // <-- Import Laravel HTTP Client
use Illuminate\Support\Str;

class DokuController extends Controller
{
    /**
     * Membuat request pembayaran ke DOKU dan mengembalikan URL pembayaran.
     */
    public function createPayment(Request $request)
    {
        // 1. Dapatkan data dari request (misal: ID reservasi, total harga)
        // Ini HANYA CONTOH. Anda harus mengambil data ini dari database
        // berdasarkan ID reservasi yang di-request.
        $invoiceNumber = 'INV-' . time(); // HARUS UNIK
        $totalAmount = 150000; // Contoh total
        $customerName = "Nama Pelanggan";
        $customerEmail = "email@pelanggan.com";

        // 2. Siapkan variabel DOKU
        $clientId = config('services.doku.client_id'); // Ambil dari .env (via config)
        $secretKey = config('services.doku.secret_key');
        $apiUrl = config('services.doku.api_url');
        $path = '/checkout/v1/payment'; // Contoh path API DOKU (cek dokumentasi)

        // 3. Siapkan data yang akan dikirim (Request Body)
        // Struktur body ini WAJIB mengikuti dokumentasi DOKU
        $body = [
            'order' => [
                'invoice_number' => $invoiceNumber,
                'amount' => $totalAmount
            ],
            'payment' => [
                'payment_due_date' => 60 // Waktu kadaluarsa (menit)
            ],
            'customer' => [
                'name' => $customerName,
                'email' => $customerEmail
            ]
        ];

        // 4. GENERATE SIGNATURE (Bagian Paling Penting & Tricky)
        // Cara membuat signature BISA BERBEDA. SELALU CEK DOKUMENTASI DOKU.
        // Ini adalah contoh umum:
        $requestTimestamp = now()->toIso8601String();
        $requestId = (string) Str::uuid();
        $digest = base64_encode(hash('sha256', json_encode($body), true));

        $stringToSign = "Client-Id:" . $clientId . "\n"
                      . "Request-Id:" . $requestId . "\n"
                      . "Request-Timestamp:" . $requestTimestamp . "\n"
                      . "Request-Target:" . $path . "\n"
                      . "Digest:" . $digest;

        $signature = base64_encode(hash_hmac('sha256', $stringToSign, $secretKey, true));

        // 5. Kirim Request ke DOKU
        try {
            $response = Http::withHeaders([
                'Client-Id' => $clientId,
                'Request-Id' => $requestId,
                'Request-Timestamp' => $requestTimestamp,
                'Signature' => "HMACSHA256=" . $signature,
            ])
            ->withBody(json_encode($body), 'application/json')
            ->post($apiUrl . $path);

            $responseData = $response->json();

      
            if ($response->successful() && isset($responseData['response']['payment']['url'])) {

                return response()->json([
                    'success' => true,
                    'payment_url' => $responseData['response']['payment']['url']
                ]);
            }

            // Jika gagal
            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat pembayaran DOKU',
                'details' => $responseData
            ], 500);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    // Kita akan tambahkan method handleNotification di sini nanti
}
