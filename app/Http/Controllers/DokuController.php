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
        // 1. LANGKAH KRUSIAL: Log SEMUANYA
        // Kita log dulu untuk memastikan pesannya sampai.
        Log::info('DOKU NOTIFICATION RECEIVED:', $request->all());

        // 2. TODO: Verifikasi Signature Notifikasi
        // (Untuk keamanan, Anda harus memverifikasi header 'Signature'
        // yang dikirim DOKU, mirip cara kita membuat signature.
        // Kita bisa lakukan ini nanti, yang penting fungsionalitas dulu.)

        // 3. Ambil data penting dari DOKU
        // (Struktur body DOKU bisa berbeda, cek log Anda untuk pastinya)
        $transactionStatus = $request->input('transaction.status');
        $invoiceNumber = $request->input('order.invoice_number');

        // 4. Cari dan Update Database
        // Cek apakah transaksinya 'SUCCESS' dan ada invoice number
        if ($transactionStatus == 'SUCCESS' && $invoiceNumber) {

            // Cari reservasi di database kita yang statusnya masih 'pending'
            $reservation = Reservation::where('id_transaksi', $invoiceNumber)
                                      ->where('status', 'pending')
                                      ->first();

            if ($reservation) {
                // DITEMUKAN! Update statusnya.
                $reservation->update([
                    'status' => 'akan datang' // Atau 'paid', 'confirmed', dll.
                ]);

                Log::info('RESERVATION UPDATED: ' . $invoiceNumber . ' to "akan datang"');

            } else {
                // Transaksi sukses, tapi tidak ditemukan di DB (atau sudah di-update)
                Log::warning('RESERVATION NOT FOUND (or already updated): ' . $invoiceNumber);
            }
        }

        // 5. Kirim balasan 'OK' ke DOKU
        // Ini WAJIB. Jika DOKU tidak menerima 200 OK,
        // mereka akan terus mengirim notifikasi yang sama berulang-ulang.
        return response()->json(['status' => 'success'], 200);
    }
}