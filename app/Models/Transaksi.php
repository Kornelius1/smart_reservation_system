<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    use HasFactory;

    protected $table = 'transaksi'; // atau 'transaksis' jika pakai default Laravel

    protected $fillable = [
        'id_transaksi',
        'tanggal',
        'waktu',
        'nama_customer',
        'nomor_telepon',
        'jumlah_orang',
        'total_pembayaran',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'total_pembayaran' => 'decimal:2',
    ];
}