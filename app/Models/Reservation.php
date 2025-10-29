<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang terhubung dengan model.
     *
     * @var string
     */
    protected $table = 'reservations'; 

    /**
     * Primary key untuk model ini.
     *
     * @var string
     */
    protected $primaryKey = 'id_reservasi'; 

    // public $incrementing = true; // Tidak perlu ditulis, ini adalah default

    /**
     * Atribut yang dapat diisi secara massal (mass assignable).
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id_transaksi',
        'nama',           
        'nomor_telepon', 
        'jumlah_orang',  
        'tanggal',
        'waktu',         
        'status',         
        'nomor_meja',     
        'nomor_ruangan'   
    ];

    
    /**
     * Tipe data (casts) untuk atribut model.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'tanggal' => 'date', // Ini akan mengubah 'tanggal' menjadi objek Carbon

        // =======================================================
        // PERBAIKAN DI SINI
        // =======================================================
        //
        // Kode Lama:
        // 'waktu' => 'datetime:H:i:s', 
        // 'status' => 'boolean' 
        //
        // Kode Baru:
        // 'waktu' kita biarkan sebagai string (sesuai tipe kolom TIME di DB).
        // 'status' diubah menjadi string.
        
        'status' => 'string' 
    ];

     // ==========================================================
    // TAMBAHAN BARU (MASALAH #3)
    // ==========================================================
    /**
     * Mendapatkan produk-produk yang dipesan dalam reservasi ini.
     */
    public function products()
    {
        // 'reservation_product' = Nama tabel pivot
        // 'reservation_id' = Foreign key untuk model ini
        // 'product_id' = Foreign key untuk model 'Product'
        return $this->belongsToMany(Product::class, 'reservation_product', 'reservation_id', 'product_id')
                    ->withPivot('quantity', 'price'); // Ambil data 'quantity' & 'price'
    }
}