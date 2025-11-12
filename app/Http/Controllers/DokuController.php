<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log; // <-- PENTING: Tambahkan ini
use App\Models\Reservation;         // <-- PENTING: Tambahkan ini

class DokuController extends Controller
{
    /**
     * Menangani notifikasi (webhook) yang masuk dari DOKU.
     * Ini adalah komunikasi Server-ke-Server.
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
            
            // Panggil Helper untuk verifikasi
            $ourSignature = \App\Helpers\DokuSignatureHelper::generateSignature(
                $clientId,
                $secretKey,
                $headerRequestId,
                $headerTimestamp,
                $requestPath,
                $requestBody
            );
            
            // Bandingkan hasil
            if (!hash_equals($ourSignature, $headerSignature)) {
                Log::error('DOKU SIGNATURE MISMATCH.');
                Log::error('Our Signature: ' . $ourSignature);
                Log::error('DOKU Signature: ' . $headerSignature);
                return response()->json(['status' => 'error', 'message' => 'Invalid signature'], 401);
            }

            // 3. Jika lolos, artinya aman
            Log::info('DOKU SIGNATURE VERIFIED.');

        } catch (\Exception $e) {
            Log::error('DOKU SIGNATURE VERIFICATION FAILED: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => 'Verification failed'], 500);
        }

        // ============================================================
        // 3️⃣ AMBIL FIELD UTAMA DARI PAYLOAD
        // ============================================================
        $transactionStatus = $request->input('transaction.status') ?? $request->input('transactionStatus');
        $invoiceNumber     = $request->input('order.invoice_number') ?? $request->input('orderId');
        
        Log::info('DOKU PAYMENT INFO:', [
            'invoice' => $invoiceNumber,
            'status'  => $transactionStatus,
            'data'    => $request->all() // Log semua data untuk info
        ]);

        // ============================================================
        // 4️⃣ UPDATE DATABASE BILA PEMBAYARAN SUKSES
        // ============================================================
        // Ini adalah "Source of Truth" Anda. HANYA update DB di sini.
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
            // Anda bisa tambahkan logika untuk status 'FAILED' di sini jika perlu
        }

        // ============================================================
        // 5️⃣ KIRIM RESPON SUKSES KE DOKU
        // ============================================================
        return response()->json(['status' => 'success'], 200);
    }

    /**
     * Menangani redirect pelanggan ke halaman "Sukses".
     * Ini HANYA untuk User Experience (UX), JANGAN update DB di sini.
     */
    public function success(Request $request)
    {
        $invoice = $request->query('invoice_number');
        Log::info("User REDIRECTED to SUCCESS page for invoice: {$invoice}");

        // Tampilkan view "sukses" Anda.
        // Anda bisa teruskan $invoice ke view jika perlu.
        return view('customer.payment-success', [
            'invoice_number' => $invoice
        ]);
    }

    /**
     * Menangani redirect pelanggan ke halaman "Gagal".
     * Ini HANYA untuk User Experience (UX).
     */
    public function failed(Request $request)
    {
        $invoice = $request->query('invoice_number');
        Log::warning("User REDIRECTED to FAILED page for invoice: {$invoice}");

        // Tampilkan view "gagal" Anda.
        return view('customer.payment-failed', [
            'invoice_number' => $invoice,
            'message' => $request->query('message', 'Pembayaran Anda gagal atau dibatalkan.')
        ]);
    }
}