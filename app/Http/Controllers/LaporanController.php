<?php
// app/Http/Controllers/LaporanController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi;
use Carbon\Carbon;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $query = Transaksi::query();

        // Filter berdasarkan tanggal
        if ($request->has('start_date') && $request->start_date) {
            $query->whereDate('tanggal', '>=', $request->start_date);
        }

        if ($request->has('end_date') && $request->end_date) {
            $query->whereDate('tanggal', '<=', $request->end_date);
        }

        // Urutkan berdasarkan tanggal terbaru
        $query->orderBy('tanggal', 'desc');

        // Pagination
        $transaksi = $query->paginate(10);

        return view('laporan.index', compact('transaksi'));
    }

    public function export(Request $request)
    {
        // Implementasi export ke Excel/PDF
        // Bisa menggunakan package seperti maatwebsite/excel atau barryvdh/laravel-dompdf
    }
}