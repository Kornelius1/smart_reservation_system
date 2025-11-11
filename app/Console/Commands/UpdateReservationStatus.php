<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Reservation; // Model Anda yang benar
use Carbon\Carbon;
use Illuminate\Support\Facades\Log; // Kita tetap pakai Log

class UpdateReservationStatus extends Command
{
    protected $signature = 'reservations:update-status';
    protected $description = 'Memperbarui status reservasi otomatis berdasarkan waktu.';

    public function handle()
    {
        Log::info('======= MENJALANKAN UPDATE STATUS RESERVASI =======');
        
        $now = Carbon::now(); 
        $todayDateString = $now->toDateString(); 
        $totalChanged = 0;

        Log::info('Waktu server: ' . $now->toDateTimeString() . ' | Tanggal hari ini: ' . $todayDateString);

        // =================================================================
        // KITA LANGSUNG UPDATE, TANPA .get()
        // =================================================================

        // Skenario: 'check-in' -> 'selesai'
        // $completed akan berisi jumlah baris yang di-update (cth: 0, 1, 5)
        $completed = Reservation::whereDate('tanggal', '<', $todayDateString)
                              ->where('status', 'check-in')
                              ->update(['status' => 'selesai']);

        if ($completed > 0) {
            $this->info($completed . ' reservasi "check-in" diubah menjadi "selesai".');
            Log::info($completed . ' reservasi "check-in" diubah menjadi "selesai".');
            $totalChanged += $completed;
        }

        // Skenario: 'akan datang' -> 'dibatalkan' (No-Show)
        $noShow = Reservation::whereDate('tanggal', '<', $todayDateString)
                           ->where('status', 'akan datang')
                           ->update(['status' => 'dibatalkan']);
        
        if ($noShow > 0) {
            $this->info($noShow . ' reservasi "akan datang" diubah menjadi "dibatalkan" (No-Show).');
            Log::info($noShow . ' reservasi "akan datang" diubah menjadi "dibatalkan" (No-Show).');
            $totalChanged += $noShow;
        }

        // Skenario: 'akan datang' -> 'check-in' (Otomatis Check-in HARI INI)
        $ongoing = Reservation::whereDate('tanggal', '=', $todayDateString)
                            ->whereTime('waktu', '<=', $now->toTimeString())
                            ->where('status', 'akan datang')
                            ->update(['status' => 'check-in']);

        if ($ongoing > 0) {
            $this->info($ongoing . ' reservasi "akan datang" diubah menjadi "check-in" (Otomatis).');
            Log::info($ongoing . ' reservasi "akan datang" diubah menjadi "check-in" (Otomatis).');
            $totalChanged += $ongoing;
        }

        // =================================================================

        // if ($totalChanged == 0) {
        //     $this->info('Tidak ada status reservasi yang perlu diperbarui.');
        //     Log::info('Tidak ada status reservasi yang perlu diperbarui.');
        // }

        // Log::info('======= SELESAI UPDATE STATUS RESERVASI =======');
    }
}