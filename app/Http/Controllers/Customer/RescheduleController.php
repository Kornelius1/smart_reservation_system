<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Reservation;
use Illuminate\View\View;
use Illuminate\Support\Str; // <-- Tambahkan ini untuk memformat teks status

class RescheduleController extends Controller
{

    /**
     * Menampilkan form pencarian awal.
     */
    public function showForm(): View
    {
        return view('customer.reschedule');
    }


    /**
     * Mencari reservasi berdasarkan ID Transaksi.
     */
    public function findReservation(Request $request): View
    {
        // Validasi input pencarian
        $request->validate(['id_transaksi' => 'required|string']);
        $id_transaksi = $request->input('id_transaksi');

        // Ambil data dari database
        $reservasi = Reservation::where('id_transaksi', $id_transaksi)->first();

        if (!$reservasi) {
            return view('customer.reschedule')->with('error', 'ID Transaksi tidak ditemukan!');
        }

        // --- KUMPULKAN SEMUA DATA UNTUK VIEW ---

        // 1. Ambil data penting
        $tanggalReservasi = $reservasi->tanggal; // Objek Carbon
        $statusReservasi = $reservasi->status;   // String ('pending', 'akan datang', 'check-in', dll)

        // 2. Tentukan apakah bisa reschedule atau tidak (LOGIKA BARU)
        // =======================================================
        // PERUBAHAN LOGIKA INTI DI SINI
        // =======================================================

        $bisa_reschedule = false;
        $alasan_tidak_bisa = null;

        // TAHAP 1: Cek Status (harus 'akan datang')
        // (Sesuai dengan @switch di file manajemen reservasi Anda)
        if ($statusReservasi == 'akan datang') {

            // TAHAP 2: Jika status OK, cek Batas Waktu H-1
            if ($tanggalReservasi->isAfter(Carbon::today())) {
                $bisa_reschedule = true;
            } else {
                // Statusnya benar, tapi sudah telat
                $alasan_tidak_bisa = "Reservasi tidak dapat diubah karena sudah melewati batas waktu reschedule (H-1).";
            }
            
        } else {
            // Status BUKAN 'akan datang'. Buat pesan error spesifik.
            
            // Ganti 'check-in' menjadi 'Berlangsung' agar lebih jelas
            $statusTampil = $statusReservasi;
            if ($statusReservasi == 'check-in') {
                $statusTampil = 'Berlangsung';
            }

            // Ubah 'pending' -> 'Pending', 'selesai' -> 'Selesai'
            $statusTampilRapi = Str::title($statusTampil); 
            
            $alasan_tidak_bisa = "Reservasi tidak dapat diubah karena statusnya sudah \"$statusTampilRapi\".";
        }
        // =======================================================
        // AKHIR PERUBAHAN LOGIKA
        // =======================================================


        // 3. Format tanggal untuk ditampilkan di view (Tetap sama)
        $waktuFormatted = $reservasi->waktu ? Carbon::parse($reservasi->waktu)->format('H:i') : '[Waktu TBC]';
        $reservasi->jadwal_awal_formatted = $tanggalReservasi->format('d M Y') . ', Pukul ' . $waktuFormatted;


        // Kirim data yang sudah siap ke view
        return view('customer.reschedule', [
            'reservasi' => $reservasi,
            'bisa_reschedule' => $bisa_reschedule,
            'alasan_tidak_bisa' => $alasan_tidak_bisa, // <-- Alasan dinamis
        ]);
    }

    /**
     * Memproses update jadwal reservasi (langsung ke DB).
     */
    public function updateSchedule(Request $request)
    {
        // Validasi input dasar
        $validated = $request->validate([
            'id_transaksi' => 'required|string|exists:reservations,id_transaksi',
            'tanggal_baru' => 'required|date|after_or_equal:today',
            'waktu_baru' => 'required|date_format:H:i',
        ]);

        // --- Validasi Logika Bisnis ---

        // 1. Validasi Jam Operasional (Tetap sama)
        $waktuBaru = Carbon::parse($validated['waktu_baru']);
        if ($waktuBaru->hour >= 23) {
            return redirect()->back()->with('update_error', 'Melewati jam operasional. Harap pilih waktu sebelum 23:00.');
        }

        // 2. Validasi Bentrok (Tetap sama)
        $isBentrok = Reservation::where('tanggal', $validated['tanggal_baru'])
            ->where('waktu', $waktuBaru->format('H:i:s'))
            ->where('id_transaksi', '!=', $validated['id_transaksi'])
            ->where('status', '!=', 'dibatalkan') 
            ->exists();

        if ($isBentrok) {
            return redirect()->back()->with('update_error', 'Jadwal pada tanggal dan jam tersebut sudah terisi.');
        }

        // =======================================================
        // PERBAIKAN LOGIKA KEAMANAN (STATUS DAN TANGGAL)
        // =======================================================

        // 3. Validasi Keamanan (Cek ulang data asli di DB)
        $reservasiAsli = Reservation::where('id_transaksi', $validated['id_transaksi'])->firstOrFail();

        // Cek Status DULU (harus 'akan datang')
        // <-- PERUBAHAN: dari 'Akan Datang' menjadi 'akan datang'
        if ($reservasiAsli->status != 'akan datang') {
            
            // Buat pesan error yang lebih rapi
            $statusTampil = $reservasiAsli->status;
            if ($statusTampil == 'check-in') {
                $statusTampil = 'Berlangsung';
            }
            $statusTampilRapi = Str::title($statusTampil);

            return redirect()->back()->with('update_error', "Gagal! Reservasi tidak bisa diubah karena statusnya sudah \"$statusTampilRapi\".");
        }

        // LALU Cek Batas Waktu H-1 (Tetap sama)
        if (!$reservasiAsli->tanggal->isAfter(Carbon::today())) {
            return redirect()->back()->with('update_error', 'Gagal! Waktu reschedule sudah melewati batas H-1.');
        }
        
        // =======================================================
        // AKHIR PERUBAHAN KEAMANAN
        // =======================================================

        // --- PROSES UPDATE DATABASE ---
        $reservasiAsli->tanggal = $validated['tanggal_baru'];
        $reservasiAsli->waktu = $validated['waktu_baru'];
        $reservasiAsli->save();

        return redirect()->route('reschedule.form')
            ->with('success', "Reservasi {$validated['id_transaksi']} berhasil diubah ke {$validated['tanggal_baru']} jam {$validated['waktu_baru']}.");
    }
}