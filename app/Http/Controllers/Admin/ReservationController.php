<?php

namespace App\Http\Controllers\Admin;

use App\Models\Reservation;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\View\View; // <-- Ditambahkan
use Illuminate\Http\RedirectResponse; // <-- Ditambahkan

class ReservationController extends Controller
{
    /**
     * Menampilkan daftar semua reservasi.
     */
    // public function index(): View // <-- Ditambahkan return type
    // {
    //     // ==========================================================
    //     // GABUNGAN: Memuat 'products' DAN mengurutkan status
    //     // ==========================================================
    //     $reservations = Reservation::with('products')
    //         ->orderByRaw("
    //             CASE 
    //                 WHEN status = 'check-in' THEN 1    -- Diperbarui ke 'check-in'
    //                 WHEN status = 'akan datang' THEN 2 -- Diperbarui ke 'akan datang'
    //                 WHEN status = 'pending' THEN 3      -- Ditambahkan 'pending'
    //                 WHEN status = 'selesai' THEN 4      -- Diperbarui ke 'selesai'
    //                 WHEN status = 'dibatalkan' THEN 5   -- Diperbarui ke 'dibatalkan'
    //                 ELSE 6
    //             END
    //         ")
    //         ->orderBy('tanggal', 'asc') // Urutkan berdasarkan tanggal terdekat
    //         ->orderBy('waktu', 'asc')   // Lalu berdasarkan waktu
    //         ->get();
        
    //     return view('admin.manajemen-reservasi', compact('reservations'));
    // }

    /**
     * Mengubah status reservasi menjadi 'check-in'
     */
    public function checkin(string $id): RedirectResponse
    {
        $reservation = Reservation::findOrFail($id);
        
        // Hanya izinkan check-in jika statusnya 'akan datang'
        if ($reservation->status == 'akan datang') { // <-- Menggunakan status 'akan datang'
            $reservation->status = 'check-in'; // <-- Menggunakan status 'check-in'
            $reservation->save();
            
            return redirect()->back()->with('success', 'Reservasi berhasil di-check-in.');
        }

        return redirect()->back()->with('error', 'Gagal, Status reservasi tidak valid.');
    }

    /**
     * Mengubah status reservasi menjadi 'selesai' (Complete/Check-out)
     */
    public function complete(string $id): RedirectResponse
    {
        $reservation = Reservation::findOrFail($id);

        // Hanya izinkan selesai jika statusnya 'check-in'
        if ($reservation->status == 'check-in') { // <-- Menggunakan status 'check-in'
            $reservation->status = 'selesai'; // <-- Menggunakan status 'selesai'
            $reservation->save();

            return redirect()->back()->with('success', 'Reservasi telah diselesaikan.');
        }

        return redirect()->back()->with('error', 'Gagal, Status reservasi tidak valid.');
    }

    /**
     * Mengubah status reservasi menjadi 'dibatalkan' (Cancel)
     */
    public function cancel(string $id): RedirectResponse
    {
        $reservation = Reservation::findOrFail($id);
        
        // Anda bisa membatalkan reservasi yang 'akan datang' atau 'pending'
        if ($reservation->status == 'akan datang' || $reservation->status == 'pending') {
            $reservation->status = 'dibatalkan'; // <-- Menggunakan status 'dibatalkan'
            $reservation->save();

            return redirect()->back()->with('success', 'Reservasi telah dibatalkan.');
        }
        
        return redirect()->back()->with('error', 'Gagal, Reservasi yang sedang berlangsung atau selesai tidak bisa dibatalkan.');
    }
}

