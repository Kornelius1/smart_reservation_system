<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\Reservation; 
use Illuminate\Http\RedirectResponse; // <-- TAMBAHKAN INI

class ReservationController extends Controller
{
    
    /**
     * Menampilkan halaman manajemen reservasi
     */
    public function index(): View
    {
        // Ubah dari ::all() agar datanya lebih terurut rapi
        // Tampilkan data terbaru (Akan Datang / Berlangsung) di paling atas
        $reservations = Reservation::orderByRaw("
            CASE 
                WHEN status = 'Berlangsung' THEN 1
                WHEN status = 'Akan Datang' THEN 2
                WHEN status = 'Selesai' THEN 3
                WHEN status = 'Dibatalkan' THEN 4
                ELSE 5
            END
        ")
        ->orderBy('tanggal', 'asc') // Urutkan berdasarkan tanggal terdekat
        ->orderBy('waktu', 'asc')   // Lalu berdasarkan waktu
        ->get();

        return view('admin.manajemen-reservasi', ['reservations' => $reservations]);
    }

    // --- FUNGSI-FUNGSI BARU DIMULAI DARI SINI ---

    /**
     * Mengubah status reservasi menjadi 'Berlangsung' (Check-in)
     */
    public function checkin(string $id): RedirectResponse
    {
        $reservation = Reservation::findOrFail($id);
        
        // Hanya izinkan check-in jika statusnya 'Akan Datang'
        if ($reservation->status == 'Akan Datang') {
            $reservation->status = 'Berlangsung';
            $reservation->save();
            
            // 'with()' akan mengirimkan 'flash message' ke session
            return redirect()->back()->with('success', 'Reservasi berhasil di-check-in.');
        }

        return redirect()->back()->with('error', 'Gagal, Status reservasi tidak valid.');
    }

    /**
     * Mengubah status reservasi menjadi 'Selesai' (Complete/Check-out)
     */
    public function complete(string $id): RedirectResponse
    {
        $reservation = Reservation::findOrFail($id);

        // Hanya izinkan selesai jika statusnya 'Berlangsung'
        if ($reservation->status == 'Berlangsung') {
            $reservation->status = 'Selesai';
            $reservation->save();

            return redirect()->back()->with('success', 'Reservasi telah diselesaikan.');
        }

        return redirect()->back()->with('error', 'Gagal, Status reservasi tidak valid.');
    }

    /**
     * Mengubah status reservasi menjadi 'Dibatalkan' (Cancel)
     */
    public function cancel(string $id): RedirectResponse
    {
        $reservation = Reservation::findOrFail($id);
        
        // Anda bisa membatalkan reservasi yang 'Akan Datang'
        if ($reservation->status == 'Akan Datang') {
            $reservation->status = 'Dibatalkan';
            $reservation->save();
            
            // Catatan: Anda mungkin perlu menambahkan logika lain di sini,
            // misalnya: membuat meja/ruangan tersedia kembali (jika ada manajemen stok).

            return redirect()->back()->with('success', 'Reservasi telah dibatalkan.');
        }
        
        return redirect()->back()->with('error', 'Gagal, Reservasi yang sedang berlangsung atau selesai tidak bisa dibatalkan.');
    }
}