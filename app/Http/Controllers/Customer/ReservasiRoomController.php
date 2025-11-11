<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Room;

class ReservasiRoomController extends Controller
{
 
    public function index() 
    {
        $rooms = Room::all(); 
        return view('customer.reservasi-ruangan', ['rooms' => $rooms]);
    }
}