<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\Reservation;

class ManajemenRescheduleController extends Controller
{
    /**
     * Menampilkan halaman manajemen reschedule.
     * (Ini seharusnya menampilkan SEMUA reservasi)
     */
    public function index(): View
    {
        // 2. Ambil SEMUA data reservasi dari database
        // Kita urutkan berdasarkan tanggal terbaru
        $reservations = Reservation::orderBy('tanggal', 'desc')->get();

        // 3. Kirim data ASLI dari database ke view
        return view('admin.ManajemenReschedule', [
            'reservations' => $reservations 
            // Nama variabel diubah dari 'reschedules' menjadi 'reservations' 
            // agar konsisten, atau biarkan 'reschedules' jika view sudah terlanjur
        ]);
    }
}