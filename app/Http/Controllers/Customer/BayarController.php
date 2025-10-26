<?php
namespace App\Http\Controllers\Customer;


use App\Models\Product;
use App\Models\Room;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse; 

class BayarController extends Controller
{
    
    public function show(Request $request)
{
    $validationResult = $this->validateOrder($request);

    if ($validationResult instanceof RedirectResponse) {
        return $validationResult;
    }

    // --- Unpack data yang baru ---
    $products = $validationResult['products'];
    $itemsFromRequest = $validationResult['items'];
    $totalPrice = $validationResult['totalPrice'];
    $reservationType = $validationResult['reservationType'];
    $reservationDetail = $validationResult['reservationDetail'];

    // Siapkan data untuk view
    $cartItems = [];
    foreach ($products as $product) {
        $quantity = $itemsFromRequest[$product->id];
        $cartItems[] = [
            'id'     => $product->id,
            'name'   => $product->name,
            'price'  => $product->price,
            'quantity' => $quantity,
            'subtotal' => $product->price * $quantity,
        ];
    }

    // --- Siapkan detail reservasi secara dinamis ---
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
     */
    public function processPayment(Request $request)
    {
        // Panggil metode validasi yang SAMA.
        $validationResult = $this->validateOrder($request);

        // Jika validasi gagal, kembalikan redirect tersebut.
        if ($validationResult instanceof RedirectResponse) {
            // Kita arahkan kembali (back) karena pengguna sudah di halaman konfirmasi.
            return redirect()->back()->withErrors($validationResult->getSession()->get('errors'));
        }

        // JIKA VALIDASI KEDUA LOLOS...
        // Di sinilah Anda menempatkan logika payment gateway, dll.

        return redirect('/sukses')->with('success_message', 'Pembayaran Anda berhasil diproses!');
    }

    /**
     * 1. METODE PRIVAT BARU UNTUK VALIDASI
     * Metode ini berisi semua logika yang berulang.
     * Ia akan mengembalikan RedirectResponse jika gagal, atau array data jika berhasil.
     */
    private const MINIMUM_ORDER_FOR_TABLE = 50000;
private function validateOrder(Request $request)
{
    // Mengambil data dari request
    // ASUMSI: Input 'reservation_room_name' berisi 'nama_ruangan' (misal: "Ruang Meeting A")
    $itemsFromRequest = $request->input('items', []);
    $roomName = trim($request->input('reservation_room_name'));
    $tableNumber = trim($request->input('reservation_table_number'));

    if (empty($itemsFromRequest)) {
        return redirect('/pesanmenu')->withErrors(['msg' => 'Keranjang Anda kosong!']);
    }

    // Kalkulasi total harga produk
    $productIds = array_keys($itemsFromRequest);
    $products = Product::findMany($productIds);
    $totalPrice = 0;
    foreach ($products as $product) {
        if ($product) {
            $totalPrice += $product->price * $itemsFromRequest[$product->id];
        }
    }

    // --- Logika validasi reservasi ---
    $reservationType = null;
    $reservationDetail = null;

    if ($roomName) {
        // --- Logika untuk validasi RUANGAN ---
        
        // UBAH DI SINI: Mencari berdasarkan 'nama_ruangan' (sesuai Model/Migrasi)
        $room = Room::where('nama_ruangan', $roomName)->first();
        
        if (!$room) {
            return redirect('/pesanmenu')->withErrors(['msg' => 'Ruangan yang dipilih tidak valid.']);
        }
        
        if ($totalPrice < $room->minimum_order) {
            // UBAH DI SINI: Menampilkan 'nama_ruangan' di pesan error
            return redirect('/pesanmenu')->withErrors(['msg' => 'Total belanja tidak memenuhi syarat minimal pemesanan untuk ' . $room->nama_ruangan]);
        }
        
        $reservationType = 'ruangan';
        $reservationDetail = $roomName; // Tetap pakai $roomName (dari input) untuk diteruskan ke view

    } elseif ($tableNumber) {
        // --- Logika untuk validasi MEJA ---
        // (Kode ini diasumsikan sudah benar sesuai model 'Meja' Anda)
        $meja = \App\Models\Meja::where('nomor_meja', $tableNumber)->where('status_aktif', true)->first();
        if (!$meja) {
            return redirect('/pilih-meja')->withErrors(['msg' => 'Meja yang dipilih tidak valid atau tidak tersedia.']);
        }
        if ($totalPrice < self::MINIMUM_ORDER_FOR_TABLE) {
            return redirect('/pesanmenu')->withErrors(['msg' => 'Total belanja tidak memenuhi syarat minimal pemesanan meja (Rp ' . number_format(self::MINIMUM_ORDER_FOR_TABLE) . ')']);
        }
        $reservationType = 'meja';
        $reservationDetail = $tableNumber;

    } else {
        // Jika tidak ada reservasi sama sekali
        return redirect('/pilih-reservasi')->withErrors(['msg' => 'Silakan pilih jenis reservasi terlebih dahulu.']);
    }

    // --- Kembalikan data yang lebih generik ---
    return [
        'products'          => $products,
        'items'             => $itemsFromRequest,
        'totalPrice'        => $totalPrice,
        'reservationType'   => $reservationType,
        'reservationDetail' => $reservationDetail,
    ];
}
}
