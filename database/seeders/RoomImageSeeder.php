<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage; // Pastikan ini di-import

class RoomImageSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Tentukan path sumber DENGAN BENAR
        $sourcePath = database_path('seeders/images/rooms'); // <-- Perbaikan di sini

        // 2. Cek apakah sumbernya ada
        if (!File::exists($sourcePath)) {
            $this->command->error('Source directory for rooms does not exist: ' . $sourcePath);
            $this.command->warn('Please create: mkdir -p database/seeders/images/rooms');
            return; // Hentikan seeder
        }

        // 3. Ambil semua file
        $files = File::allFiles($sourcePath);

        // 4. Gunakan Storage Facade untuk menyalin
        foreach ($files as $file) {
            $relativePath = $file->getRelativePathname();
            $destinationPath = 'images/rooms/' . $relativePath;

            // Gunakan Storage::disk('public')
            if (!Storage::disk('public')->exists($destinationPath)) {
                
                // 'put' akan otomatis membuat sub-direktori
                Storage::disk('public')->put(
                    $destinationPath,
                    $file->getContents(),
                    'public' // Set visibilitas
                );
            }
        }
        
        $this->command->info('Initial room images have been seeded.');
    }
}
