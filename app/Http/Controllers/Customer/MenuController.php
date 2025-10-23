<?php

namespace App\Http\Controllers\Customer;

use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str; 
use App\Http\Controllers\Controller;

class MenuController extends Controller
{
    /**
     * Menampilkan halaman manajemen menu.
     */
    public function index()
    {
        // 1. Ambil semua item menu
        $menuItems = Menu::orderBy('name', 'asc')->get();
        
        // 2. Ambil semua kategori yang unik dari tabel 'products'
        $categories = Menu::select('category')
                          ->distinct()
                          ->orderBy('category', 'asc')
                          ->pluck('category');
        
        // 3. Kirim data menu DAN data kategori ke view
        return view('admin.manajemen-menu', compact('menuItems', 'categories'));
    }

    /**
     * Menyimpan menu baru ke database.
     */
    public function store(Request $request)
    {
        // Ambil daftar kategori yang valid dari database
        $valid_categories = Menu::select('category')->distinct()->pluck('category')->implode(',');

        // 1. Validasi
        $validated = $request->validate([
            'nama_menu'     => 'required|string|max:255|unique:products,name',
            'harga_menu'    => 'required|numeric|min:0',
            // Validasi 'in' sekarang dinamis sesuai isi database
            'kategori_menu' => 'required|string|in:' . $valid_categories, 
            'gambar_menu'   => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        // 2. Handle file upload (Simpan di 'storage/app/public/images/menu')
        // $path akan berisi 'images/menu/namafile.jpg'
        $path = $request->file('gambar_menu')->store('images/menu', 'public');

        // 3. Simpan ke database
        Menu::create([
            'name'      => $validated['nama_menu'],
            'price'     => $validated['harga_menu'],
            'category'  => $validated['kategori_menu'],
            'image_url' => $path, // Simpan path-nya
            'tersedia'  => true,   // Default menu baru 'tersedia'
        ]);

        return redirect()->route('menu.index')->with('success', 'Menu baru berhasil ditambahkan!');
    }

    /**
     * Update data menu yang ada.
     */
    public function update(Request $request, string $id)
    {
        $menu = Menu::findOrFail($id);
        
        // Ambil daftar kategori yang valid
        $valid_categories = Menu::select('category')->distinct()->pluck('category')->implode(',');

        // 1. Validasi
        $validated = $request->validate([
            'ubah_nama_menu'     => 'required|string|max:255|unique:products,name,' . $menu->id,
            'ubah_harga_menu'    => 'required|numeric|min:0',
            'ubah_kategori_menu' => 'required|string|in:' . $valid_categories, // Validasi dinamis
            'ubah_gambar_menu'   => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        // 2. Siapkan data untuk di-update
        $dataToUpdate = [
            'name'     => $validated['ubah_nama_menu'],
            'price'    => $validated['ubah_harga_menu'],
            'category' => $validated['ubah_kategori_menu'],
        ];

        // 3. Cek jika ada file gambar baru
        if ($request->hasFile('ubah_gambar_menu')) {
            // Hapus gambar lama, HANYA jika itu BUKAN URL eksternal (http)
            if ($menu->image_url && !Str::startsWith($menu->image_url, ['http://', 'https://'])) {
                Storage::disk('public')->delete($menu->image_url);
            }
            
            // Simpan gambar baru
            $path = $request->file('ubah_gambar_menu')->store('images/menu', 'public');
            $dataToUpdate['image_url'] = $path; // Simpan path baru
        }

        // 4. Update database
        $menu->update($dataToUpdate);

        return redirect()->route('menu.index')->with('success', 'Detail menu berhasil diperbarui!');
    }

    /**
     * Mengubah status ketersediaan (FITUR SUDAH AKTIF)
     */
    public function updateStatus(Request $request, string $id)
    {
         $menu = Menu::findOrFail($id);
         
         // Ambil status baru dari request, pastikan itu boolean
         $newStatus = $request->input('tersedia') === 'true' ? true : false;
         
         $menu->update([
             'tersedia' => $newStatus,
         ]);

         return redirect()->route('menu.index')->with('success', 'Status menu berhasil diubah.');
    }

    /**
     * Menghapus menu dari database
     */
    public function destroy(string $id)
    {
        $menu = Menu::findOrFail($id);
        
        // Hapus gambar, HANYA jika itu BUKAN URL eksternal (http)
        if ($menu->image_url && !Str::startsWith($menu->image_url, ['http://', 'https://'])) {
            Storage::disk('public')->delete($menu->image_url);
        }
        
        // Hapus data dari database
        $menu->delete();
        
        return redirect()->route('menu.index')->with('success', 'Menu berhasil dihapus.');
    }
}