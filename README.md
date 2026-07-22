# Smart Reservation System - Homey Cafe

Sistem reservasi cerdas yang dirancang khusus untuk memfasilitasi pemesanan dan manajemen operasional di Homey Cafe. Proyek ini dikembangkan secara kolaboratif untuk memberikan solusi digital bagi pemilik kafe dalam mengelola reservasi pelanggan dengan lebih efisien.

## Teknologi Utama

* **Framework Backend:** Laravel 12.25.0
* **Frontend:** Blade Templates dengan Tailwind CSS
* **Pengujian:** Pest Framework

## Standar Pengembangan Tim

Untuk menjaga konsistensi kode di seluruh basis kode saat melakukan push atau pull melalui GitHub, seluruh anggota tim harus mematuhi aturan penamaan berikut:
* **File Kelas PHP:** Wajib menggunakan format PascalCase. Contoh: `ReservationController.php`.
* **File View Blade:** Wajib menggunakan format huruf kecil atau snake_case. Contoh: `create_reservation.blade.php`.

## Panduan Instalasi

Ikuti langkah-langkah di bawah ini untuk menjalankan proyek di lingkungan pengembangan lokal Anda.

1. Kloning repositori ini ke mesin lokal Anda
2. Jalankan perintah `composer install` untuk mengunduh semua dependensi PHP
3. Salin file `.env.example` menjadi `.env`
4. Sesuaikan kredensial database pada file `.env`
5. Hasilkan kunci aplikasi dengan menjalankan `php artisan key:generate`
6. Jalankan migrasi database dengan perintah `php artisan migrate`
7. Mulai peladen pengembangan lokal dengan `php artisan serve`

## Pengujian

Proyek ini menggunakan Pest untuk pengujian otomatis. Pastikan kode baru tidak merusak fitur yang ada sebelum melakukan push ke repositori. Jalankan perintah berikut untuk memulai pengujian:

```bash
./vendor/bin/pest
