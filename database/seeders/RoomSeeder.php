<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Room;

class RoomSeeder extends Seeder
{
    public function run()
    {
        Room::create([
            'name' => 'Indoor 1 - Cafe Area',
            'capacity' => 20,
            'min_order' => 200000,
            'extra_time_price' => 50000,
            'image' => 'images/rooms/cafe_area.jpg',
            'description' => 'Cozy cafe area, cocok untuk meeting dan hangout.'
        ]);

        Room::create([
            'name' => 'Indoor 2 - Private Hall',
            'capacity' => 35,
            'min_order' => 400000,
            'extra_time_price' => 50000,
            'image' => 'images/rooms/private_hall.jpg',
            'description' => 'Ruang pribadi dengan meja besar.'
        ]);
    }
}
