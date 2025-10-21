<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reservation;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class LaporanController extends Controller
{
    /**
     * Menampilkan daftar laporan reservasi dengan filter.
     */
    public function index(Request $request): View
    {
        // Validasi input agar lebih aman
        $request->validate([
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        $query = $this->getFilteredQuery($request);

        $reservations = $query->latest('tanggal')->paginate(10)->withQueryString();

        return view('admin.manajemen-laporan', compact('reservations'));
    }

    /**
     * Mengekspor data laporan ke dalam format CSV.
     */
    public function export(Request $request): StreamedResponse
    {
        // Menggunakan kembali logika filter dari method index
        $query = $this->getFilteredQuery($request);
        $reservations = $query->get();

        $fileName = 'laporan-reservasi-' . now()->format('Y-m-d') . '.csv';

        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = ['ID Transaksi', 'Nama Customer', 'Tanggal', 'Waktu', 'Dibuat Pada'];

        return response()->stream(function () use ($reservations, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($reservations as $reservation) {
                fputcsv($file, [
                    $reservation->id_transaksi,
                    $reservation->nama,
                    $reservation->tanggal->format('d-m-Y'),
                    $reservation->waktu->format('H:i'),
                    $reservation->created_at->format('d-m-Y H:i:s'),
                ]);
            }

            fclose($file);
        }, 200, $headers);
    }

    /**
     * Helper function untuk mengambil query yang sudah difilter.
     * Ini mencegah duplikasi kode antara index() dan export().
     */
    private function getFilteredQuery(Request $request)
    {
        $query = Reservation::query();

        if ($request->filled('start_date')) {
            $query->whereDate('tanggal', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('tanggal', '<=', $request->end_date);
        }

        return $query;
    }
}