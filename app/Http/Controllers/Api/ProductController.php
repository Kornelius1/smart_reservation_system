<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        // Ambil semua produk, urutkan berdasarkan kategori
        $products = Product::orderBy('category')->get();

        // Kembalikan sebagai response JSON
        return response()->json($products);
    }
}