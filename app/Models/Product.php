<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'price',
        'stock',
        'image_url',
        'category',
        'tersedia',
    ];

    protected $casts = [
        'price' => 'decimal:2', 
        'stock' => 'integer',
        'tersedia' => 'boolean',
    ];
}