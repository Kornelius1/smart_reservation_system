<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Kosongkan tabel users
        DB::table('users')->delete();

        // Buat 1 user Admin
        User::create([
            'name' => 'Admin',
            'email' => 'admin@homey.com',
            'password' => Hash::make('password'), // Password-nya: "password"
            'email_verified_at' => now(),
            // Tambahkan field lain jika perlu (misal: 'role' => 'admin')
        ]);

        // (Opsional) Buat 10 user dummy
        // User::factory(10)->create();
    }
}
