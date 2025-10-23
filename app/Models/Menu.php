<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;

    protected $table = 'products';

    protected $fillable = [
        'name',
        'price',
        'category',
        'image_url',
        'tersedia', // <-- PASTIKAN INI DITAMBAHKAN
    ];

    protected $casts = [
        'price'    => 'integer',
        'tersedia' => 'boolean', // <-- PASTIKAN INI DITAMBAHKAN
    ];

    // public $timestamps = false; // <-- Hapus komentar ini jika perlu
}