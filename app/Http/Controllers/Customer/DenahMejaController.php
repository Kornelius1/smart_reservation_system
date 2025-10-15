<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;

class DenahMejaController extends Controller
{
    public function index()
    {
        
        // Data meja bisa diganti nanti dengan query ke database
        $tables = [
            ['id' => 1, 'name' => 'Meja A1', 'capacity' => 4, 'available' => true,  'location' => 'Indoor 1'],
            ['id' => 2, 'name' => 'Meja A2', 'capacity' => 4, 'available' => false, 'location' => 'Indoor 1'],
            ['id' => 3, 'name' => 'Meja A3', 'capacity' => 2, 'available' => true,  'location' => 'Indoor 1'],
            ['id' => 4, 'name' => 'Meja A4', 'capacity' => 6, 'available' => true,  'location' => 'Indoor 1'],

            ['id' => 5, 'name' => 'Meja B1', 'capacity' => 4, 'available' => true,  'location' => 'Indoor 2'],
            ['id' => 6, 'name' => 'Meja B2', 'capacity' => 4, 'available' => false, 'location' => 'Indoor 2'],
            ['id' => 7, 'name' => 'Meja B3', 'capacity' => 2, 'available' => true,  'location' => 'Indoor 2'],
            ['id' => 8, 'name' => 'Meja B4', 'capacity' => 6, 'available' => true,  'location' => 'Indoor 2'],

            ['id' => 9,  'name' => 'Meja C1', 'capacity' => 4, 'available' => true,  'location' => 'Outdoor 1'],
            ['id' => 10, 'name' => 'Meja C2', 'capacity' => 4, 'available' => false, 'location' => 'Outdoor 1'],
            ['id' => 11, 'name' => 'Meja C3', 'capacity' => 2, 'available' => true,  'location' => 'Outdoor 1'],

            ['id' => 12, 'name' => 'Meja D1', 'capacity' => 6, 'available' => true,  'location' => 'Outdoor 2'],
            ['id' => 13, 'name' => 'Meja D2', 'capacity' => 4, 'available' => false, 'location' => 'Outdoor 2'],
            ['id' => 14, 'name' => 'Meja D3', 'capacity' => 4, 'available' => true,  'location' => 'Outdoor 2'],
        ];

        // Kirim ke view
        return view('customer.DenahMeja', compact('tables'));
    }
}
