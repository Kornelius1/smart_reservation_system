<?php

namespace App\Http\Controllers\Customer;

use App\Models\Reservation;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Http\Controllers\Controller;


class DokuController extends Controller
{
    /**
     * Menangani redirect dari DOKU setelah pembayaran berhasil.
     * Tombol "GO TO MERCHANT" mengarah ke sini.
     *
     * @param string $invoice Invoice number yang dikirim dari DOKU
     */
    public function handleSuccessRedirect(string $invoice)
    {
        // 1. Cari reservasi berdasarkan invoice number
        $reservation = Reservation::where('id_transaksi', $invoice)->first();

        // 2. Jika reservasi tidak ditemukan atau masih pending/gagal
        if (!$reservation) {
            // Jika Anda tidak ingin menunjukkan halaman error, Anda bisa redirect ke homepage
            return redirect()->route('customer.landing.page')->with('error', 'Transaksi tidak ditemukan atau belum selesai.');
        }

        // 3. Tentukan pesan status
        $statusMessage = match ($reservation->status) {
            'akan datang' => 'Pembayaran Anda SUKSES. Reservasi Anda dikonfirmasi!',
            'check-in' => 'Reservasi Anda sudah Check-in.',
            'pending' => 'Pembayaran belum terkonfirmasi. Mohon tunggu notifikasi DOKU (terkadang butuh 1-2 menit).',
            default => 'Pembayaran tidak berhasil atau status dibatalkan.',
        };

        // 4. Tampilkan halaman sukses
        return view('customer.SuccessPage', [
            'reservation' => $reservation,
            'statusMessage' => $statusMessage,
            'isSuccess' => $reservation->status === 'akan datang' || $reservation->status === 'check-in',
            'serviceFee' => config('doku.service_fee', 4440),
        ]);
    }

    /**
     * Menangani redirect dari DOKU jika pembayaran gagal atau dibatalkan.
     */
    public function handleFailedRedirect(string $invoice)
    {
        // Di sini Anda bisa mencari reservasi dan menampilkannya di halaman "Gagal"
        return view('customer.FailedPage', [
            'invoice' => $invoice,
            'message' => 'Pembayaran dibatalkan atau gagal. Silakan coba lagi.'
        ]);
    }
}