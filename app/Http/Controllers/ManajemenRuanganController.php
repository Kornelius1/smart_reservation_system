<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ManajemenRuangan;

class ManajemenRuanganController extends Controller
{
    public function index()
    {
        $rooms = ManajemenRuangan::all();
        return view('manajemen-ruangan.index', compact('rooms'));
    }

    public function edit($id)
    {
        $room = ManajemenRuangan::findOrFail($id);
        return view('manajemen-ruangan.edit', compact('room'));
    }

    public function update(Request $request, $id)
    {
        $room = ManajemenRuangan::findOrFail($id);
        $room->update($request->all());
        return redirect()->route('manajemen-ruangan.index')->with('success', 'Data ruangan berhasil diperbarui!');
    }
}
