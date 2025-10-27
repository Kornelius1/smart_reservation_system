<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use App\Console\Commands\UpdateReservationStatus;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// 2. Tambahkan penjadwal Anda di sini
Schedule::command(UpdateReservationStatus::class)->everyMinute();

// Alternatifnya, Anda bisa menggunakan string
// Schedule::command('reservations:update-status')->everyMinute(); 
// (Nama 'reservations:update-status' berasal dari $signature di file Perintah Anda)
