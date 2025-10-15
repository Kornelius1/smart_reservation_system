<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;

class RescheduleController extends Controller
{
    public function showForm()
    {
        return view('reschedule');
    }

    public function findReservation(Request $request)
    {
        // Data dummy kita
        $reservations = [
            'TRS001' => ['id_transaksi' => 'TRS001', 'nama' => 'Budi', 'tanggal' => '2025-10-12', 'waktu' => '19:00'],
            'TRS002' => ['id_transaksi' => 'TRS002', 'nama' => 'Citra', 'tanggal' => '2025-11-20', 'waktu' => '12:00'],
            'TRS003' => ['id_transaksi' => 'TRS003', 'nama' => 'Dewi', 'tanggal' => '2025-11-22', 'waktu' => '20:00'], 
            'TRS004' => ['id_transaksi' => 'TRS004', 'nama' => 'Fahira', 'tanggal' => '2025-10-13', 'waktu' => '20:00'], 
        ];

        $id_transaksi = $request->input('id_transaksi');
        $reservasi = $reservations[$id_transaksi] ?? null;

        if (!$reservasi) {
            return redirect()->route('reschedule.form')->with('error', 'ID Transaksi tidak ditemukan!');
        }

        $tanggalReservasi = Carbon::parse($reservasi['tanggal']);
        
        // Aturan standar: bisa diubah sampai H-1 (apakah tanggal reservasi jatuh setelah hari ini?)
        $bisa_reschedule = $tanggalReservasi->isAfter(Carbon::today());

        return view('reschedule', [
            'reservasi' => $reservasi,
            'bisa_reschedule' => $bisa_reschedule,
        ]);
    }

    public function updateSchedule(Request $request)
    {
        $idTransaksi = $request->input('id_transaksi');
        $tanggalBaru = $request->input('tanggal_baru');
        $waktuBaru = $request->input('waktu_baru');
        
        // --- 1. Validasi Tanggal Mundur (dengan Timezone) ---
        $waktuBaruReservasi = Carbon::parse($tanggalBaru . ' ' . $waktuBaru, config('app.timezone'));
        if ($waktuBaruReservasi->isPast()) {
            return redirect()->back()->with('update_error', 'Anda tidak bisa memilih tanggal atau waktu yang sudah lewat.');
        }

        // --- 2. Validasi Jam Operasional di Server ---
        if (Carbon::parse($waktuBaru)->hour >= 23) {
            return redirect()->back()->with('update_error', 'Melewati jam operasional. Harap pilih waktu sebelum 23:00.');
        }

        // --- 3. Validasi Batas Waktu H-1 ---
        $reservations = [
            'TRS001' => ['id_transaksi' => 'TRS001', 'nama' => 'Budi', 'tanggal' => '2025-10-12', 'waktu' => '19:00'],
            'TRS002' => ['id_transaksi' => 'TRS002', 'nama' => 'Citra', 'tanggal' => '2025-11-20', 'waktu' => '12:00'],
            'TRS003' => ['id_transaksi' => 'TRS003', 'nama' => 'Dewi', 'tanggal' => '2025-11-22', 'waktu' => '20:00'],
            'TRS004' => ['id_transaksi' => 'TRS004', 'nama' => 'Fahira', 'tanggal' => '2025-10-13', 'waktu' => '20:00'],
        ];
        $reservasiAsli = $reservations[$idTransaksi] ?? null;

        if ($reservasiAsli) {
            $tanggalReservasiAsli = Carbon::parse($reservasiAsli['tanggal']);
            if (!$tanggalReservasiAsli->isAfter(Carbon::today())) {
                return redirect()->back()->with('update_error', 'Gagal! Waktu reschedule sudah melewati batas H-1.');
            }
        } else {
            return redirect()->route('reschedule.form')->with('error', 'Gagal memproses, ID Transaksi tidak valid.');
        }

        // --- 4. Validasi Jadwal Bentrok ---
        $jadwalTerisi = [
            '2025-11-25' => ['19:00:00', '20:00:00'], // Gunakan format H:i:s untuk perbandingan akurat
            '2025-11-26' => ['18:00:00'],
        ];
        
        $waktuBaruFormatted = Carbon::parse($waktuBaru)->format('H:i:s');
        if (isset($jadwalTerisi[$tanggalBaru]) && in_array($waktuBaruFormatted, $jadwalTerisi[$tanggalBaru])) {
            return redirect()->back()->with('update_error', 'Jadwal pada tanggal dan jam tersebut sudah terisi. Silakan pilih waktu lain.');
        }

        // --- Jika semua validasi lolos ---
        return redirect()->route('reschedule.form')
            ->with('success', "Reservasi $idTransaksi berhasil diubah ke tanggal $tanggalBaru jam $waktuBaru.");
    }
}