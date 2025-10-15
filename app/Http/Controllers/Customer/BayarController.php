<?php

namespace App\Http\Controllers\Customer;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


class BayarController extends Controller
{
  public function index(Request $request)
    {
        // Ambil array [id => quantity] dari form
        $itemsFromRequest = $request->input('items', []);

        // Jika keranjang kosong, kembalikan ke halaman sebelumnya atau halaman menu
        if (empty($itemsFromRequest)) {
            return redirect('/menu')->with('error', 'Keranjang Anda kosong!');
        }

        // 2. Ambil semua ID produk dari request
        $productIds = array_keys($itemsFromRequest);

        // 3. Ambil semua detail produk dari database dalam satu query
        $products = Product::findMany($productIds);

        $cartItems = [];
        $totalPrice = 0;

        // 4. Loop melalui produk yang ditemukan untuk membangun data keranjang
        foreach ($products as $product) {
            $quantity = $itemsFromRequest[$product->id];
            $subtotal = $product->price * $quantity;

            $cartItems[] = [
                'id'       => $product->id,
                'name'     => $product->name,
                'price'    => $product->price,
                'quantity' => $quantity,
                'subtotal' => $subtotal,
            ];

            $totalPrice += $subtotal;
        }


        // dd($cartItems);
        // dd($totalPrice);

        // 5. Hapus dd() dan kirim data yang sudah lengkap ke view
        return view('customer.BayarReservasi', [
            'cartItems'  => $cartItems,
            'totalPrice' => $totalPrice
        ]);
    }
}
