<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Meja; 
use Illuminate\Support\Facades\DB; 

class MejaSeeder extends Seeder
{
 
    public function run(): void
    {
       
        DB::table('meja')->delete();
        

       
        $locations = ['indoor1', 'indoor2', 'out1', 'out2'];

        $mejaCounter = 1;

     
        foreach ($locations as $location) {
         
            for ($i = 1; $i <= 6; $i++) { 
                Meja::create([
                    'nomor_meja'   => $mejaCounter, 
                    'kapasitas'    => 4,
                    'lokasi'       => $location,
                    'status_aktif' => true, 
                ]);

             
                $mejaCounter++; 
            }
        }
     
    }
}