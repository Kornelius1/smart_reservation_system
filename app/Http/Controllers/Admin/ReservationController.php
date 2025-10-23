<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\Reservation; 

class ReservationController extends Controller
{
    
    public function index(): View
    {
       
        $reservations = Reservation::all();

      
        return view('admin.manajemen-reservasi', ['reservations' => $reservations]);
    }
}