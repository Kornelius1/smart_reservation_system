<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log; // <-- PENTING: Tambahkan ini
use App\Models\Reservation;         // <-- PENTING: Tambahkan ini

class DokuController extends Controller
{

    // ... (Method createPayment Anda yang sudah ada ada di sini) ...


    /**
     * Menangani notifikasi (webhook) yang masuk dari DOKU.
     */
  public function handleNotification(Request $request)
{


    Log::info('--- DOKU WEBHOOK HIT ---');
    Log::info('FULL RAW BODY: ' . $request->getContent());
    Log::info('FULL HEADERS:', $request->headers->all());
    Log::info('REQUEST PATH: ' . $request->getPathInfo());
    
    // ============================================================
    // 1️⃣ LOG SEMUA DATA MASUK
    // ============================================================
    Log::info('DOKU NOTIFICATION RECEIVED:', $request->all());
    Log::info('DOKU HEADERS:', $request->headers->all());

    // ============================================================
    // 2️⃣ VERIFIKASI SIGNATURE (HMAC SHA256)
    // ============================================================
    try {
        $clientId = config('services.doku.client_id');
        $secretKey = config('services.doku.secret_key');

        $headerTimestamp = $request->header('Request-Timestamp');
        $headerSignature = $request->header('Signature');
        $headerRequestId = $request->header('Request-Id');

        // Pastikan header lengkap
        if (!$headerTimestamp || !$headerSignature || !$headerRequestId) {
            Log::warning('DOKU NOTIFICATION: Missing required headers.');
            return response()->json(['status' => 'error', 'message' => 'Missing headers'], 400);
        }

        // Ambil raw body dan path
        $requestBody = $request->getContent();
        $requestPath = $request->getPathInfo();
       


        // Buat Digest
        $digest = base64_encode(hash('sha256', $requestBody, true));

        // Buat String-To-Sign
        $stringToSign = "Client-Id:" . $clientId . "\n"
            . "Request-Id:" . $headerRequestId . "\n"
            . "Request-Timestamp:" . $headerTimestamp . "\n"
            . "Request-Target:" . $requestPath . "\n"
            . "Digest:" . $digest;

        // Buat Signature Lokal
        $ourSignature = 'HMACSHA256=' . base64_encode(hash_hmac('sha256', $stringToSign, $secretKey, true));

        // Bandingkan hasil
        if (!hash_equals($ourSignature, $headerSignature)) {
            Log::error('DOKU SIGNATURE MISMATCH.');
            Log::error('String-to-Sign: ' . $stringToSign);
            Log::error('Our Signature: ' . $ourSignature);
            Log::error('DOKU Signature: ' . $headerSignature);
            return response()->json(['status' => 'error', 'message' => 'Invalid signature'], 401);
        }

    } catch (\Exception $e) {
        Log::error('DOKU SIGNATURE VERIFICATION FAILED: ' . $e->getMessage());
        return response()->json(['status' => 'error', 'message' => 'Verification failed'], 500);
    }

    // ============================================================
    // 3️⃣ AMBIL FIELD UTAMA DARI PAYLOAD
    // ============================================================
    $transactionStatus = $request->input('transaction.status') ?? $request->input('transactionStatus');
    $invoiceNumber     = $request->input('order.invoice_number') ?? $request->input('orderId');
    $amount            = $request->input('order.amount');
    $serviceId         = $request->input('service.id');
    $acquirerId        = $request->input('acquirer.id');
    $channelId         = $request->input('channel.id');
    $transactionDate   = $request->input('transaction.date');
    $requestId         = $request->input('transaction.original_request_id');

    Log::info('DOKU PAYMENT INFO:', [
        'invoice'      => $invoiceNumber,
        'status'       => $transactionStatus,
        'amount'       => $amount,
        'service'      => $serviceId,
        'acquirer'     => $acquirerId,
        'channel'      => $channelId,
        'trans_date'   => $transactionDate,
        'request_id'   => $requestId,
    ]);

    // ============================================================
    // 4️⃣ UPDATE DATABASE BILA PEMBAYARAN SUKSES
    // ============================================================
    if ($transactionStatus === 'SUCCESS' && $invoiceNumber) {
        $reservation = Reservation::where('id_transaksi', $invoiceNumber)
            ->where('status', 'pending')
            ->first();

        if ($reservation) {
            $reservation->update([
                'status' => 'akan datang',
            ]);

            Log::info("RESERVATION UPDATED: {$invoiceNumber} -> 'akan datang'");
        } else {
            Log::warning("RESERVATION NOT FOUND or ALREADY UPDATED: {$invoiceNumber}");
        }
    } else {
        Log::warning("TRANSACTION NOT SUCCESS for {$invoiceNumber}, status: {$transactionStatus}");
    }

    // ============================================================
    // 5️⃣ KIRIM RESPON SUKSES KE DOKU
    // ============================================================
    return response()->json(['status' => 'success'], 200);
}

}