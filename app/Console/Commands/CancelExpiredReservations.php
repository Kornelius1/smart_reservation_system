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
    protected $description = 'Mencari dan membatalkan reservasi pending yang sudah kedaluwarsa';

    /**
     * Jalankan logic command.
     */
    public function handle()
    {
        // Tentukan batas waktu kedaluwarsa.
        // Ini HARUS cocok dengan 'payment_due_date' di BayarController Anda.
        // Karena Anda simulasi 1 menit, kita gunakan 1 menit di sini.
        // Nanti, ubah ini ke 60 (menit) jika di produksi.
        $expiryMinutes = 1; 
        
        $cutoffTime = now()->subMinutes($expiryMinutes);

        // 1. Cari semua reservasi yang 'pending' DAN dibuat
        //    sebelum waktu batas (cutoffTime).
        $expiredReservations = Reservation::where('status', 'pending')
                                          ->where('created_at', '<=', $cutoffTime)
                                          ->get();

      if ($expiredReservations->count() > 0) {
            
            // 2. (KODE BARU) Ubah statusnya menjadi 'kedaluwarsa'
            foreach ($expiredReservations as $reservation) {
                $reservation->update(['status' => 'kedaluwarsa']);
                
                // (Opsional) Log info yang baru
                Log::info('Reservasi Kedaluwarsa Otomatis (Payment Expired): ' . $reservation->id_transaksi);
            }

            // 3. Beri pesan di terminal bahwa tugas berhasil (diperbarui)
            $this->info('Berhasil menandai ' . $expiredReservations->count() . ' reservasi sebagai kedaluwarsa.');
        
        } else {
            // 4. Beri pesan jika tidak ada yang ditemukan
            $this->info('Tidak ada reservasi kedaluwarsa yang ditemukan.');
        }

        return 0;
    }
}