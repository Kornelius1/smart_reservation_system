<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File; // Kita masih butuh ini untuk membaca sumber
use Illuminate\Support\Facades\Storage; // Tambahkan ini

class ProductImageSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Tentukan path sumber
        //    (Menggunakan database_path() lebih umum untuk aset seeder,
        //     tapi storage_path() juga tidak masalah jika itu preferensi Anda)
        $sourcePath = database_path('seeders/images/menus');
        
        // Pastikan sumbernya ada
        if (!File::exists($sourcePath)) {
             $this.command->error('Source directory does not exist: ' . $sourcePath);
             return; // Hentikan jika tidak ada sumber
        }

        // 2. Dapatkan semua file dari sumber
        $files = File::allFiles($sourcePath);

        // 3. Salin file ke disk 'public'
        foreach ($files as $file) {
            // Dapatkan path relatif (mis: 'kategori/minuman.jpg')
            $relativePath = $file->getRelativePathname();
            
            // Tentukan path tujuan di dalam disk 'public'
            $destinationPath = 'images/menus/' . $relativePath;

            // Cek apakah file sudah ada di disk 'public'
            if (!Storage::disk('public')->exists($destinationPath)) {
                
                // Salin file menggunakan 'put'
                // 'put' akan otomatis membuat sub-direktori jika perlu
                Storage::disk('public')->put(
                    $destinationPath,
                    $file->getContents(), // Ambil isi file
                    'public'             // Set visibilitas agar bisa diakses
                );
            }
        }
        
        $this->command->info('Initial product images have been seeded.');
    }
}
