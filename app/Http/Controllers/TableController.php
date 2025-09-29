<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class TableController extends Controller
{
    /**
     * Menampilkan halaman manajemen meja.
     */
    public function index(): View
    {
        // Data statis untuk meja, sesuai dengan gambar
        $tables = [
            ['id' => 1, 'nomor_meja' => '1', 'kapasitas' => 4, 'lokasi' => 'Indoor 1', 'tersedia' => true],
            ['id' => 2, 'nomor_meja' => '2', 'kapasitas' => 4, 'lokasi' => 'Indoor 1', 'tersedia' => true],
            ['id' => 3, 'nomor_meja' => '3', 'kapasitas' => 4, 'lokasi' => 'Indoor 1', 'tersedia' => false],
            ['id' => 4, 'nomor_meja' => '4', 'kapasitas' => 2, 'lokasi' => 'Indoor 2', 'tersedia' => true],
            ['id' => 5, 'nomor_meja' => '5', 'kapasitas' => 6, 'lokasi' => 'Outdoor', 'tersedia' => true],
            ['id' => 6, 'nomor_meja' => '6', 'kapasitas' => 6, 'lokasi' => 'Outdoor', 'tersedia' => false],
        ];

        return view('manajemen-meja', ['tables' => $tables]);
    }
}