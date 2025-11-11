<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\View\View;
use App\Models\Meja;
use Illuminate\Http\JsonResponse; 

class TableController extends Controller
{
    public function index(): View
    {
        $mejas = Meja::orderBy('nomor_meja')->get();
        return view('admin.manajemen-meja', ['tables' => $mejas]);
    }

    /**
     * @param Meja $meja 
     * @return JsonResponse
     */
    public function toggleStatus(Meja $meja): JsonResponse
    {
        try {
            $meja->status_aktif = !$meja->status_aktif;
            $meja->save();

            return response()->json([
                'success' => true,
                'newStatus' => $meja->status_aktif,
                'statusText' => $meja->status 
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}