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
        $height_base = 240; 
        
        $height_per_item = 15; 

        $total_items = $this->reservation->products->count();

        $buffer = 10;

        $total_height = $height_base + ($total_items * $height_per_item) + $buffer;

        $customPaper = [0, 0, 227, $total_height];

        $shopData = [
            'reservation' => $this->reservation,
            'app_name'    => 'HOMEY CAFE',
            'alamat'      => 'Jl. Mawar, Simpang Baru, Kec. Tampan, Kota Pekanbaru, Riau 28293',
            'ig'          => '@homeycafe.pku',
        ];
 
        $pdf = Pdf::loadView('receipts.pdf', $shopData)
            ->setPaper($customPaper, 'portrait') 
            ->setOptions([
                'defaultFont' => 'Courier',
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled' => true,
                'dpi' => 72,
            ]);

        return $this->subject('Struk Pembayaran - Homey Cafe')
                        ->view('emails.payment_receipt_body')
                        ->attachData($pdf->output(), 'Struk-' . $this->reservation->id_transaksi . '.pdf', [
                            'mime' => 'application/pdf',
                        ]);
    }
}