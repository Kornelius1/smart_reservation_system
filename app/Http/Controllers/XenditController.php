<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reservation; // <-- Import model Reservation

class XenditController extends Controller
{
    /**
     * Menangani notifikasi (webhook) dari Xendit.
     */
    public function handle(Request $request)
    {
        // 1. Verifikasi Callback Token
        // Ambil token dari header request
        $serverToken = $request->header('x-callback-token');
        $localToken = config('xendit.callback_token');

        if ($serverToken !== $localToken) {
            // Jika token tidak cocok, unauthorized
            return response()->json(['message' => 'Invalid callback token'], 401);
        }

        // 2. Ambil data JSON dari body request
        $payload = $request->all();

        // 3. Cek Tipe Event
        // Xendit mengirim banyak event, kita hanya peduli pada invoice yang terbayar
        // Eventnya adalah 'invoice.paid' atau 'payment.succeeded'
        // Untuk Invoice API, status yang kita cek adalah 'PAID'
        
        if (isset($payload['status']) && $payload['status'] === 'PAID') {
            
            $externalId = $payload['external_id'];
            
            // 4. Cari reservasi berdasarkan external_id (id_transaksi kita)
            $reservation = Reservation::where('id_transaksi', $externalId)->first();

            if ($reservation) {
                // 5. LOGIKA UTAMA: Update status
                // Hanya update jika statusnya masih 'pending'
                if ($reservation->status === 'pending') {
                    $reservation->update(['status' => 'akan datang']); // <-- GOAL #2 TERCAPAI
                }
                
                // 6. Kirim response 200 OK ke Xendit
                return response()->json(['message' => 'Webhook received successfully'], 200);
            }
        }

        // Jika event bukan 'PAID' atau reservasi tidak ditemukan,
        // tetap kirim 200 agar Xendit berhenti mengirim ulang.
        return response()->json(['message' => 'Event not relevant or not found'], 200);
    }
}