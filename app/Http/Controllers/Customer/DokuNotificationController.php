<?php

namespace App\Http\Controllers\Customer;

use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Helpers\DokuSignatureHelper; // <-- Pastikan 'use' ini ada
use App\Http\Controllers\Controller;

class DokuNotificationController extends Controller
{
    /**
     * Menangani notifikasi webhook yang masuk dari DOKU.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function handle(Request $request): JsonResponse
    {
        // 1. Log MENTAH
        Log::info('DOKU Notification Received', [
            'headers' => $request->headers->all(),
            'body' => $request->getContent()
        ]);

        // 2. Validasi Signature (KEAMANAN)
        // (Sekarang menggunakan helper yang sudah diperbarui)
        if (!DokuSignatureHelper::validate($request)) {
            Log::warning('DOKU Signature Validation FAILED', [
                'ip' => $request->ip()
            ]);
            return response()->json(['status' => 'FORBIDDEN_INVALID_SIGNATURE'], 403);
        }

        // --- Signature Valid: Lanjutkan ke Logika Bisnis ---

        $data = $request->json()->all();

        // 3. Ambil Data Kunci dari JSON
        try {
            // Sesuaikan path ini jika DOKU mengirim struktur yang berbeda
            $invoiceNumber = $data['order']['invoice_number'];
            $paymentStatus = $data['transaction']['status']; 
        } catch (\Exception $e) {
            Log::error('DOKU Notification: Invalid JSON structure', ['body' => $data]);
            return response()->json(['status' => 'OK_INVALID_STRUCTURE']);
        }


        // 4. Proses Transaksi Database
        try {
            DB::beginTransaction();

            $reservation = Reservation::where('id_transaksi', $invoiceNumber)
                                      ->lockForUpdate() // Kunci baris untuk keamanan
                                      ->first();

            // Kasus 1: Reservasi tidak ditemukan
            if (!$reservation) {
                Log::warning('DOKU Notification: Reservation not found', ['invoice' => $invoiceNumber]);
                DB::commit(); 
                return response()->json(['status' => 'OK_NOT_FOUND']);
            }

            // Kasus 2: Reservasi sudah diproses (Idempotency)
            if ($reservation->status !== 'PENDING') {
                Log::info('DOKU Notification: Reservation already processed', ['invoice' => $invoiceNumber]);
                DB::commit();
                return response()->json(['status' => 'OK_ALREADY_PROCESSED']);
            }

            // [PERBAIKAN] Kasus 3: Logika Bisnis Utama (Sesuai Aturan Checkout)
            if ($paymentStatus === 'SUCCESS') {
                
                $reservation->status = 'akan datang'; // Sesuai permintaan Anda
                $reservation->save();
                DB::commit();

                Log::info('DOKU Notification: Processed successfully', ['invoice' => $invoiceNumber, 'new_status' => $reservation->status]);
                // Kirim 200 OK agar DOKU berhenti
                return response()->json(['status' => 'OK_SUCCESS']);
            
            } else {
                
                // [PERBAIKAN] Jika status 'FAILED', 'EXPIRED', 'CANCELLED', dll.
                // Sesuai Best Practice DOKU Checkout, kita ABAIKAN.
                // Biarkan status tetap 'PENDING' di DB.
                // Biarkan Task Scheduler Anda yang menanganinya nanti.
                
                DB::commit(); // Tidak ada perubahan, tapi tutup transaksi.
                
                Log::info('DOKU Notification: Ignoring non-SUCCESS status', [
                    'invoice' => $invoiceNumber, 
                    'status_from_doku' => $paymentStatus
                ]);
                // Kita tetap kirim 200 OK agar DOKU berhenti
                return response()->json(['status' => 'OK_IGNORED_NON_SUCCESS']);
            }

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('DOKU Notification: Database processing failed', [
                'error' => $e->getMessage(),
                'invoice' => $invoiceNumber
            ]);
            
            // Kirim 500. DOKU akan mencoba mengirim notifikasi ini lagi.
            return response()->json(['status' => 'ERROR_PROCESSING'], 500);
        }
    }
}