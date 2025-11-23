<?php

namespace App\Http\Controllers\Customer;

use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Helpers\DokuSignatureHelper; 
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail; 
use App\Mail\PaymentReceiptMail;     
use Carbon\Carbon; 


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
        // (Menggunakan helper "pintar" v2.1 kita yang sudah benar)
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
            $invoiceNumber = $data['order']['invoice_number'];
            $paymentStatus = $data['transaction']['status']; 
        } catch (\Exception $e) {
            Log::error('DOKU Notification: Invalid JSON structure', ['body' => $data]);
            return response()->json(['status' => 'OK_INVALID_STRUCTURE']);
        }

        // 4. [PERBAIKAN] Proses Transaksi Database (Logika Baru Anda)
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

            // Kasus 2: Hanya proses jika DOKU bilang 'SUCCESS'
            if ($paymentStatus !== 'SUCCESS') {
                DB::commit();
                Log::info('DOKU Notification: Ignoring non-SUCCESS status', [
                    'invoice' => $invoiceNumber, 
                    'status_from_doku' => $paymentStatus
                ]);
                return response()->json(['status' => 'OK_IGNORED_NON_SUCCESS']);
            }

            // Kasus 3: [PERBAIKAN] Idempotency Check (Pemeriksaan Duplikat)
            // Kita HANYA berhenti jika statusnya SUDAH lunas.
            // Kita HARUS melanjutkan jika statusnya 'pending' ATAU 'kedaluwarsa'.
            $processedStatuses = ['akan datang', 'check-in', 'selesai'];
            if (in_array($reservation->status, $processedStatuses)) {
                Log::info('DOKU Notification: Reservation already processed (Idempotent)', ['invoice' => $invoiceNumber]);
                DB::commit();
                return response()->json(['status' => 'OK_ALREADY_PROCESSED']);
            }

            // Kasus 4: [LOGIKA BARU ANDA] Tentukan status baru
            // Jika kita sampai di sini, artinya status DOKU 'SUCCESS'
            // dan status kita saat ini 'pending' ATAU 'kedaluwarsa'.
            // Kita akan MENIMPANYA (OVERWRITE).

            $newStatus = '';
            $today = Carbon::now()->toDateString();
            
            // Ambil tanggal reservasi (sudah di-cast sebagai objek 'date' oleh model)
            $reservationDate = $reservation->tanggal->toDateString(); 

            if ($today === $reservationDate) {
                // Sesuai permintaan Anda: "statusnya berlangsung"
                $newStatus = 'check-in'; 
            } else {
                // Sesuai permintaan Anda: "menjadi akan datang"
                $newStatus = 'akan datang'; 
            }

            $reservation->status = $newStatus;
            $reservation->save();

            if (!$reservation->receipt_sent) {
                try {
                    // Menggunakan email_customer sesuai database kamu
                    Mail::to($reservation->email_customer)->send(new PaymentReceiptMail($reservation));
                    
                    // Tandai sudah terkirim di database
                    $reservation->receipt_sent = true; 
                    $reservation->save();

                    Log::info('Email receipt sent via Webhook', ['invoice' => $invoiceNumber]);
                } catch (\Exception $e) {
                    // Log error saja, jangan batalkan transaksi utama
                    Log::error('Failed to send email receipt', ['error' => $e->getMessage()]);
                }
            }

            DB::commit();

            Log::info('DOKU Notification: Processed successfully (Overwrote status)', [
                'invoice' => $invoiceNumber, 
                'new_status' => $newStatus
            ]);

            // Kirim 200 OK untuk memberitahu DOKU agar berhenti mengirim
            return response()->json(['status' => 'OK_SUCCESS']);

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