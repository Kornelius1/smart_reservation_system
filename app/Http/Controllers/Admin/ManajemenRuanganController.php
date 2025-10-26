<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\Room;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Storage; // Penting untuk mengelola file

class ManajemenRuanganController extends Controller
{
    /**
     * Menampilkan halaman utama manajemen ruangan.
     */
    public function index()
    {
        // Mengambil semua ruangan, diurutkan dari yang terbaru
        $rooms = Room::latest()->get(); 
        
        // Mengirim data 'rooms' ke view
        return view('admin.manajemen-ruangan', compact('rooms'));
    }

    /**
     * Menyimpan data ruangan baru dari modal "Tambah Ruangan".
     */
    public function store(Request $request)
    {
        // 1. Validasi semua input dari form modal tambah
        $validatedData = $request->validate([
            'nama_ruangan' => 'required|string|max:255|unique:rooms,nama_ruangan',
            'kapasitas' => 'required|integer|min:1',
            'minimum_order' => 'required|integer|min:0',
            'lokasi' => 'required|string|max:255',
            'fasilitas' => 'nullable|string',
            'keterangan' => 'nullable|string',
            'image_url' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048', // Wajib ada gambar saat tambah
        ]);

        try {
            // 2. Handle file upload
            if ($request->hasFile('image_url')) {
                // Simpan gambar di 'storage/app/public/images/rooms'
                // 'public' adalah nama disk
                $path = $request->file('image_url')->store('images/rooms', 'public');
                
                // Simpan path relatif (misal: 'images/rooms/foto.jpg') ke database
                $validatedData['image_url'] = $path;
            }

            // 3. Buat data baru di database
            Room::create($validatedData);

            // 4. Redirect kembali dengan notifikasi sukses
            return redirect()->route('admin.manajemen-ruangan.index')->with('success', 'Ruangan baru berhasil ditambahkan!');

        } catch (\Exception $e) {
            // 5. Jika gagal, redirect kembali dengan notifikasi error
            return redirect()->back()->with('error', 'Gagal menyimpan ruangan: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Memperbarui data ruangan dari modal "Ubah Detail".
     */
   public function update(Request $request, $id)
    {
        // 1. Cari ruangan yang akan di-update
        $room = Room::findOrFail($id);

        try {
            // 2. Validasi semua input dari form modal ubah
            // Validasi akan otomatis melempar 'ValidationException' jika gagal
            $validatedData = $request->validate([
                // Rule::unique di-ignore untuk ID saat ini, agar tidak error "nama sudah ada"
                'nama_ruangan' => ['required', 'string', 'max:255', Rule::unique('rooms')->ignore($room->id)],
                'kapasitas' => 'required|integer|min:1',
                'minimum_order' => 'required|integer|min:0',
                'lokasi' => 'required|string|max:255',
                'fasilitas' => 'nullable|string',
                'keterangan' => 'nullable|string',
                'image_url' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048', // Opsional saat update
                
                // PENTING: Validasi juga hidden input yang kita tambahkan di view
                'update_room_id' => 'required|integer' 
            ]);

            // 3. Handle file upload (JIKA ada gambar baru)
            if ($request->hasFile('image_url')) {
                
                // Hapus gambar lama (jika ada) dari storage
                if ($room->image_url) {
                    Storage::disk('public')->delete($room->image_url);
                }

                // Simpan gambar baru
                $path = $request->file('image_url')->store('images/rooms', 'public');
                $validatedData['image_url'] = $path;
            }

            // 4. Update data di database
            $room->update($validatedData);

            // 5. Redirect kembali dengan notifikasi sukses
            return redirect()->route('admin.manajemen-ruangan.index')->with('success', 'Data ruangan berhasil diperbarui!');

        
        // =======================================================
        // PERBAIKAN UTAMA ADA DI SINI
        // =======================================================

        } catch (ValidationException $e) {
            // 6A. Jika GAGAL VALIDASI:
            // Kembali ke halaman sebelumnya DENGAN input lama DAN
            // masukkan semua error ke 'Error Bag' bernama 'update'.
            return redirect()->back()
                             ->withInput()
                             ->withErrors($e->validator, 'update'); // <-- Ini kunci agar JS tahu

        } catch (\Exception $e) {
            // 6B. Jika Gagal karena hal lain (Database mati, dll):
            // Kembali dengan notifikasi error umum.
            return redirect()->back()->with('error', 'Gagal memperbarui ruangan: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * (INI YANG ERROR) Memperbarui status ketersediaan ruangan (toggle).
     */
    public function updateStatus(Request $request, $id)
    {
        // 1. Validasi input. Status harus 'tersedia' atau 'tidak tersedia'.
        $validated = $request->validate([
            'status' => ['required', Rule::in(['tersedia', 'tidak tersedia'])],
        ]);

        try {
            // 2. Cari ruangan
            $room = Room::findOrFail($id);
            
            // 3. Update status
            $room->status = $validated['status'];
            $room->save();

            // 4. Kirim notifikasi sukses (redirect back() lebih baik untuk toggle)
            return redirect()->back()->with('success', 'Status untuk "' . $room->nama_ruangan . '" berhasil diperbarui.');

        } catch (\Exception $e) {
            // 5. Kirim notifikasi error jika gagal
            return redirect()->back()->with('error', 'Gagal memperbarui status: ' . $e->getMessage());
        }
    }
    
    /**
     * (Metode 'edit' Anda yang sudah ada, tidak terpakai oleh modal tapi ada di route)
     * Menampilkan halaman edit (jika Anda membuat halaman edit terpisah).
     */
    public function edit($id)
    {
        $room = Room::findOrFail($id);
        // Pastikan Anda punya view 'admin.manajemen-ruangan.edit'
        return view('admin.manajemen-ruangan.edit', compact('room'));
    }
}