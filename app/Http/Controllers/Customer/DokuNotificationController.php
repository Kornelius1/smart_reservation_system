<?php

namespace App\Http\Controllers\Customer;

use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Helpers\DokuSignatureHelper;
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
        // 1. Log MENTAH (Sangat Penting untuk Debugging)
        // Kita log *sebelum* validasi, untuk melihat apa yang sebenarnya dikirim DOKU.
        Log::info('DOKU Notification Received', [
            'headers' => $request->headers->all(),
            'body' => $request->getContent()
        ]);

        // 2. Validasi Signature (KEAMANAN)
        // Kita panggil method 'validate' baru dari helper kita.
        if (!DokuSignatureHelper::validate($request)) {
            Log::warning('DOKU Signature Validation FAILED', [
                'ip' => $request->ip()
            ]);
            // Tolak permintaan yang tidak valid.
            return response()->json(['status' => 'FORBIDDEN'], 403);
        }

        // --- Signature Valid: Lanjutkan ke Logika Bisnis ---

        $data = $request->json()->all();

        // 3. Ambil Data Kunci dari JSON
        // PERHATIAN: Sesuaikan path ini jika DOKU mengirim struktur yang berbeda
        try {
            $invoiceNumber = $data['order']['invoice_number'];
            $paymentStatus = $data['transaction']['status']; // mis. 'SUCCESS', 'FAILED'
        } catch (\Exception $e) {
            Log::error('DOKU Notification: Invalid JSON structure', ['body' => $data]);
            // Kita tetap kirim 200 OK agar DOKU berhenti retrying.
            return response()->json(['status' => 'OK_INVALID_STRUCTURE']);
        }


        // 4. Proses Transaksi Database
        try {
            DB::beginTransaction();

            // Kunci reservasi ini untuk mencegah 'race condition'
            $reservation = Reservation::where('id_transaksi', $invoiceNumber)
                                      ->lockForUpdate()
                                      ->first();

            // Kasus 1: Reservasi tidak ditemukan
            if (!$reservation) {
                Log::warning('DOKU Notification: Reservation not found', ['invoice' => $invoiceNumber]);
                DB::commit(); // Tidak ada yang perlu dilakukan, tapi anggap sukses
                return response()->json(['status' => 'OK_NOT_FOUND']);
            }

            // Kasus 2: Reservasi sudah diproses (Idempotency)
            // Jika statusnya BUKAN 'PENDING', berarti kita sudah memproses notifikasi sebelumnya.
            if ($reservation->status !== 'PENDING') {
                Log::info('DOKU Notification: Reservation already processed', ['invoice' => $invoiceNumber]);
                DB::commit();
                return response()->json(['status' => 'OK_ALREADY_PROCESSED']);
            }

            // Kasus 3: Logika Bisnis Utama
            if ($paymentStatus === 'SUCCESS') {
                $reservation->status = 'Akan Datang'; // Sesuai permintaan Anda
                // Anda juga bisa menyimpan data lain di sini jika perlu
                // $reservation->payment_token = $data['payment']['token_id'];
            } else {
                // mis. 'EXPIRED', 'FAILED', 'CANCELLED'
                $reservation->status = 'Gagal'; // Atau 'Dibatalkan', 'Kadaluarsa'
            }

            $reservation->save();
            DB::commit();

            Log::info('DOKU Notification: Processed successfully', ['invoice' => $invoiceNumber, 'new_status' => $reservation->status]);

            // Kirim 200 OK untuk memberitahu DOKU agar berhenti mengirim
            return response()->json(['status' => 'OK']);

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