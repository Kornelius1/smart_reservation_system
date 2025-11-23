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

        // Estimasi tinggi Header (Logo, Alamat, Info Transaksi) + Footer (Total, Note)
        // Nilai ini didapat dari kira-kira layout HTML (sekitar 5-6 cm)
        $height_base = 240; 

        // Estimasi tinggi per baris produk
        // Font 9pt dengan line-height 1.3 + padding = sekitar 15-20 point per item
        $height_per_item = 35; 
        
        // Hitung total item
        $total_items = $reservation->products->count();

        // Tambahkan buffer (jarak aman) sedikit
        $buffer = 20;

        // Total Tinggi (dalam point)
        $total_height = $height_base + ($total_items * $height_per_item) + $buffer;

        $data = [
            'reservation' => $reservation,
            'app_name' => config('Homey Cafe', 'Homey Cafe'),
            'alamat' => 'Jl. Mawar, Simpang Baru, Kec. Tampan, Kota Pekanbaru, Riau 28293',
            'ig' => '@homey.cafe',
        ];

        $customPaper = [0, 0, 227, $total_height];

        // Gunakan lebar 80mm (226.77 pt), tinggi otomatis (gunakan nilai besar agar tidak terpotong)
        $pdf = Pdf::loadView('receipts.pdf', $data)
            ->setPaper($customPaper, 'portrait') // Tinggi 1000pt ≈ 353mm — cukup untuk struk panjang
            ->setOptions([
                'defaultFont' => 'Courier',
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled' => true,
                'dpi' => 72,
            ]);

        $pdf->render();

        return $pdf->download("struk-{$invoice}.pdf");
    }
}