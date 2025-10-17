<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;

class RescheduleController extends Controller
{

    public function showForm()
    {
        return view('customer.reschedule');
    }

   
    public function findReservation(Request $request)
    {
        $reservations = $this->getReservations();

        $id_transaksi = $request->input('id_transaksi');
        $reservasi = $reservations[$id_transaksi] ?? null;

        if (!$reservasi) {
            return redirect()->route('reschedule.form')->with('error', 'ID Transaksi tidak ditemukan!');
        }

        // Siapkan semua data yang dibutuhkan oleh view SEBELUM dikirim.
        $tanggalReservasi = Carbon::parse($reservasi['tanggal']);
        
        // 1. Tentukan apakah bisa reschedule atau tidak
        $bisa_reschedule = $tanggalReservasi->isAfter(Carbon::today());

        // 2. Format tanggal untuk ditampilkan di view
        $reservasi['jadwal_awal_formatted'] = $tanggalReservasi->format('d M Y') . ', Pukul ' . $reservasi['waktu'];

        // Kirim data yang sudah siap ke view
        return view('customer.reschedule', [
            'reservasi' => $reservasi,
            'bisa_reschedule' => $bisa_reschedule,
        ]);
    }

    /**
     * Memproses update jadwal reservasi.
     */
    public function updateSchedule(Request $request)
    {
        // Gunakan validasi bawaan Laravel untuk input dasar
        $validated = $request->validate([
            'id_transaksi' => 'required|string',
            'tanggal_baru' => 'required|date|after_or_equal:today',
            'waktu_baru' => 'required|date_format:H:i',
        ]);

        // --- Validasi Logika Bisnis (yang tidak bisa dilakukan di aturan validasi) ---

        // 1. Validasi Jam Operasional
        if (Carbon::parse($validated['waktu_baru'])->hour >= 23) {
            return redirect()->back()->with('update_error', 'Melewati jam operasional. Harap pilih waktu sebelum 23:00.');
        }
        
        // 2. Validasi Jadwal Bentrok
        $jadwalTerisi = [
            '2025-11-25' => ['19:00:00', '20:00:00'],
            '2025-11-26' => ['18:00:00'],
        ];
        $waktuBaruFormatted = Carbon::parse($validated['waktu_baru'])->format('H:i:s');
        if (isset($jadwalTerisi[$validated['tanggal_baru']]) && in_array($waktuBaruFormatted, $jadwalTerisi[$validated['tanggal_baru']])) {
            return redirect()->back()->with('update_error', 'Jadwal pada tanggal dan jam tersebut sudah terisi.');
        }

        // 3. Validasi Batas Waktu H-1 (terhadap data lama)
        $reservasiAsli = $this->getReservations()[$validated['id_transaksi']] ?? null;
        if (!$reservasiAsli || !Carbon::parse($reservasiAsli['tanggal'])->isAfter(Carbon::today())) {
            return redirect()->back()->with('update_error', 'Gagal! Waktu reschedule sudah melewati batas H-1.');
        }
        
        // --- Jika semua validasi lolos, proses update (simulasi) ---
        // Di aplikasi nyata, Anda akan menyimpan ke database di sini.

        return redirect()->route('reschedule.form')
            ->with('success', "Reservasi {$validated['id_transaksi']} berhasil diubah ke tanggal {$validated['tanggal_baru']} jam {$validated['waktu_baru']}.");
    }

    /**
     * Mengambil data reservasi (dummy).
     * Di aplikasi nyata, ini akan mengambil data dari database.
     */
    private function getReservations(): array
    {
        return [
            'TRS001' => ['id_transaksi' => 'TRS001', 'nama' => 'Budi', 'tanggal' => '2025-10-17', 'waktu' => '19:00'],
            'TRS002' => ['id_transaksi' => 'TRS002', 'nama' => 'Citra', 'tanggal' => '2025-11-20', 'waktu' => '12:00'],
            'TRS003' => ['id_transaksi' => 'TRS003', 'nama' => 'Dewi', 'tanggal' => '2025-11-22', 'waktu' => '20:00'],
            'TRS004' => ['id_transaksi' => 'TRS004', 'nama' => 'Fahira', 'tanggal' => '2025-10-13', 'waktu' => '20:00'],
        ];
    }
}
