<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        // $this->call([
        //     MejaSeeder::class,
        // ]);

       $this->call([
            ReservationSeeder::class,
            
        ]);

         $this->call([
            ProductSeeder::class,
          
        ]);

         $this->call([
            MejaSeeder::class,
           
        ]);

        
    }
}