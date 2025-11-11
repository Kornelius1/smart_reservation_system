<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Reservation;
use Illuminate\View\View;
use Carbon\Carbon;

class LaporanController extends Controller
{
    /**
     * Menampilkan halaman laporan dan memfilter data.
     */
    public function index(Request $request): View
    {
        // Mulai kueri dasar
        // PENTING: Langsung filter HANYA yang statusnya 'Selesai'
        $query = Reservation::where('status', 'Selesai');

        // Filter berdasarkan rentang tanggal jika ada
        if ($request->filled('start_date')) {
            $startDate = Carbon::parse($request->start_date)->startOfDay();
            $query->where('tanggal', '>=', $startDate);
        }

        if ($request->filled('end_date')) {
            $endDate = Carbon::parse($request->end_date)->endOfDay();
            $query->where('tanggal', '<=', $endDate);
        }

        // Ambil data dengan urutan terbaru di atas + paginasi
        $reservations = $query->orderBy('tanggal', 'desc')
                              ->orderBy('waktu', 'desc')
                              ->paginate(10)
                              ->withQueryString(); // Agar filter tetap ada saat ganti halaman

        // Kirim data ke view
        return view('admin.manajemen-laporan', [ // Pastikan nama view Anda sesuai
            'reservations' => $reservations
        ]);
    }

    /**
     * Menangani ekspor CSV
     */
    public function export(Request $request)
    {
        // Logika di sini SAMA PERSIS dengan index(), 
        // bedanya tidak pakai paginate() tapi pakai get()

        $query = Reservation::where('status', 'Selesai');

        if ($request->filled('start_date')) {
            $query->where('tanggal', '>=', Carbon::parse($request->start_date)->startOfDay());
        }

        if ($request->filled('end_date')) {
            $query->where('tanggal', '<=', Carbon::parse($request->end_date)->endOfDay());
        }

        $data = $query->orderBy('tanggal', 'asc')->get();

        // Logika untuk membuat file CSV...
        // (Ini adalah contoh sederhana, Anda bisa pakai package seperti Maatwebsite/Excel)
        
        $fileName = 'laporan_reservasi_' . date('Y-m-d') . '.csv';
        $headers = [
            'Content-type'        => 'text/csv',
            'Content-Disposition' => "attachment; filename=$fileName",
            'Pragma'              => 'no-cache',
            'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0',
            'Expires'             => '0'
        ];

        $callback = function() use ($data) {
            $file = fopen('php://output', 'w');
            
            // Header kolom
            fputcsv($file, ['ID Transaksi', 'Nama', 'Tanggal', 'Waktu', 'Jumlah Orang', 'Nomor Meja', 'Nomor Ruangan']);

            // Baris data
            foreach ($data as $row) {
                fputcsv($file, [
                    $row->id_transaksi,
                    $row->nama,
                    $row->tanggal->format('d-m-Y'),
                    $row->waktu ? \Carbon\Carbon::parse($row->waktu)->format('H:i') : '-',
                    $row->jumlah_orang,
                    $row->nomor_meja ?? '-',
                    $row->nomor_ruangan ?? '-',
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}