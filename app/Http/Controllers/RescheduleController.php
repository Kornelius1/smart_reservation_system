<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;

class RescheduleController extends Controller
{
    // Menampilkan halaman awal dengan form pencarian
    public function showForm()
    {
        return view('reschedule');
    }

    // Mencari reservasi berdasarkan ID
    public function findReservation(Request $request)
    {
        // Data dummy kita
        $reservations = [
            'TRS001' => ['id_transaksi' => 'TRS001', 'nama' => 'Budi', 'tanggal' => '2025-10-12', 'waktu' => '19:00'],
            'TRS002' => ['id_transaksi' => 'TRS002', 'nama' => 'Citra', 'tanggal' => '2025-11-20', 'waktu' => '12:00'],
        ];

        $id_transaksi = $request->input('id_transaksi');
        $reservasi = $reservations[$id_transaksi] ?? null;

        if (!$reservasi) {
            return redirect()->route('reschedule.form')->with('error', 'ID Transaksi tidak ditemukan!');
        }

        // Cek apakah bisa reschedule (H-1 sebelum hari H)
        $tanggalReservasi = Carbon::parse($reservasi['tanggal']);
        $bisa_reschedule = $tanggalReservasi->isAfter(Carbon::now()->addDay());

        // Kembali ke view dengan membawa data
        return view('reschedule', [
            'reservasi' => $reservasi,
            'bisa_reschedule' => $bisa_reschedule,
        ]);
    }
}