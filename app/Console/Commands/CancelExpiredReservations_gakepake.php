<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Reservation; 
use Illuminate\Support\Facades\Log; 

class CancelExpiredReservations extends Command
{
    /**
     * Nama dan signature dari command.
     */
    protected $signature = 'reservations:cancel-expired';

    /**
     * Deskripsi command.
     */
    protected $description = 'Mencari dan membatalkan reservasi pending yang sudah kedaluwarsa berdasarkan waktu kedaluwarsa DOKU.';

    /**
     * Jalankan logic command.
     */
    public function handle()
    {
        // Ambil waktu saat ini (Ini adalah waktu yang akan dibandingkan)
        $currentTime = now();

        // 1. Cari semua reservasi yang 'pending' (belum dibayar)
        //    DAN memiliki waktu kedaluwarsa ('expired_at') yang sudah terlampaui.
        $expiredReservations = Reservation::where('status', 'pending')
                                         // Kueri: cari expired_at yang <= dari waktu saat ini
                                         ->where('expired_at', '<=', $currentTime)
                                         ->get();
        
        $count = 0;
        if ($expiredReservations->count() > 0) {
            
            // 2. Ubah statusnya menjadi 'kedaluwarsa'
            foreach ($expiredReservations as $reservation) {
                // Hanya update jika expired_at tidak null (memastikan data Doku ada)
                if ($reservation->expired_at) { 
                    $reservation->update(['status' => 'kedaluwarsa']);
                    $count++;
                    Log::info('Reservasi Kedaluwarsa Otomatis (DOKU Expired): ' . $reservation->id_transaksi);
                }
            }

            // 3. Beri pesan di terminal bahwa tugas berhasil (diperbarui)
            $this->info('Berhasil menandai ' . $count . ' reservasi sebagai kedaluwarsa.');
        
        } else {
            // 4. Beri pesan jika tidak ada yang ditemukan
            $this->info('Tidak ada reservasi kedaluwarsa yang ditemukan.');
        }

        return 0;
    }
}
