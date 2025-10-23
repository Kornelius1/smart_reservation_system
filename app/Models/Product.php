<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name',
        'price',
        'image_url',
        'category',
        'tersedia',
    ];

    protected $casts = [
        'price' => 'integer',
        'tersedia' => 'boolean',
    ];
}