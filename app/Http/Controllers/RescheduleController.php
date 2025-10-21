<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Reservation; // <-- Menggunakan Model

class RescheduleController extends Controller
{

    /**
     * Menampilkan form pencarian awal.
     */
    public function showForm()
    {
        return view('customer.reschedule');
    }

    
    /**
     * Mencari reservasi berdasarkan ID Transaksi.
     */
    public function findReservation(Request $request)
    {
        // Validasi input pencarian
        $request->validate(['id_transaksi' => 'required|string']);
        $id_transaksi = $request->input('id_transaksi');

        // Ambil data dari database menggunakan Eloquent
        $reservasi = Reservation::where('id_transaksi', $id_transaksi)->first();

        if (!$reservasi) {
            return redirect()->route('reschedule.form')
                ->with('error', 'ID Transaksi tidak ditemukan!')
                ->withInput(); // <-- Tambahkan ini agar ID yg dicari tidak hilang
        }

        // Cek apakah tanggal reservasi masih H+1 (minimal besok)
        // (Asumsi Carbon::today() adalah 21 Okt 2025)
        $tanggalReservasi = $reservasi->tanggal; // Sudah jadi objek Carbon dari $casts
        
        // 1. Tentukan apakah bisa reschedule atau tidak
        $bisa_reschedule = $tanggalReservasi->isAfter(Carbon::today());

        // 2. Format tanggal untuk ditampilkan di view
        $reservasi->jadwal_awal_formatted = $tanggalReservasi->format('d M Y') . ', Pukul ' . $reservasi->waktu->format('H:i');

        // Kirim data yang sudah siap ke view
        return view('customer.reschedule', [
            'reservasi' => $reservasi, // Kirim sebagai objek Eloquent
            'bisa_reschedule' => $bisa_reschedule,
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
                                  ->exists();

        if ($isBentrok) {
            return redirect()->back()->with('update_error', 'Jadwal pada tanggal dan jam tersebut sudah terisi.');
        }
        
        // 3. Validasi Batas Waktu H-1 (Cek ulang data asli di DB)
        $reservasiAsli = Reservation::where('id_transaksi', $validated['id_transaksi'])->firstOrFail();

        if (!$reservasiAsli->tanggal->isAfter(Carbon::today())) {
            return redirect()->back()->with('update_error', 'Gagal! Waktu reschedule sudah melewati batas H-1.');
        }
        
        // --- PROSES UPDATE DATABASE ---
        $reservasiAsli->tanggal = $validated['tanggal_baru'];
        $reservasiAsli->waktu = $validated['waktu_baru'];
        $reservasiAsli->save(); // <-- Data tersimpan di database

        return redirect()->route('reschedule.form')
            ->with('success', "Reservasi {$validated['id_transaksi']} berhasil diubah ke {$validated['tanggal_baru']} jam {$validated['waktu_baru']}.");
    }
}