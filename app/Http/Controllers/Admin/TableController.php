<?php
namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;



use Illuminate\View\View;
use App\Models\Meja; 
class TableController extends Controller
{
    /**
     * Menampilkan halaman manajemen meja.
     */
    public function index(): View
    {
        // 2. Ambil data dari database menggunakan Model Meja
        $mejas = Meja::all(); // Mengambil semua record dari tabel 'meja'

        // 3. Kirim data meja dari database ke view
        //    Pastikan view 'admin.manajemen-meja' sekarang menggunakan variabel $mejas
        return view('admin.manajemen-meja', ['tables' => $mejas]);
    }
}