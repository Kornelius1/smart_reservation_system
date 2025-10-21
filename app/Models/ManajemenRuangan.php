<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ManajemenRuangan extends Model
{
    use HasFactory;

    protected $table = 'manajemen_ruangan';
    protected $primaryKey = 'id_ruangan';
    protected $fillable = [
        'nama_ruangan',
        'kapasitas',
        'lokasi',
        'fasilitas',
        'keterangan',
        'status',
    ];
}
