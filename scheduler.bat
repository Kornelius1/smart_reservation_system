@echo off

:: Pindah ke direktori proyek Anda
:: (Gunakan "D:" untuk pindah drive, lalu 'cd' untuk pindah folder)
D:
cd "D:\PPSI Project\smart_reservation_system"

:: Jalankan perintah artisan
:: (Pastikan 'php' ada di system PATH Anda. 
:: Jika tidak, ganti 'php' dengan path lengkap, cth: "C:\xampp\php\php.exe")
php artisan schedule:run