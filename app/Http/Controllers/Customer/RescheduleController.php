<?php
namespace App\Http\Controllers\Customer;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Reservation; 
use Illuminate\View\View; // <-- Tambahkan ini untuk View

class RescheduleController extends Controller
{

    /**
     * Menampilkan form pencarian awal.
     */
    public function showForm(): View // <-- Perjelas return type
    {
        return view('customer.reschedule');
    }

    
    /**
     * Mencari reservasi berdasarkan ID Transaksi.
     */
    public function findReservation(Request $request): View // <-- Perjelas return type
    {
        // Validasi input pencarian
        $request->validate(['id_transaksi' => 'required|string']);
        $id_transaksi = $request->input('id_transaksi');

        // Ambil data dari database menggunakan Eloquent
        $reservasi = Reservation::where('id_transaksi', $id_transaksi)->first();

        if (!$reservasi) {
            return view('customer.reschedule')->with('error', 'ID Transaksi tidak ditemukan!');
            // Kita gunakan view() agar halaman tetap sama, bukan redirect()
        }

        // --- KUMPULKAN SEMUA DATA UNTUK VIEW ---
        
        // 1. Ambil data penting
        $tanggalReservasi = $reservasi->tanggal; // Ini sudah objek Carbon dari $casts
        $statusReservasi = $reservasi->status;   // Ini string ('Akan Datang', 'Selesai', dll)

        // 2. Tentukan apakah bisa reschedule atau tidak
        // =======================================================
        // PERBAIKAN LOGIKA INTI DI SINI
        // =======================================================
        $bisa_reschedule = ($statusReservasi == 'Akan Datang') && ($tanggalReservasi->isAfter(Carbon::today()));

        // 3. Buat alasan kenapa tidak bisa (untuk ditampilkan ke user)
        $alasan_tidak_bisa = null;
        if (!$bisa_reschedule) {
            if ($statusReservasi != 'Akan Datang') {
                $alasan_tidak_bisa = "Reservasi tidak dapat diubah karena statusnya sudah \"$statusReservasi\".";
            } elseif (!$tanggalReservasi->isAfter(Carbon::today())) {
                $alasan_tidak_bisa = "Reservasi tidak dapat diubah karena sudah melewati batas waktu H-1.";
            }
        }
        
        // 4. Format tanggal untuk ditampilkan di view
        // =======================================================
        // PERBAIKAN ERROR (waktu adalah string, bukan objek)
        // =======================================================
        $waktuFormatted = $reservasi->waktu ? Carbon::parse($reservasi->waktu)->format('H:i') : '[Waktu TBC]';
        $reservasi->jadwal_awal_formatted = $tanggalReservasi->format('d M Y') . ', Pukul ' . $waktuFormatted;


        // Kirim data yang sudah siap ke view
        return view('customer.reschedule', [
            'reservasi' => $reservasi, 
            'bisa_reschedule' => $bisa_reschedule,
            'alasan_tidak_bisa' => $alasan_tidak_bisa, // <-- Kirim alasan ke view
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

        // 1. Validasi Jam Operasional
        $waktuBaru = Carbon::parse($validated['waktu_baru']);
        if ($waktuBaru->hour >= 23) {
            return redirect()->back()->with('update_error', 'Melewati jam operasional. Harap pilih waktu sebelum 23:00.');
        }
        
        // 2. Validasi Bentrok (Cek ke database)
        $isBentrok = Reservation::where('tanggal', $validated['tanggal_baru'])
                                    ->where('waktu', $waktuBaru->format('H:i:s'))
                                    ->where('id_transaksi', '!=', $validated['id_transaksi'])
                                    ->where('status', '!=', 'Dibatalkan') // <-- Tambahan: jangan hitung yg batal
                                    ->exists();

        if ($isBentrok) {
            return redirect()->back()->with('update_error', 'Jadwal pada tanggal dan jam tersebut sudah terisi.');
        }
        
        // =======================================================
        // PERBAIKAN LOGIKA KEAMANAN (STATUS DAN TANGGAL)
        // =======================================================
        
        // 3. Validasi Keamanan (Cek ulang data asli di DB)
        $reservasiAsli = Reservation::where('id_transaksi', $validated['id_transaksi'])->firstOrFail();

        // Cek Status DULU
        if ($reservasiAsli->status != 'Akan Datang') {
             return redirect()->back()->with('update_error', "Gagal! Reservasi tidak bisa diubah karena statusnya sudah \"{$reservasiAsli->status}\".");
        }

        // LALU Cek Batas Waktu H-1
        if (!$reservasiAsli->tanggal->isAfter(Carbon::today())) {
            return redirect()->back()->with('update_error', 'Gagal! Waktu reschedule sudah melewati batas H-1.');
        }
        
        // --- PROSES UPDATE DATABASE ---
        $reservasiAsli->tanggal = $validated['tanggal_baru'];
        $reservasiAsli->waktu = $validated['waktu_baru'];
        // (Opsional) Jika Anda ingin statusnya kembali ke "Akan Datang" jika sebelumnya diubah admin
        // $reservasiAsli->status = 'Akan Datang'; 
        $reservasiAsli->save(); // <-- Data tersimpan di database

        return redirect()->route('reschedule.form')
            ->with('success', "Reservasi {$validated['id_transaksi']} berhasil diubah ke {$validated['tanggal_baru']} jam {$validated['waktu_baru']}.");
    }
}