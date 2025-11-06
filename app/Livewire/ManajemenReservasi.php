<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Reservation;
use Livewire\Attributes\Url;
use Livewire\Attributes\Layout;

#[Layout('layouts.reservasi-layout')]
class ManajemenReservasi extends Component
{
    #[Url]
    public $search = '';

    public function render()
    {
        // Gunakan Model 'Reservation'
        $query = Reservation::with('products') // Eager loading
                            ->orderBy('tanggal', 'desc')
                            ->orderBy('waktu', 'desc');

        // --- AWAL BLOK PENCARIAN BARU ---

        // 1. Siapkan istilah pencarian (Search Term)
        //    - Menghapus semua spasi
        //    - Mengubah ke huruf kecil
        //    Ini untuk memenuhi syarat "case insensitive" dan "space insensitive"
        $searchTerm = strtolower(preg_replace('/\s+/', '', trim($this->search)));

        // 2. Terapkan filter HANYA JIKA ada sesuatu untuk dicari
        if (!empty($searchTerm)) {
            $query->where(function ($q) use ($searchTerm) {
                
                // 3. Buat query "space-insensitive" dan "case-insensitive" untuk 'nama'
                //    - REPLACE() menghapus spasi dari kolom database
                //    - LOWER() mengubah kolom database ke huruf kecil
                //    - PERUBAHAN: Menggunakan ' ' dan '' (kutip tunggal) untuk string SQL
                $q->whereRaw("LOWER(REPLACE(nama, ' ', '')) LIKE ?", ['%' . $searchTerm . '%'])
                  
                  // 4. Tambahkan pencarian untuk 'id_transaksi' (dengan perlakuan yang sama)
                  //    Ini memenuhi syarat "cari berdasarkan id transaksi ATAU nama customer"
                  //    - PERUBAHAN: Menggunakan ' ' dan '' (kutip tunggal) untuk string SQL
                  ->orWhereRaw("LOWER(REPLACE(id_transaksi, ' ', '')) LIKE ?", ['%' . $searchTerm . '%']);
            });
        }
        // --- AKHIR BLOK PENCARIAN BARU ---


        // Ambil data
        $reservations = $query->get();

        // Urutkan dengan logika yang sama seperti di Controller Anda (Logika ini sudah benar dan tetap dipertahankan)
        $reservations = $reservations->sortBy(function($reservation) {
            return match ($reservation->status) {
                'check-in' => 1,
                'akan datang' => 2,
                'pending' => 3,
                'selesai' => 4,
                'dibatalkan' => 5,
                'kedaluwarsa'=> 6,
                default => 7,
            };
        });

        return view('livewire.manajemen-reservasi', [
            'reservations' => $reservations,
        ]);
    }
}

