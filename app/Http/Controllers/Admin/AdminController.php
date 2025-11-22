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

        $statusAktif = ['check-in', 'akan datang'];
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

        $daysAhead = 2;
        $maxItems = 2;

        $allReservasi = Reservation::whereDate('tanggal', '>=', $now)
            ->whereDate('tanggal', '<=', $now->copy()->addDays($daysAhead))
            ->where('status', 'akan datang')
            ->orderBy('tanggal')
            ->orderBy('waktu')
            ->get();

        $reservasiMendatang = $allReservasi->take($maxItems);
        $extraReservasi = $allReservasi->slice($maxItems);
        $extraCount = $extraReservasi->count();

        return view('admin.DashboardAdmin', compact(
            'totalReservasi',
            'totalTamu',
            'totalPendapatan',
            'totalSelesai',
            'reservasiMendatang',
            'extraReservasi',
            'extraCount'
        ));
    }
}
