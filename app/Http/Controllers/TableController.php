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
        // Data statis untuk 24 meja
        $tables = [
            // Indoor 1 (Meja 1-6)
            ['id' => 1, 'nomor_meja' => '1', 'kapasitas' => 4, 'lokasi' => 'Indoor 1', 'tersedia' => true],
            ['id' => 2, 'nomor_meja' => '2', 'kapasitas' => 2, 'lokasi' => 'Indoor 1', 'tersedia' => false],
            ['id' => 3, 'nomor_meja' => '3', 'kapasitas' => 4, 'lokasi' => 'Indoor 1', 'tersedia' => true],
            ['id' => 4, 'nomor_meja' => '4', 'kapasitas' => 6, 'lokasi' => 'Indoor 1', 'tersedia' => false],
            ['id' => 5, 'nomor_meja' => '5', 'kapasitas' => 4, 'lokasi' => 'Indoor 1', 'tersedia' => true],
            ['id' => 6, 'nomor_meja' => '6', 'kapasitas' => 2, 'lokasi' => 'Indoor 1', 'tersedia' => true],

            // Indoor 2 (Meja 7-12)
            ['id' => 7, 'nomor_meja' => '7', 'kapasitas' => 4, 'lokasi' => 'Indoor 2', 'tersedia' => true],
            ['id' => 8, 'nomor_meja' => '8', 'kapasitas' => 6, 'lokasi' => 'Indoor 2', 'tersedia' => false],
            ['id' => 9, 'nomor_meja' => '9', 'kapasitas' => 2, 'lokasi' => 'Indoor 2', 'tersedia' => true],
            ['id' => 10, 'nomor_meja' => '10', 'kapasitas' => 4, 'lokasi' => 'Indoor 2', 'tersedia' => true],
            ['id' => 11, 'nomor_meja' => '11', 'kapasitas' => 4, 'lokasi' => 'Indoor 2', 'tersedia' => false],
            ['id' => 12, 'nomor_meja' => '12', 'kapasitas' => 6, 'lokasi' => 'Indoor 2', 'tersedia' => true],

            // Outdoor 1 (Meja 13-18)
            ['id' => 13, 'nomor_meja' => '13', 'kapasitas' => 6, 'lokasi' => 'Outdoor 1', 'tersedia' => false],
            ['id' => 14, 'nomor_meja' => '14', 'kapasitas' => 4, 'lokasi' => 'Outdoor 1', 'tersedia' => true],
            ['id' => 15, 'nomor_meja' => '15', 'kapasitas' => 2, 'lokasi' => 'Outdoor 1', 'tersedia' => true],
            ['id' => 16, 'nomor_meja' => '16', 'kapasitas' => 4, 'lokasi' => 'Outdoor 1', 'tersedia' => false],
            ['id' => 17, 'nomor_meja' => '17', 'kapasitas' => 6, 'lokasi' => 'Outdoor 1', 'tersedia' => true],
            ['id' => 18, 'nomor_meja' => '18', 'kapasitas' => 4, 'lokasi' => 'Outdoor 1', 'tersedia' => false],

            // Outdoor 2 (Meja 19-24)
            ['id' => 19, 'nomor_meja' => '19', 'kapasitas' => 2, 'lokasi' => 'Outdoor 2', 'tersedia' => true],
            ['id' => 20, 'nomor_meja' => '20', 'kapasitas' => 4, 'lokasi' => 'Outdoor 2', 'tersedia' => false],
            ['id' => 21, 'nomor_meja' => '21', 'kapasitas' => 6, 'lokasi' => 'Outdoor 2', 'tersedia' => true],
            ['id' => 22, 'nomor_meja' => '22', 'kapasitas' => 4, 'lokasi' => 'Outdoor 2', 'tersedia' => true],
            ['id' => 23, 'nomor_meja' => '23', 'kapasitas' => 2, 'lokasi' => 'Outdoor 2', 'tersedia' => false],
            ['id' => 24, 'nomor_meja' => '24', 'kapasitas' => 6, 'lokasi' => 'Outdoor 2', 'tersedia' => true],
        ];

        return view('admin.manajemen-meja', ['tables' => $tables]);
    }
}