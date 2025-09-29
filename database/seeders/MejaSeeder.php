<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Meja;

class MejaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Data meja sesuai dengan gambar yang diberikan
        $mejaDummy = [
            [
                'nomor_meja' => 1,
                'kapasitas' => 4,
                'lokasi' => 'Indoor 1',
                'status_aktif' => true,
            ],
            [
                'nomor_meja' => 2,
                'kapasitas' => 4,
                'lokasi' => 'Indoor 1',
                'status_aktif' => true,
            ],
            [
                'nomor_meja' => 3,
                'kapasitas' => 4,
                'lokasi' => 'Indoor 1',
                'status_aktif' => false,
            ],
            [
                'nomor_meja' => 4,
                'kapasitas' => 4,
                'lokasi' => 'Indoor 1',
                'status_aktif' => false,
            ],
            [
                'nomor_meja' => 5,
                'kapasitas' => 4,
                'lokasi' => 'Indoor 1',
                'status_aktif' => true,
            ],
            [
                'nomor_meja' => 6,
                'kapasitas' => 4,
                'lokasi' => 'Indoor 1',
                'status_aktif' => true,
            ],
            [
                'nomor_meja' => 7,
                'kapasitas' => 4,
                'lokasi' => 'Indoor 2',
                'status_aktif' => true,
            ],
        ];

        foreach ($mejaDummy as $meja) {
            Meja::create($meja);
        }
    }
}