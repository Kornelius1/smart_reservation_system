<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;

    protected $table = 'reservations'; 
    protected $primaryKey = 'id_reservasi'; 

    public $incrementing = true; 

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

    
    protected $casts = [
        'tanggal' => 'date', 
        'waktu' => 'datetime:H:i:s',
        'status' => 'boolean' 
    ];

    public $timestamps = true; 
}
