<?php

namespace App\Http\Controllers\Customer;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


class BayarController extends Controller
{
  public function show(Request $request)
    {
        // 1. Validasi request
        $request->validate([
            'items' => 'required|array'
        ]);

        $cartData = $request->input('items'); // Contoh: ['1' => 2, '10' => 1] (productId => quantity)

        $orderItems = [];
        $totalPrice = 0;

        // 2. Loop melalui data keranjang
        foreach ($cartData as $productId => $quantity) {
            // Ambil detail produk dari DATABASE untuk keamanan (jangan percaya harga dari client)
            $product = Product::find($productId);

            if ($product) {
                $orderItems[] = [
                    'name'      => $product->name,
                    'quantity'  => $quantity,
                    'price'     => $product->price,
                    'subtotal'  => $product->price * $quantity,
                ];
                $totalPrice += $product->price * $quantity;
            }
        }

        // 3. Kirim data yang sudah aman ke view Blade
        return view('customer.BayarReservasi', [
            'items' => $orderItems,
            'total' => $totalPrice
        ]);
    }
}
