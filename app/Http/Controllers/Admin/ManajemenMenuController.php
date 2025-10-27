<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str; 
// TAMBAHAN: Dibutuhkan untuk validasi
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class ManajemenMenuController extends Controller
{
     public function index()
     {
         $menuItems = Product::orderBy('name', 'asc')->get();
         
         $categories = Product::select('category')
                           ->distinct()
                           ->orderBy('category', 'asc')
                           ->pluck('category');
         
         return view('admin.manajemen-menu', compact('menuItems', 'categories'));
     }

     /**
      * Menyimpan menu baru ke database.
      */
     public function store(Request $request)
     {
         $valid_categories = Product::select('category')->distinct()->pluck('category')->implode(',');

         // 1. Validasi (Termasuk 'stok_menu')
         $validated = $request->validate([
             'nama_menu'     => 'required|string|max:255|unique:products,name',
             'harga_menu'    => 'required|numeric|min:0',
             'stok_menu'     => 'required|integer|min:0', // TAMBAHAN: Validasi stok
             'kategori_menu' => 'required|string|in:' . $valid_categories, 
             'gambar_menu'   => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
         ]);

         try {
            $path = $request->file('gambar_menu')->store('images/menu', 'public');

            // 3. Simpan ke database
            Product::create([
                'name'      => $validated['nama_menu'],
                'price'     => $validated['harga_menu'],
                'stock'     => $validated['stok_menu'], // TAMBAHAN: Simpan stok
                'category'  => $validated['kategori_menu'],
                'image_url' => $path,
                // LOGIKA STOK: Otomatis 'tidak tersedia' jika stok 0
                'tersedia'  => ($validated['stok_menu'] > 0), 
            ]);

            return redirect()->route('menu.index')->with('success', 'Menu baru berhasil ditambahkan!');

         } catch (\Exception $e) {
            // Jika gagal, kembali dengan error
            return redirect()->back()
                   ->with('error', 'Gagal menyimpan menu: ' . $e->getMessage())
                   ->withInput(); // Bawa input lama
        }
     }

     /**
      * Update data menu yang ada.
      */
     // PERBAIKAN: Parameter harus $id agar cocok dengan route
     public function update(Request $request, string $id) 
     {
        // TAMBAHAN: Kita butuh 'update_menu_id' untuk redirect jika validasi gagal
        $validatedUpdateId = $request->validate(['update_menu_id' => 'required|integer']);

        $menu = Product::findOrFail($id);
        
        // Pastikan ID dari form_ubah sama dengan ID di URL
        if ($menu->id != $validatedUpdateId['update_menu_id']) {
             return redirect()->back()->with('error', 'Terjadi kesalahan ID. Silakan coba lagi.');
        }

        $valid_categories = Product::select('category')->distinct()->pluck('category')->implode(',');

        try {
            // 1. Validasi
            $validated = $request->validate([
                // PERBAIKAN: Gunakan Rule::unique
                'ubah_nama_menu'     => ['required', 'string', 'max:255', Rule::unique('products', 'name')->ignore($menu->id)],
                'ubah_harga_menu'    => 'required|numeric|min:0',
                'ubah_stok_menu'     => 'required|integer|min:0', // TAMBAHAN: Validasi stok
                'ubah_kategori_menu' => 'required|string|in:' . $valid_categories,
                'ubah_gambar_menu'   => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            ]);

            // 2. Siapkan data untuk di-update
            $dataToUpdate = [
                'name'     => $validated['ubah_nama_menu'],
                'price'    => $validated['ubah_harga_menu'],
                'stock'    => $validated['ubah_stok_menu'], // TAMBAHAN: Update stok
                'category' => $validated['ubah_kategori_menu'],
            ];

            // 3. LOGIKA STOK: Jika stok di-set ke 0, paksa status 'tersedia' jadi false
            if ($validated['ubah_stok_menu'] == 0) {
                $dataToUpdate['tersedia'] = false;
            }

            // 4. Cek jika ada file gambar baru
            if ($request->hasFile('ubah_gambar_menu')) {
                if ($menu->image_url && !Str::startsWith($menu->image_url, ['http://', 'https://'])) {
                    Storage::disk('public')->delete($menu->image_url);
                }
                
                $path = $request->file('ubah_gambar_menu')->store('images/menu', 'public');
                $dataToUpdate['image_url'] = $path;
            }

            // 5. Update database
            $menu->update($dataToUpdate);

            return redirect()->route('menu.index')->with('success', 'Detail menu berhasil diperbarui!');
        
        } catch (ValidationException $e) {
            // 6A. Jika GAGAL VALIDASI:
            // Kembali DENGAN input DAN error, serta 'error_bag' 'update'
            return redirect()->back()
                             ->withInput()
                             // Kirim juga ID menu agar modal tahu siapa yang di-edit
                             ->with('update_error_id', $id) 
                             ->withErrors($e->validator, 'update'); // Kirim error ke 'Error Bag' bernama 'update'

        } catch (\Exception $e) {
            // 6B. Jika Gagal karena hal lain
            return redirect()->back()
                   ->with('error', 'Gagal memperbarui menu: ' . $e->getMessage())
                   ->withInput();
        }
     }

     /**
      * Mengubah status ketersediaan
      */
     public function updateStatus(Request $request, string $id)
     {
          $menu = Product::findOrFail($id);
          
          $newStatus = $request->input('tersedia') === 'true' ? true : false;

          // LOGIKA STOK: Cek jika admin mencoba set 'Tersedia' padahal stok 0
          if ($newStatus === true && $menu->stock == 0) {
              return redirect()->back()->with('error', 'Tidak dapat mengubah status. Stok menu "' . $menu->name . '" adalah 0.');
          }
          
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
         try {
            $menu = Product::findOrFail($id);
            
            if ($menu->image_url && !Str::startsWith($menu->image_url, ['http://', 'https://'])) {
                Storage::disk('public')->delete($menu->image_url);
            }
            
            $namaMenu = $menu->name;
            $menu->delete();
            
            return redirect()->route('menu.index')->with('success', 'Menu "' . $namaMenu . '" berhasil dihapus.');

         } catch (\Exception $e) {
             return redirect()->back()->with('error', 'Gagal menghapus menu: ' . $e->getMessage());
         }
     }
}