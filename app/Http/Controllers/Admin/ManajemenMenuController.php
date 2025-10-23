<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ManajemenMenuController extends Controller
{
    // Daftar kategori yang akan digunakan di dropdown
    private $categories = ['heavy-meal', 'light-meal', 'drink', 'snack', 'dessert'];

    /**
     * Menampilkan halaman manajemen menu.
     */
    public function index()
    {
        // Mengambil semua data produk/menu
        $menuItems = Product::orderBy('name', 'asc')->get();

        // Mengirim data menu dan daftar kategori ke view
        return view('admin.manajemen-menu', [
            'menuItems' => $menuItems,
            'categories' => $this->categories
        ]);
    }

    /**
     * Menyimpan menu baru ke database.
     */
    public function store(Request $request)
    {
        // Validasi input dari modal "Tambah Menu"
        // Nama input disesuaikan dengan 'name' di blade
        $validatedData = $request->validate([
            'nama_menu' => 'required|string|max:255|unique:products,name',
            'harga_menu' => 'required|numeric|min:0',
            'kategori_menu' => ['required', Rule::in($this->categories)],
            'gambar_menu' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
        ], [
            'nama_menu.required' => 'Nama menu tidak boleh kosong.',
            'nama_menu.unique' => 'Nama menu sudah ada.',
            'harga_menu.required' => 'Harga menu tidak boleh kosong.',
            'kategori_menu.required' => 'Kategori harus dipilih.',
            'gambar_menu.required' => 'Gambar menu tidak boleh kosong.',
            'gambar_menu.image' => 'File harus berupa gambar.',
        ]);

        try {
            // 1. Upload Gambar ke storage
            // 'public/menu-images' adalah folder di dalam 'storage/app/public/'
            $path = $request->file('gambar_menu')->store('public/menu-images');

            // 2. Dapatkan URL publik dari gambar
            // Ini akan menghasilkan URL seperti '/storage/menu-images/namagambar.jpg'
            $url = Storage::url($path);

            // 3. Simpan ke Database (Nama kolom disesuaikan dengan Model Product)
            Product::create([
                'name' => $validatedData['nama_menu'],
                'price' => $validatedData['harga_menu'],
                'category' => $validatedData['kategori_menu'],
                'image_url' => $url,
                'tersedia' => true 
            ]);

            return redirect()->back()->with('success', 'Menu baru berhasil ditambahkan.');
        } catch (\Exception $e) {
            // Jika gagal, tampilkan pesan error
            return redirect()->back()->with('error', 'Gagal menambahkan menu. Coba lagi.')->withInput();
        }
    }

    /**
     * Update status ketersediaan menu (via toggle).
     */
    // Menggunakan Route Model Binding (Product $product)
    public function updateStatus(Request $request, Product $product)
    {
        // Validasi input dari form toggle
        $request->validate([
            'tersedia' => 'required|in:true,false',
        ]);

        // Konversi string "true" / "false" dari form ke boolean
        $status = $request->input('tersedia') === 'true';

        $product->update(['tersedia' => $status]);

        $message = $status ? 'Status menu diubah menjadi Tersedia.' : 'Status menu diubah menjadi Habis.';
        return redirect()->back()->with('success', $message);
    }

    /**
     * Update detail menu (via modal Ubah Detail).
     */
    // Menggunakan Route Model Binding (Product $product)
    public function updateDetail(Request $request, Product $product)
    {
        // Validasi input dari modal "Ubah Detail"
        $validatedData = $request->validate([
            'ubah_nama_menu' => ['required', 'string', 'max:255', Rule::unique('products', 'name')->ignore($product->id)],
            'ubah_harga_menu' => 'required|numeric|min:0',
            'ubah_kategori_menu' => ['required', Rule::in($this->categories)],
            'ubah_gambar_menu' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048', // Opsional
        ], [
            'ubah_nama_menu.required' => 'Nama menu tidak boleh kosong.',
            'ubah_nama_menu.unique' => 'Nama menu sudah ada.',
        ]);

        try {
            // Siapkan data untuk di-update
            $dataToUpdate = [
                'name' => $validatedData['ubah_nama_menu'],
                'price' => $validatedData['ubah_harga_menu'],
                'category' => $validatedData['ubah_kategori_menu'],
            ];

            // Cek jika user mengupload gambar baru
            if ($request->hasFile('ubah_gambar_menu')) {
                // 1. Upload gambar baru
                $newPath = $request->file('ubah_gambar_menu')->store('public/menu-images');
                $newUrl = Storage::url($newPath);

                // 2. Hapus gambar lama (jika ada)
                // Ubah URL (cth: /storage/...) menjadi path storage (cth: public/...)
                $oldPath = str_replace(Storage::url(''), 'public/', $product->image_url);
                if (Storage::exists($oldPath)) {
                    Storage::delete($oldPath);
                }

                // 3. Tambahkan URL gambar baru ke data update
                $dataToUpdate['image_url'] = $newUrl;
            }

            // Update data di database
            $product->update($dataToUpdate);

            return redirect()->back()->with('success', 'Detail menu berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal memperbarui detail menu. Coba lagi.');
        }
    }
}