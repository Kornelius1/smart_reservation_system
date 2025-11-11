<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reservation;
use Illuminate\Support\Facades\DB;   // <-- Import DB facade
use Illuminate\Support\Facades\Log;  // <-- Import Log facade
use Throwable;                      // <-- Import Throwable untuk menangkap semua error

class XenditController extends Controller
{
    /**
     * Menangani notifikasi (webhook) dari Xendit.
     */
    public function handle(Request $request)
    {
        // 1. Verifikasi Callback Token
        if (!$this->isValidCallbackToken($request)) {
            Log::warning('Xendit Webhook: Invalid callback token received.');
            return response()->json(['message' => 'Invalid callback token'], 401);
        }

        $payload = $request->all();
        $externalId = $payload['external_id'] ?? null;
        
        // 2. Guard Clause: Cek data esensial
        if (!$externalId) {
            // Beri 200 OK agar Xendit berhenti, tapi log errornya
            Log::warning('Xendit Webhook: Received payload without external_id.', $payload);
            return response()->json(['message' => 'Webhook missing external_id'], 200);
        }

        // 3. Proses Logika Inti dengan Database Transaction
        try {
            // DB::transaction akan otomatis rollback jika ada Exception
            $message = DB::transaction(function () use ($externalId, $payload) {
                
                // Kunci baris data untuk mencegah "race condition"
                $reservation = Reservation::where('id_transaksi', $externalId)
                                        ->lockForUpdate() // <-- Penting!
                                        ->first();

                // 4. Guard Clause: Reservasi tidak ditemukan
                if (!$reservation) {
                    Log::info("Xendit Webhook: Reservation not found for external_id: $externalId");
                    return 'Reservation not found for this external_id.';
                }

                // 5. Guard Clause: Idempotency Check
                // Hanya proses jika status MASIH 'pending'
                if ($reservation->status !== 'pending') {
                    Log::info("Xendit Webhook: Reservation $externalId already processed (status: {$reservation->status}). Ignoring webhook.");
                    return 'Reservation status no longer pending, webhook ignored.';
                }

                // 6. Logika Utama dipisah ke method sendiri
                return $this->processReservationStatus($reservation, $payload);
            });

            // Jika transaction sukses, kirim 200 OK
            return response()->json(['message' => $message], 200);

        } catch (Throwable $e) {
            // 7. Penanganan Error
            // Jika terjadi error (DB mati, query salah, dll)
            Log::error("Xendit Webhook: Failed to process external_id $externalId", [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(), // <-- Detail error
                'payload' => $payload
            ]);
            
            // Kirim 500 agar Xendit MENCOBA LAGI
            return response()->json(['message' => 'Internal Server Error'], 500);
        }
    }

    /**
     * Memverifikasi token callback dari header.
     *
     * @param Request $request
     * @return bool
     */
    private function isValidCallbackToken(Request $request): bool
    {
        $serverToken = $request->header('x-callback-token');
        $localToken = config('xendit.callback_token');

        // Pastikan keduanya adalah string sebelum membandingkan
        if (!is_string($serverToken) || !is_string($localToken)) {
            return false;
        }

        // Gunakan hash_equals untuk perbandingan string yang aman (mencegah timing attack)
        return hash_equals($localToken, $serverToken);
    }

    /**
     * Memproses status reservasi berdasarkan payload.
     * Method ini dieksekusi di dalam DB Transaction.
     *
     * @param Reservation $reservation
     * @param array $payload
     * @return string Pesan untuk respons
     */
    private function processReservationStatus(Reservation $reservation, array $payload): string
    {
        // Ambil status dan pastikan lowercase, default string kosong jika null
        $status = strtolower($payload['status'] ?? '');
        $externalId = $reservation->id_transaksi; // Ambil dari model yang sudah ada

        switch ($status) {
            case 'paid':
                $reservation->update(['status' => 'akan datang']);
                Log::info("Xendit Webhook: Reservation $externalId updated to 'akan datang'.");
                return 'Payment successful, reservation updated.';

            case 'expired':
                $reservation->delete();
                Log::info("Xendit Webhook: Reservation $externalId deleted due to expiration.");
                return 'Invoice expired, reservation deleted.';

            default:
                // Status lain (misal: 'pending', 'settled') kita abaikan
                Log::info("Xendit Webhook: Reservation $externalId received status '$status', no action taken.");
                return 'Webhook received, no action needed for this status.';
        }
    }
}