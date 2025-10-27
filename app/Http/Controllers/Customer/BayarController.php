<?php

namespace App\Http\Controllers\Customer;

use App\Models\Product;
use App\Models\Room;
use App\Models\Meja;
use App\Models\Reservation;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;
use Midtrans\Config; // <-- IMPORT MIDTRANS
use Midtrans\Snap;   // <-- IMPORT MIDTRANS

class BayarController extends Controller
{
    // ... (Fungsi show() TIDAK BERUBAH) ...
    public function show(Request $request)
    {
       // ... (kode fungsi show() Anda tetap sama)
        $validationResult = $this->validateOrder($request);

        if ($validationResult instanceof RedirectResponse) {
            return $validationResult;
        }

        $products = $validationResult['products'];
        $itemsFromRequest = $validationResult['items'];
        $totalPrice = $validationResult['totalPrice'];
        $reservationType = $validationResult['reservationType'];
        $reservationDetail = $validationResult['reservationDetail'];

        $cartItems = [];
        foreach ($products as $product) {
            $quantity = $itemsFromRequest[$product->id];
            $cartItems[] = [
                'id'       => $product->id,
                'name'     => $product->name,
                'price'    => $product->price,
                'quantity' => $quantity,
                'subtotal' => $product->price * $quantity,
            ];
        }

        $reservationData = [
            'type'   => $reservationType,
            'detail' => $reservationDetail
        ];

        return view('customer.BayarReservasi', [
            'cartItems'          => $cartItems,
            'totalPrice'         => $totalPrice,
            'reservationDetails' => $reservationData
        ]);
    }


    /**
     * Memproses pembayaran setelah konfirmasi.
     * VERSI BARU: Mengirim ke Midtrans.
     */
    public function processPayment(Request $request)
    {
        // 1. VALIDASI PESANAN
        $validationResult = $this->validateOrder($request);
        if ($validationResult instanceof RedirectResponse) {
            return redirect()->back()
                ->withErrors($validationResult->getSession()->get('errors'))
                ->withInput();
        }

        // Unpack data pesanan
        $totalPrice = $validationResult['totalPrice'];
        $reservationType = $validationResult['reservationType'];
        $reservationFkId = $validationResult['reservationFkId'];
        $products = $validationResult['products'];
        $itemsFromRequest = $validationResult['items'];

        // 2. VALIDASI DATA CUSTOMER
        $customerData = $request->validate([
            'nama'          => 'required|string|max:255',
            'nomor_telepon' => 'required|string|regex:/^08[0-9]{8,12}$/',
            'jumlah_orang'  => 'required|integer|min:1',
            'tanggal'       => 'required|date|after_or_equal:today',
            'waktu'         => 'required|date_format:H:i',
        ], [
            'nomor_telepon.regex' => 'Format nomor telepon tidak valid (contoh: 08123456789).',
            'tanggal.after_or_equal' => 'Tanggal reservasi tidak boleh di masa lalu.',
        ]);

        // 3. BUAT ID TRANSAKSI UNIK
        $id_transaksi = 'HOMEY-' . now()->format('Ymd') . '-' . strtoupper(Str::random(6));

        // 4. SIMPAN RESERVASI (STATUS: PENDING)
        $dataToSave = [
            'id_transaksi'  => $id_transaksi,
            'nama'          => $customerData['nama'],
            'nomor_telepon' => $customerData['nomor_telepon'],
            'jumlah_orang'  => $customerData['jumlah_orang'],
            'tanggal'       => $customerData['tanggal'],
            'waktu'         => $customerData['waktu'],
            'status'        => 'pending', // <--- STATUS AWAL
            'nomor_meja'    => ($reservationType === 'meja') ? $reservationFkId : null,
            'nomor_ruangan' => ($reservationType === 'ruangan') ? $reservationFkId : null,
        ];

        // Simpan data reservasi
        $reservation = Reservation::create($dataToSave);
        // (Disarankan juga menyimpan detail item pesanan di tabel lain,
        // tapi untuk sekarang kita fokus di reservasi)
        
        // 5. KONFIGURASI MIDTRANS
        $this->setupMidtrans();

        // 6. SIAPKAN PARAMETER UNTUK MIDTRANS
        
        // Detail Item (dari keranjang)
        $item_details = [];
        foreach ($products as $product) {
            $quantity = $itemsFromRequest[$product->id];
            $item_details[] = [
                'id'       => $product->id,
                'price'    => $product->price,
                'quantity' => $quantity,
                'name'     => $product->name,
            ];
        }

        // Detail Customer
        $customer_details = [
            'first_name' => $customerData['nama'],
            'last_name'  => '', // Kosongkan jika hanya ada 1 field nama
            'phone'      => $customerData['nomor_telepon'],
        ];

        // Parameter Transaksi Utama
        $params = [
            'transaction_details' => [
                'order_id'     => $id_transaksi, // ID Transaksi UNIK Anda
                'gross_amount' => $totalPrice,   // Total Harga
            ],
            'item_details'        => $item_details,
            'customer_details'    => $customer_details,
            // (Opsional) URL Redirect jika tidak pakai Snap.js callback
            // 'callbacks' => [
            //     'finish' => route('payment.success') 
            // ]
        ];

        try {
            // 7. DAPATKAN SNAP TOKEN
            $snapToken = Snap::getSnapToken($params);

            // 8. KIRIM KE VIEW CHECKOUT
            return view('customer.checkout', [
                'snapToken' => $snapToken,
                'orderId'   => $id_transaksi // Kirim orderId untuk redirect JS
            ]);

        } catch (\Exception $e) {
            // Jika gagal membuat token, kembalikan dengan error
            return redirect()->back()->withErrors(['msg' => 'Gagal memulai pembayaran: ' . $e->getMessage()]);
        }
    }

    /**
     * Helper untuk setup config Midtrans.
     */
    private function setupMidtrans()
    {
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');
    }


    // ... (Fungsi validateOrder() TIDAK BERUBAH) ...
    private const MINIMUM_ORDER_FOR_TABLE = 50000;
    private function validateOrder(Request $request)
    {
        // ... (Semua kode di fungsi ini tetap sama seperti perbaikan terakhir)
        
        $itemsFromRequest = $request->input('items', []);
        $roomName = trim($request->input('reservation_room_name'));
        $tableNumber = trim($request->input('reservation_table_number'));

        if (empty($itemsFromRequest)) {
            return redirect('/pesanmenu')->withErrors(['msg' => 'Keranjang Anda kosong!']);
        }

        $productIds = array_keys($itemsFromRequest);
        $products = Product::findMany($productIds);
        $totalPrice = 0;
        foreach ($products as $product) {
            if ($product) {
                $totalPrice += $product->price * $itemsFromRequest[$product->id];
            }
        }

        $reservationType = null;
        $reservationDetail = null;
        $reservationFkId = null; // <-- FK ID

        if ($roomName) {
            $room = Room::where('nama_ruangan', $roomName)->first();
            if (!$room) {
                return redirect('/pesanmenu')->withErrors(['msg' => 'Ruangan yang dipilih tidak valid.']);
            }
            if ($totalPrice < $room->minimum_order) {
                return redirect('/pesanmenu')->withErrors(['msg' => 'Total belanja tidak memenuhi syarat minimal pemesanan untuk ' . $room->nama_ruangan]);
            }
            $reservationType = 'ruangan';
            $reservationDetail = $roomName;
            $reservationFkId = $room->id; // <-- Simpan ID
        } elseif ($tableNumber) {
            $meja = \App\Models\Meja::where('nomor_meja', $tableNumber)->where('status_aktif', true)->first();
            if (!$meja) {
                return redirect('/pilih-meja')->withErrors(['msg' => 'Meja yang dipilih tidak valid atau tidak tersedia.']);
            }
            if ($totalPrice < self::MINIMUM_ORDER_FOR_TABLE) {
                return redirect('/pesanmenu')->withErrors(['msg' => 'Total belanja tidak memenuhi syarat minimal pemesanan meja (Rp ' . number_format(self::MINIMUM_ORDER_FOR_TABLE) . ')']);
            }
            $reservationType = 'meja';
            $reservationDetail = $tableNumber;
            $reservationFkId = $meja->id; // <-- Simpan ID
        } else {
            return redirect('/pilih-reservasi')->withErrors(['msg' => 'Silakan pilih jenis reservasi terlebih dahulu.']);
        }

        return [
            'products'          => $products,
            'items'             => $itemsFromRequest,
            'totalPrice'        => $totalPrice,
            'reservationType'   => $reservationType,
            'reservationDetail' => $reservationDetail,
            'reservationFkId'   => $reservationFkId, // <-- Pastikan ini ada
        ];
    }
}