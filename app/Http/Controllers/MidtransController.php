<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reservation; // <-- Import model Reservation
use Midtrans\Config;
use Midtrans\Notification;

class MidtransController extends Controller
{
    /**
     * Menangani notifikasi (webhook) dari Midtrans.
     */
    public function handle(Request $request)
    {
        // 1. Set config Midtrans
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');

        // 2. Buat instance notifikasi Midtrans
        $notification = new Notification();

        // 3. Ambil data notifikasi
        $order_id = $notification->order_id;
        $transaction_status = $notification->transaction_status;
        $fraud_status = $notification->fraud_status;

        // 4. Cari reservasi berdasarkan order_id (id_transaksi kita)
        $reservation = Reservation::where('id_transaksi', $order_id)->first();

        if (!$reservation) {
            // Jika reservasi tidak ditemukan, kirim response error
            return response()->json(['message' => 'Reservation not found.'], 404);
        }

        // 5. LOGIKA UTAMA: Update status berdasarkan notifikasi
        // Hanya update jika statusnya masih 'pending'
        if ($reservation->status === 'pending') {
            
            if ($transaction_status == 'settlement') {
                // Status settlement: Pembayaran berhasil.
                if ($fraud_status == 'accept') {
                    // Fraud aman, ubah status ke "akan datang"
                    $reservation->update(['status' => 'akan datang']); // <-- GOAL #2 TERCAPAI
                }
            } else if ($transaction_status == 'capture') {
                // Status capture: Sama seperti settlement untuk kartu kredit.
                if ($fraud_status == 'accept') {
                    $reservation->update(['status' => 'akan datang']); // <-- GOAL #2 TERCAPAI
                }
            } else if ($transaction_status == 'expire') {
                // Pembayaran kadaluarsa
                $reservation->update(['status' => 'dibatalkan']);
            } else if ($transaction_status == 'cancel' || $transaction_status == 'deny') {
                // Pembayaran dibatalkan atau ditolak
                $reservation->update(['status' => 'dibatalkan']);
            }
        }

        // 6. Kirim response 200 OK ke Midtrans
        // Ini WAJIB agar Midtrans tahu notifikasi sudah diterima.
        return response()->json(['message' => 'Notification processed successfully.'], 200);
    }
}