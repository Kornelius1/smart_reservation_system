<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'capacity',
        'min_order',
        'extra_time_price',
        'image',
        'description'
    ];

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }
}
