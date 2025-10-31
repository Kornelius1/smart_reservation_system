<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class ProductImageSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Tentukan path sumber dan tujuan
        $sourcePath = storage_path('app/seed-images');
        $destinationPath = storage_path('app/public/images/menus'); 

        // 2. Buat direktori tujuan jika belum ada
        if (!File::exists($destinationPath)) {
            File::makeDirectory($destinationPath, 0755, true);
        }

        // 3. Salin semua file dari sumber ke tujuan
        $files = File::allFiles($sourcePath);

        foreach ($files as $file) {
            $destinationFile = $destinationPath . '/' . $file->getRelativePathname();
            
            // Buat sub-direktori jika perlu
            if (!File::exists(dirname($destinationFile))) {
                File::makeDirectory(dirname($destinationFile), 0755, true);
            }

            // Salin file hanya jika belum ada di tujuan
            if (!File::exists($destinationFile)) {
                File::copy($file->getPathname(), $destinationFile);
            }
        }
        
        $this->command->info('Initial product images have been seeded.');
    }
}