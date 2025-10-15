<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PemesananMenuController extends Controller
{
    public function index()
    {
        // return view dari resources/views/PemesananMenu.blade.php
        return view('customer.PemesananMenu');
    }
}
