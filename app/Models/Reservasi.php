<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'room_id',
        'customer_name',
        'start_datetime',
        'end_datetime',
        'status', // booked, cancelled, rescheduled
        'notes'
    ];

    protected $dates = [
        'start_datetime',
        'end_datetime'
    ];

    public function room()
    {
        return $this->belongsTo(Room::class);
    }
}
