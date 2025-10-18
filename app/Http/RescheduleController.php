<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Room;
use App\Models\Reservation;
use Carbon\Carbon;

class RescheduleController extends Controller
{
    public function index()
    {
        $rooms = Room::with(['reservations' => function($q){
            $q->where('end_datetime', '>=', now())->orderBy('start_datetime');
        }])->get();

        // also get upcoming reservations for listing or quick edits
        $reservations = Reservation::with('room')->orderBy('start_datetime')->get();

        return view('reschedule.index', compact('rooms', 'reservations'));
    }

    // Show simple edit/reschedule form for a reservation
    public function edit(Reservation $reservation)
    {
        $rooms = Room::all();
        return view('reschedule.edit', compact('reservation', 'rooms'));
    }

    public function update(Request $request, Reservation $reservation)
    {
        $validated = $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'start_datetime' => 'required|date',
            'end_datetime' => 'required|date|after:start_datetime',
            'customer_name' => 'required|string|max:255',
            'notes' => 'nullable|string'
        ]);

        // optionally check conflicts
        $conflict = Reservation::where('room_id', $validated['room_id'])
            ->where('id', '!=', $reservation->id)
            ->where(function($q) use ($validated) {
                $q->whereBetween('start_datetime', [$validated['start_datetime'], $validated['end_datetime']])
                  ->orWhereBetween('end_datetime', [$validated['start_datetime'], $validated['end_datetime']])
                  ->orWhere(function($q2) use ($validated) {
                      $q2->where('start_datetime', '<=', $validated['start_datetime'])
                         ->where('end_datetime', '>=', $validated['end_datetime']);
                  });
            })->exists();

        if ($conflict) {
            return back()->withErrors(['conflict' => 'Waktu bentrok dengan reservasi lain.'])->withInput();
        }

        $reservation->update(array_merge($validated, ['status' => 'rescheduled']));

        return redirect()->route('reschedule.index')->with('success', 'Reservasi berhasil di-reschedule.');
    }

    // optional: create/reserve new
    public function store(Request $request)
    {
        $v = $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'customer_name' => 'required|string',
            'start_datetime' => 'required|date',
            'end_datetime' => 'required|date|after:start_datetime'
        ]);

        // simple conflict check (like above)
        $conflict = Reservation::where('room_id', $v['room_id'])
            ->where(function($q) use ($v) {
                $q->whereBetween('start_datetime', [$v['start_datetime'], $v['end_datetime']])
                  ->orWhereBetween('end_datetime', [$v['start_datetime'], $v['end_datetime']]);
            })->exists();

        if ($conflict) {
            return back()->withErrors(['conflict' => 'Waktu bentrok dengan reservasi lain.'])->withInput();
        }

        Reservation::create($v);

        return redirect()->route('reschedule.index')->with('success', 'Reservasi berhasil dibuat.');
    }

    // optional: show a reservation (not required)
    public function show(Reservation $reservation)
    {
        return view('reschedule.show', compact('reservation'));
    }
}
