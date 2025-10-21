<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BayarController extends Controller
{
    // Method untuk simulasi pembayaran
    public function bayar(Request $request)
    {
        $amount = $request->input('amount', 0);

        if ($amount <= 0) {
            return response()->json([
                'status' => 'error',
                'message' => 'Jumlah pembayaran tidak valid'
            ], 400);
        }

        // Simulasi pembayaran sukses
        return response()->json([
            'status' => 'success',
            'message' => 'Pembayaran berhasil',
            'amount' => $amount
        ]);
    }

    // Method untuk cek status pembayaran
    public function cekStatus($id)
    {
        // Simulasi status selalu sukses
        return response()->json([
            'status' => 'success',
            'payment_id' => $id,
            'message' => 'Pembayaran terkonfirmasi'
        ]);
    }
}
