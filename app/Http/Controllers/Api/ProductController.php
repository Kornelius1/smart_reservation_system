<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        
        $products = Product::where('tersedia', true)->orderBy('category')->get();
        // Kembalikan sebagai response JSON
        return response()->json($products);
    }
}