<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Halaman ini hanya bisa diakses setelah admin login
        return view('admin.dashboard');
    }
}