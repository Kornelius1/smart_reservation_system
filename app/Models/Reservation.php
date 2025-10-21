<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang terhubung dengan model ini.
     * (Opsional jika nama tabel Anda 'reservations')
     */
    protected $table = 'reservations';

    /**
     * Primary key dari tabel.
     * (Opsional jika 'id' dan auto-increment)
     */
    // protected $primaryKey = 'id';

    /**
     * Menentukan apakah primary key-nya auto-increment.
     * (Penting jika Anda menggunakan 'id_transaksi' sebagai primary key)
     */
    // public $incrementing = false;

    /**
     * Tipe data dari primary key.
     * (Penting jika primary key Anda bukan integer, misal: 'TRS001')
     */
    // protected $keyType = 'string';


    /**
     * Kolom yang BOLEH diisi secara massal (mass assignable).
     * Ini penting untuk keamanan.
     */
    protected $fillable = [
        'id_transaksi',
        'nama', // <--- Asumsi dari data dummy Anda
        'tanggal',
        'waktu',
        // tambahkan kolom lain yang relevan di sini
    ];

    /**
     * Mengubah tipe data kolom secara otomatis (casting).
     * Sangat berguna untuk tanggal dan jam!
     */
    protected $casts = [
        'tanggal' => 'date', // Otomatis mengubah '2025-11-20' menjadi objek Carbon
        'waktu' => 'datetime:H:i', // Menangani format jam
    ];

    /**
     * Menentukan apakah model harus memiliki timestamp (created_at & updated_at).
     * (Jika tabel Anda tidak punya kolom ini, set ke false)
     */
    public $timestamps = true; // atau false
}