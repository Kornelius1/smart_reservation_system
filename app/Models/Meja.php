<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Meja extends Model
{
    use HasFactory;

    protected $table = 'meja';

    protected $fillable = [
        'nomor_meja',
        'kapasitas',
        'lokasi',
        'status_aktif'
    ];

    protected $casts = [
        'status_aktif' => 'boolean',
    ];

    // Scope untuk cari meja berdasarkan nomor atau lokasi
    public function scopeSearch($query, $term)
    {
        return $query->where('nomor_meja', 'like', "%{$term}%")
                    ->orWhere('lokasi', 'like', "%{$term}%");
    }

    // Scope untuk meja aktif
    public function scopeAktif($query)
    {
        return $query->where('status_aktif', true);
    }

    // Accessor untuk status dalam bahasa Indonesia
    public function getStatusAttribute()
    {
        return $this->status_aktif ? 'Tersedia' : 'Tidak Tersedia';
    }

    // Accessor untuk kapasitas dengan format
    public function getKapasitasFormatAttribute()
    {
        return $this->kapasitas . ' Orang';
    }
}