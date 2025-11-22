<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use App\Models\Reservation;

class AdminController extends Controller
{
    public function index()
    {
        $now = Carbon::now();
        $startOfMonth = $now->copy()->startOfMonth();
        $endOfMonth = $now->copy()->endOfMonth();

        // ✅ Status sesuai database (lowercase + spasi)
        $statusAktif = ['check-in', 'akan datang']; // hanya yang sudah dikonfirmasi
        $statusSelesai = ['selesai'];

        // Statistik Bulan Ini
        $totalReservasi = Reservation::whereBetween('tanggal', [$startOfMonth, $endOfMonth])
            ->whereIn('status', $statusAktif)
            ->count();

        $totalTamu = Reservation::whereBetween('tanggal', [$startOfMonth, $endOfMonth])
            ->whereIn('status', $statusAktif)
            ->sum('jumlah_orang') ?? 0;

        $totalPendapatan = Reservation::whereBetween('tanggal', [$startOfMonth, $endOfMonth])
            ->whereIn('status', $statusAktif)
            ->sum('total_price') ?? 0;

        $totalSelesai = Reservation::whereBetween('tanggal', [$startOfMonth, $endOfMonth])
            ->whereIn('status', $statusSelesai)
            ->count();

        // Reservasi Mendatang (7 hari ke depan, hanya "akan datang")
        $reservasiMendatang = Reservation::whereDate('tanggal', '>=', $now)
            ->whereDate('tanggal', '<=', $now->copy()->addDays(7))
            ->where('status', 'akan datang') // ⚠️ lowercase + spasi!
            ->orderBy('tanggal')
            ->orderBy('waktu')
            ->limit(5)
            ->get();

        return view('admin.DashboardAdmin', compact(
            'totalReservasi',
            'totalTamu',
            'totalPendapatan',
            'totalSelesai',
            'reservasiMendatang'
        ));
    }
}