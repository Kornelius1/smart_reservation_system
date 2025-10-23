<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Room;

class ManajemenRuanganController extends Controller
{
    public function index()
    {
        $rooms = Room::all();
        return view('admin.manajemen-ruangan', compact('rooms'));
    }

    public function edit($id)
    {
        $room = Room::findOrFail($id);
        return view('admin.manajemen-ruangan.edit', compact('room'));
    }

    public function update(Request $request, $id)
    {
        // Gunakan Model 'Room'
        $room = Room::findOrFail($id);
        
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'minimum_order' => 'required|integer|min:0',
        ]);

        $room->update($validatedData);

        return redirect()->route('admin.manajemen-ruangan.index')->with('success', 'Data ruangan berhasil diperbarui!');
    }
}
