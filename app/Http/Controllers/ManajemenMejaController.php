<?php

namespace App\Http\Controllers;

use App\Models\Meja;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ManajemenMejaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $meja = Meja::orderBy('nomor_meja', 'asc')->get();
        return view('manajemen-meja.index', compact('meja'));
    }

   


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id): JsonResponse
    {
        $meja = Meja::findOrFail($id);

        $request->validate([
            'nomor_meja' => 'required|integer|unique:meja,nomor_meja,' . $id,
            'kapasitas' => 'required|integer|min:1',
            'lokasi' => 'required|string|max:255',
        ]);

        $meja->update([
            'nomor_meja' => $request->nomor_meja,
            'kapasitas' => $request->kapasitas,
            'lokasi' => $request->lokasi,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Meja berhasil diupdate',
            'data' => $meja
        ]);
    }

   

    /**
     * Search meja
     */
    public function search(Request $request): JsonResponse
    {
        $search = $request->get('search');
        $meja = Meja::search($search)->orderBy('nomor_meja', 'asc')->get();

        $html = view('manajemen-meja.partials.table-rows', compact('meja'))->render();

        return response()->json([
            'success' => true,
            'html' => $html
        ]);
    }

    /**
     * Toggle status meja
     */
    public function toggleStatus($id): JsonResponse
    {
        $meja = Meja::findOrFail($id);
        $meja->update(['status_aktif' => !$meja->status_aktif]);

        return response()->json([
            'success' => true,
            'message' => 'Status meja berhasil diubah',
            'status_aktif' => $meja->status_aktif
        ]);
    }
}