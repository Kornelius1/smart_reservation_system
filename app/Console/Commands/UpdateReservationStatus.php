<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Reservation; // <-- Pastikan ini nama Model Anda
use Carbon\Carbon;

class UpdateReservationStatus extends Command
{
    /**
     * Nama dan tanda tangan dari perintah konsol.
     *
     * @var string
     */
    protected $signature = 'reservations:update-status';

    /**
     * Deskripsi perintah konsol.
     *
     * @var string
     */
    protected $description = 'Memperbarui status reservasi otomatis berdasarkan waktu.';

    /**
     * Jalankan perintah konsol.
     */
    public function handle()
    {
        // Ambil waktu 'sekarang' dengan detail jam, menit, detik
        $now = Carbon::now(); 
        
        // Ambil tanggal hari ini, tapi set jamnya ke 00:00:00
        // Ini berguna untuk perbandingan "sebelum hari ini"
        $today = Carbon::today(); 

        // =================================================================
        // LOGIKA 1 (BARU): Update reservasi yang sudah lewat (dari hari-hari sebelumnya)
        // =================================================================

        // 1. Ubah 'Berlangsung' dari kemarin -> 'Selesai'
        //    (Sesuai permintaan Anda)
        $completed = Reservation::where('tanggal', '<', $today)
                              ->where('status', 'Berlangsung')
                              ->update(['status' => 'Selesai']);

        if ($completed > 0) {
            $this->info($completed . ' reservasi "Berlangsung" diubah menjadi "Selesai".');
        }

        // 2. LOGIKA TAMBAHAN (SANGAT DISARANKAN):
        //    Ubah 'Akan Datang' dari kemarin -> 'Tidak Datang'
        //    (Ini untuk kasus tamu yang tidak check-in sama sekali)
        $noShow = Reservation::where('tanggal', '<', $today)
                           ->where('status', 'Akan Datang')
                           ->update(['status' => 'Tidak Datang']);
        
        if ($noShow > 0) {
            $this->info($noShow . ' reservasi "Akan Datang" diubah menjadi "Tidak Datang".');
        }

        // =================================================================
        // LOGIKA 2 (LAMA): Update reservasi 'Akan Datang' HARI INI
        // =================================================================
        
        // Cek reservasi 'Akan Datang' yang tanggalnya HARI INI
        // dan waktunya sudah tiba/lewat
        $ongoing = Reservation::where('status', 'Akan Datang')
            ->whereDate('tanggal', '=', $today->toDateString()) // Tanggal adalah HARI INI
            ->whereTime('waktu', '<=', $now->toTimeString())   // Waktu sudah lewat
            ->update(['status' => 'Berlangsung']);

        if ($ongoing > 0) {
            $this->info($ongoing . ' reservasi "Akan Datang" diubah menjadi "Berlangsung".');
        }

        // Pesan jika tidak ada yang diubah
        if ($completed == 0 && $noShow == 0 && $ongoing == 0) {
            $this->info('Tidak ada status reservasi yang perlu diperbarui.');
        }
    }
}