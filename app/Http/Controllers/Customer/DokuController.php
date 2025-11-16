<?php

namespace App\Http\Controllers\Customer;


use PDF;
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

   
   public function downloadReceipt(string $invoice)
    {
        try {
            // 1. Cari reservasi (termasuk data produk)
            $reservation = Reservation::with('products')
                                ->where('id_transaksi', $invoice)
                                ->firstOrFail(); // Gagal jika tidak ditemukan

            // 2. Load view Blade yang kita buat tadi
            $pdf = Pdf::loadView('customer.Receipt', [
                'reservation' => $reservation
            ]);

            // 3. Buat nama file
            $fileName = 'struk-reservasi-' . $reservation->id_transaksi . '.pdf';

            // 4. Download file
            return $pdf->download($fileName);

        } catch (\Exception $e) {
            // Jika reservasi tidak ditemukan atau ada error
            return redirect()->route('customer.landing.page')
                             ->with('error', 'Gagal men-download struk: Reservasi tidak ditemukan.');
        }
    }
}