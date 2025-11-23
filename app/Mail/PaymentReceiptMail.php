<?php

namespace App\Mail;

use App\Models\Reservation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Barryvdh\DomPDF\Facade\Pdf; 
class PaymentReceiptMail extends Mailable
{
    use Queueable, SerializesModels;

    public $reservation;

    /**
     * Create a new message instance.
     */
    public function __construct(Reservation $reservation)
    {
        $this->reservation = $reservation;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        // 1. Data Statis Toko (Bisa kamu ganti atau ambil dari Config/DB)
        $shopData = [
            'reservation' => $this->reservation,
            'app_name'    => 'HOMEY CAFE',
            'alamat'      => 'Jl. Hr. Soebrantas, Panam, Pekanbaru',
            'ig'          => '@homeycafe.pku',
        ];

        // 2. Load View PDF dengan data di atas
        // Pastikan ukuran kertas diset sesuai kebutuhan (custom thermal size atau A4)
        // Di sini saya set auto/custom sesuai CSS kamu (@page margin 0)
        $pdf = Pdf::loadView('customer.invoice_pdf', $shopData)
                  ->setPaper([0, 0, 226.77, 500], 'portrait'); 
                  // 226.77 pt = 80mm (Ukuran kertas thermal standar)
                  // Panjang 500 pt (fleksibel)

        // 3. Render Email
        return $this->subject('E-Struk Pembayaran - Homey Cafe')
                    ->view('emails.payment_receipt_body') // Body email (bukan lampiran)
                    ->with(['reservation' => $this->reservation])
                    ->attachData($pdf->output(), 'Struk-' . $this->reservation->id_transaksi . '.pdf', [
                        'mime' => 'application/pdf',
                    ]);
    }
}