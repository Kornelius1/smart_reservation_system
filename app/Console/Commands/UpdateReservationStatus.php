<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Reservation; 
use Carbon\Carbon;
use Illuminate\Support\Facades\Log; 

class UpdateReservationStatus extends Command
{
    /**
     * Nama dan signature dari command.
     * (Sekarang ini adalah satu-satunya command yang Anda perlukan)
     */
    protected $signature = 'reservations:update-status';
    protected $description = 'Memperbarui SEMUA status reservasi otomatis (Kedaluwarsa, Selesai, No-Show, Check-in).';

    /**
     * Jalankan logic command.
     */
    public function handle()
    {
        Log::info('======= MENJALANKAN UPDATE STATUS RESERVASI =======');
        
        $now = Carbon::now(); 
        $todayDateString = $now->toDateString(); 
        $totalChanged = 0;

        Log::info('Waktu server: ' . $now->toDateTimeString() . ' | Tanggal hari ini: ' . $todayDateString);

        // =================================================================
        // [LOGIKA BARU DIGABUNGKAN]
        // Skenario: 'pending' -> 'kedaluwarsa' (Diambil dari CancelExpiredReservations)
        // -----------------------------------------------------------------
        // Cari reservasi 'pending' yang 'expired_at'-nya (dari DOKU) sudah lewat.
        $expired = Reservation::where('status', 'pending') // <-- Menggunakan 'pending' (huruf kecil)
                                ->whereNotNull('expired_at') // Pastikan 'expired_at' ada
                                ->where('expired_at', '<=', $now)
                                ->update(['status' => 'kedaluwarsa']);

        if ($expired > 0) {
            $this->info($expired . ' reservasi "pending" diubah menjadi "kedaluwarsa".');
            Log::info($expired . ' reservasi "pending" diubah menjadi "kedaluwarsa".');
            $totalChanged += $expired;
        }

        // =================================================================
        // LOGIKA ANDA YANG SUDAH ADA (TIDAK BERUBAH)
        // =================================================================

        // Skenario: 'check-in' -> 'selesai'
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

        if ($totalChanged == 0) {
            $this->info('Tidak ada status reservasi yang perlu diperbarui.');
            // (Kita tidak perlu log ini)
        }
    }
}