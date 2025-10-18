<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Room; // Impor model Room

class RoomSeeder extends Seeder
{
    public function run(): void
    {
        Room::create([
            'name' => 'Ruangan 1',
            'minimum_order' => 200000
        ]);

        Room::create([
            'name' => 'Ruangan 2',
            'minimum_order' => 400000
        ]);

        
    }
}