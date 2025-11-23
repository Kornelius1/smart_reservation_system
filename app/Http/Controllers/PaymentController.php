<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Routing\Controller;

class PaymentController extends Controller
{
    public function downloadReceipt(string $invoice)
    {
        $reservation = Reservation::with([
            'products' => function ($query) {
                $query->select('products.id', 'products.name');
            }
        ])->where('id_transaksi', $invoice)->firstOrFail();

        $data = [
            'reservation' => $reservation,
            'app_name' => config('Homey Cafe', 'Homey Cafe'),
            'alamat' => 'Jl. Mawar, Simpang Baru, Kec. Tampan, Kota Pekanbaru, Riau 28293',
            'telp' => '@homey.cafe',
        ];

        // Gunakan lebar 80mm (226.77 pt), tinggi otomatis (gunakan nilai besar agar tidak terpotong)
        $pdf = Pdf::loadView('receipts.pdf', $data)
            ->setPaper([0, 0, 250, 360], 'portrait') // Tinggi 1000pt ≈ 353mm — cukup untuk struk panjang
            ->setOptions([
                'defaultFont' => 'DejaVu Sans',
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled' => true,
            ]);

        return $pdf->download("struk-{$invoice}.pdf");
    }
}