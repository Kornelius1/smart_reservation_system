<?php

namespace App\Http\Controllers\Customer;

use App\Models\Product;
use App\Models\Room;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse; // Impor untuk type-hinting

class BayarController extends Controller
{
    /**
     * Menampilkan halaman konfirmasi SETELAH validasi awal.
     */
    public function show(Request $request)
    {

        // dd($request->all());
        // Panggil metode validasi. Hasilnya bisa berupa redirect atau data yang valid.
        $validationResult = $this->validateOrder($request);

        // Jika hasilnya adalah sebuah RedirectResponse, berarti validasi gagal.
        if ($validationResult instanceof RedirectResponse) {
            return $validationResult;
        }

        // Jika validasi lolos, kita akan mendapatkan data yang sudah dihitung.
        $products = $validationResult['products'];
        $itemsFromRequest = $validationResult['items'];
        $totalPrice = $validationResult['totalPrice'];
        $roomName = $validationResult['roomName'];

        // Siapkan data untuk view
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

        return view('customer.BayarReservasi', [
            'cartItems'          => $cartItems,
            'totalPrice'         => $totalPrice,
            'reservationDetails' => ['room_name' => $roomName]
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
    private function validateOrder(Request $request)
    {
        $itemsFromRequest = $request->input('items', []);
        $roomName = $request->input('reservation_room_name');

        if (empty($itemsFromRequest)) {
            return redirect('/pesanmenu')->withErrors(['msg' => 'Keranjang Anda kosong!']);
        }

        $productIds = array_keys($itemsFromRequest);
        $products = Product::findMany($productIds);
        $totalPrice = 0;
        foreach ($products as $product) {
            // Pastikan produk ada sebelum mengaksesnya
            if ($product) {
                $totalPrice += $product->price * $itemsFromRequest[$product->id];
            }
        }

        if (!$roomName) {
            return redirect('/pilih-reservasi')->withErrors(['msg' => 'Silakan pilih jenis reservasi terlebih dahulu.']);
        }

        $room = Room::where('name', $roomName)->first();

      

        if (!$room) {
            return redirect('/pesanmenu')->withErrors(['msg' => 'Ruangan yang dipilih tidak valid.']);
        }

        if ($totalPrice < $room->minimum_order) {
            return redirect('/pesanmenu')->withErrors(['msg' => 'Total belanja tidak memenuhi syarat minimal pemesanan untuk ' . $room->name]);
        }

        // Jika semua validasi lolos, kembalikan data yang sudah dihitung
        return [
            'products'   => $products,
            'items'      => $itemsFromRequest,
            'totalPrice' => $totalPrice,
            'roomName'   => $roomName,
        ];
    }
}