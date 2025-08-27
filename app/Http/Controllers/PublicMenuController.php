<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PublicMenuController extends Controller
{
    public function index()
    {
        // Logika untuk menampilkan halaman utama/selamat datang
        return view('welcome'); // Menggunakan view bawaan welcome.blade.php
    }

    public function showMenu()
    {
        // Logika untuk mengambil data menu dari database dan menampilkannya
        return view('public.menu'); // Tampilkan di view public/menu.blade.php
    }
}