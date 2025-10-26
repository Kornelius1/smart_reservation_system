<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\View\View;
use App\Models\Meja;
use Illuminate\Http\JsonResponse; // <-- Pastikan ini ditambahkan

class TableController extends Controller
{
    /**
     * Menampilkan halaman manajemen meja.
     * (Ini adalah method asli Anda)
     */
    public function index(): View
    {
        // 2. Ambil data dari database menggunakan Model Meja
        $mejas = Meja::all(); // Mengambil semua record dari tabel 'meja'

        // 3. Kirim data meja dari database ke view
        //    Pastikan view 'admin.manajemen-meja' sekarang menggunakan variabel $mejas
        return view('admin.manajemen-meja', ['tables' => $mejas]);
    }

    /**
     * Memperbarui status aktif meja (dipanggil oleh AJAX).
     * (Ini adalah method baru untuk toggle)
     *
     * @param Meja $meja Instance Meja dari Route-Model Binding
     * @return JsonResponse
     */
    public function toggleStatus(Meja $meja): JsonResponse
    {
        try {
            // Ubah status (true jadi false, false jadi true)
            $meja->status_aktif = !$meja->status_aktif;
            $meja->save();

            // Kirim balasan sukses bersama status baru (menggunakan accessor 'status' dari Model)
            return response()->json([
                'success' => true,
                'newStatus' => $meja->status_aktif,
                'statusText' => $meja->status // Ini akan 'Available' atau 'Not Available'
            ]);
        } catch (\Exception $e) {
            // Kirim balasan error jika gagal
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}

