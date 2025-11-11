<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Room; // Impor model Room

class RoomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Room::create([
            'nama_ruangan' => 'Ruang Meeting A',
            'kapasitas' => 10,
            'minimum_order' => 200000,
            'lokasi' => 'Lantai 2, Indoor',
            'fasilitas' => 'AC, Proyektor, Papan Tulis, Wi-Fi',
            'keterangan' => 'Ruangan privat yang tenang, cocok untuk meeting tim atau presentasi.',
            'status' => 'tersedia',
            'image_url' => 'images/rooms/indoor1.png',
        ]);

        Room::create([
            'nama_ruangan' => 'Area VIP Balkon',
            'kapasitas' => 20,
            'minimum_order' => 10,
            'lokasi' => 'Lantai 2, Outdoor',
            'fasilitas' => 'Sofa, Pemandangan Kota, Wi-Fi, Smoking Area',
            'keterangan' => 'Area semi-outdoor eksklusif dengan pemandangan kota. Ideal untuk acara santai.',
            'status' => 'tersedia',
            'image_url' => 'images/rooms/indoor2.png',
        ]);

        Room::create([
            'nama_ruangan' => 'Ruang Serbaguna B',
            'kapasitas' => 30,
            'minimum_order' => 300000,
            'lokasi' => 'Lantai 1, Indoor',
            'fasilitas' => 'AC, Sound System, Meja Panjang, Wi-Fi',
            'keterangan' => 'Ruangan luas yang dapat diatur untuk berbagai acara, seperti workshop atau arisan.',
            'status' => 'tersedia',
            'image_url' => 'images/rooms/indoor1.png',
        ]);
    }
}
