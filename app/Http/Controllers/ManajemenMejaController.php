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
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'nomor_meja' => 'required|integer|unique:meja,nomor_meja',
            'kapasitas' => 'required|integer|min:1',
            'lokasi' => 'required|string|max:255',
        ]);

        $meja = Meja::create([
            'nomor_meja' => $request->nomor_meja,
            'kapasitas' => $request->kapasitas,
            'lokasi' => $request->lokasi,
            'status_aktif' => true
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Meja berhasil ditambahkan',
            'data' => $meja
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): JsonResponse
    {
        $meja = Meja::findOrFail($id);
        
        return response()->json([
            'success' => true,
            'data' => $meja
        ]);
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
     * Remove the specified resource from storage.
     */
    public function destroy($id): JsonResponse
    {
        $meja = Meja::findOrFail($id);
        $meja->delete();

        return response()->json([
            'success' => true,
            'message' => 'Meja berhasil dihapus'
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