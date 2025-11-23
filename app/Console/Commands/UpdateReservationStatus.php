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
     */
    protected $signature = 'reservations:update-status';
    protected $description = 'Memperbarui SEMUA status reservasi otomatis (Kedaluwarsa, Selesai, No-Show, Check-in).';

    /**
     * Jalankan logic command.
     */
    public function handle()
    {
        // 1. WAKTU LOKAL (INDONESIA / WIB)
        // Kita set eksplisit ke 'Asia/Jakarta' (WIB).
        // Ini memastikan logika Check-in & No-Show mengikuti jam Indonesia,
        // tidak peduli lokasi fisik server Anda ada di mana.
        $nowLocal = Carbon::now('Asia/Jakarta'); 
        $todayDateString = $nowLocal->toDateString();
        
        // 2. WAKTU UTC (KHUSUS DOKU)
        // Doku / Payment Gateway biasanya menyimpan expired_at dalam UTC.
        // Kita butuh pembanding yang setara (Apple-to-Apple).
        $nowUtc = Carbon::now('UTC');

        $totalChanged = 0;

        Log::info('======= RUNNING RESERVATION SCHEDULER (TARGET: INDO) =======');
        Log::info("Local Time (WIB): " . $nowLocal->toDateTimeString());
        Log::info("UTC Time (Doku):  " . $nowUtc->toDateTimeString());

        // =================================================================
        // 1. UPDATE STATUS: PENDING -> KEDALUWARSA (LOGIKA UTC)
        // =================================================================
        
        // Grace period 10 menit untuk delay callback payment gateway
        $gracePeriodMinutes = 10;
        
        // Gunakan Waktu UTC untuk menghitung batas waktu
        $expiryCutoff = $nowUtc->copy()->subMinutes($gracePeriodMinutes);

        // Debug log (optional, bisa dihapus kalau log terlalu penuh)
        // Log::info("Checking expired. Cutoff UTC: {$expiryCutoff->toDateTimeString()}");

        $expiredQuery = Reservation::where('status', 'pending')
            ->whereNotNull('expired_at')
            // Bandingkan expired_at (UTC) dengan Waktu UTC sekarang
            ->where('expired_at', '<=', $expiryCutoff); 

        $countExpired = $expiredQuery->count();

        if ($countExpired > 0) {
            $sample = $expiredQuery->first();
            Log::warning("EXPIRING {$countExpired} RESERVATIONS. Sample ID: {$sample->id}, Expired At (UTC): {$sample->expired_at}");
            
            $expiredQuery->update(['status' => 'kedaluwarsa']);
            
            $this->info($countExpired . ' reservasi "pending" diubah menjadi "kedaluwarsa".');
            $totalChanged += $countExpired;
        }

        // =================================================================
        // 2. UPDATE STATUS: CHECK-IN -> SELESAI (LOGIKA LOKAL WIB)
        // =================================================================
        // Logika: Tanggal reservasi < Hari ini (WIB)

        $completed = Reservation::whereDate('tanggal', '<', $todayDateString)
            ->where('status', 'check-in')
            ->update(['status' => 'selesai']);

        if ($completed > 0) {
            $this->info($completed . ' reservasi "check-in" diubah menjadi "selesai".');
            Log::info($completed . ' reservasi "check-in" diubah menjadi "selesai".');
            $totalChanged += $completed;
        }

        // =================================================================
        // 3. UPDATE STATUS: AKAN DATANG -> DIBATALKAN (LOGIKA LOKAL WIB)
        // =================================================================
        // Logika: Tanggal reservasi < Hari ini (WIB), tapi orangnya tidak datang

        $noShow = Reservation::whereDate('tanggal', '<', $todayDateString)
            ->where('status', 'akan datang')
            ->update(['status' => 'dibatalkan']);
        
        if ($noShow > 0) {
            $this->info($noShow . ' reservasi "akan datang" diubah menjadi "dibatalkan" (No-Show).');
            Log::info($noShow . ' reservasi "akan datang" diubah menjadi "dibatalkan" (No-Show).');
            $totalChanged += $noShow;
        }

        // =================================================================
        // 4. UPDATE STATUS: AKAN DATANG -> CHECK-IN (LOGIKA LOKAL WIB)
        // =================================================================
        // Logika: Tanggal = Hari ini, DAN Jam (WIB) <= Jam Sekarang (WIB)
        
        $ongoing = Reservation::whereDate('tanggal', '=', $todayDateString)
            ->whereTime('waktu', '<=', $nowLocal->toTimeString())
            ->where('status', 'akan datang')
            ->update(['status' => 'check-in']);

        if ($ongoing > 0) {
            $this->info($ongoing . ' reservasi "akan datang" diubah menjadi "check-in" (Otomatis).');
            Log::info($ongoing . ' reservasi "akan datang" diubah menjadi "check-in" (Otomatis).');
            $totalChanged += $ongoing;
        }

        if ($totalChanged == 0) {
            $this->info('Tidak ada status reservasi yang perlu diperbarui.');
        }
    }
}