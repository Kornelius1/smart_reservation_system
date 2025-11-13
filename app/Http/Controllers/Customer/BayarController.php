<?php

namespace App\Http\Controllers\Customer;

// Pengurutan 'use' statement Anda sudah bagus
use App\Models\Meja;
use App\Models\Room;
use App\Models\Product;
use App\Models\Reservation;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Helpers\DokuSignatureHelper;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Validator;

class BayarController extends Controller
{
    private const MINIMUM_ORDER_FOR_TABLE = 50000;

    /**
     * 🧾 Menampilkan halaman pembayaran (checkout)
     */
    public function show(Request $request)
    {

        $validationResult = $this->validateOrder($request);

        if ($validationResult instanceof RedirectResponse) {
            return $validationResult;
        }

        $products = $validationResult['products'];
        $itemsFromRequest = $validationResult['items'];
        $totalPrice = $validationResult['totalPrice'];
        $reservationType = $validationResult['reservationType'];
        $reservationDetail = $validationResult['reservationDetail'];

        $cartItems = [];
        foreach ($products as $product) {
            $quantity = $itemsFromRequest[$product->id];
            $cartItems[] = [
                'id'       => $product->id,
                'name'     => $product->name,
                'price'    => $product->price,
                'quantity' => $quantity,
                'subtotal' => $product->price * $quantity,
            ];
        }

        $reservationData = [
            'type'   => $reservationType,
            'detail' => $reservationDetail,
        ];

        return view('customer.BayarReservasi', [
            'cartItems'   => $cartItems,
            'totalPrice'  => $totalPrice,
            'reservationDetails' => $reservationData,
        ]);
    }


    /**
     * 🚀 Memproses pembayaran dan membuat invoice DOKU (via AJAX)
     * Ini adalah method yang dipanggil oleh route('doku.createPayment')
     */
    public function processPayment(Request $request): JsonResponse // Pastikan return type adalah JsonResponse
    {
        // 1. Validasi Keamanan (Harus selalu ada)
        // Gunakan logika 'validateOrder' Anda
        $validationResult = $this->validateOrderForPayment($request); 

        if ($validationResult instanceof RedirectResponse) {
            // Jika validasi gagal, kembalikan error JSON
            return response()->json([
                'message' => 'Data pesanan tidak valid atau tidak memenuhi syarat.'
            ], 422); // 422 Unprocessable Entity
        }

        // Ambil data pelanggan dari request (setelah lolos HTML5 validation)
        $customerData = $request->validate([
            'nama' => 'required|string|min:3',
            'nomor_telepon' => 'required|string|regex:/^08[0-9]{8,12}$/',
            'email' => 'required|email',
            'jumlah_orang' => 'required|integer|min:1',
            'tanggal' => 'required|date|after_or_equal:today',
            'waktu' => 'required|date_format:H:i',
        ]);
        
        $totalPrice = $validationResult['totalPrice'];
        $invoiceNumber = 'INV-' . time() . Str::random(5);

        // --- MULAI LOGIKA DOKU ---
        DB::beginTransaction();
        try {
            // 2. Buat reservasi di DB (status 'pending')
            $reservation = Reservation::create([
                'invoice_number' => $invoiceNumber,
                'total_price' => $totalPrice,
                'status' => 'PENDING',
                'customer_name' => $customerData['nama'],
                'customer_email' => $customerData['email'],
                'customer_phone' => $customerData['nomor_telepon'],
                // ... (simpan meja_id, room_id, dll dari $validationResult) ...
            ]);

            // 3. Siapkan Body untuk DOKU
            $requestBody = [
                'order' => [
                    'amount' => $totalPrice,
                    'invoice_number' => $invoiceNumber,
                    // 'callback_url' => route('doku.notification') // PENTING untuk notifikasi
                ],
                'customer' => [
                    'name' => $customerData['nama'],
                    'email' => $customerData['email'],
                    'phone' => $customerData['nomor_telepon'],
                ]
                // ... (data lain sesuai kebutuhan DOKU) ...
            ];

            // 4. Generate Signature (Memanggil Helper Anda)
            // (Ini adalah Pseude-code, sesuaikan dengan helper Anda)
            $signatureResult = DokuSignatureHelper::generate($requestBody);

            // 5. Hit API DOKU
            $response = Http::withHeaders([
                'Client-Id' => $signatureResult['client_id'],
                'Request-Id' => $signatureResult['request_id'],
                'Request-Timestamp' => $signatureResult['timestamp'],
                'Signature' => $signatureResult['signature'],
            ])->post(config('doku.base_url') . '/checkout/v1/payment', $requestBody);

            if (!$response->successful()) {
                throw new \Exception('Gagal menghubungi DOKU: ' . $response->body());
            }

            $paymentUrl = $response->json('payment.url');
            
            // 6. Update reservasi dengan payment_url & Commit DB
            $reservation->payment_url = $paymentUrl;
            $reservation->save();
            DB::commit();

            // 7. Sukses! Kembalikan URL ke Frontend
            return response()->json([
                'payment_url' => $paymentUrl
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            // Error validasi data pelanggan
            DB::rollBack();
            return response()->json([
                'message' => 'Data pemesan tidak valid.',
                'errors' => $e->errors(),
            ], 422);
            } catch (\Exception $e) {
                // Error DOKU atau Database
                DB::rollBack();
                Log::error('DOKU Payment Error: ' . $e->getMessage());
                
                // KODE YANG SUDAH DIPERBAIKI:
                return response()->json([
                'message' => 'Terjadi kesalahan internal. Silakan coba beberapa saat lagi.'
            ], 500); // 500 Internal Server Error
        }
    }


    private function validateOrder(Request $request)
    {
        
        $itemsFromRequest = $request->input('items', []);
        $roomName = trim($request->input('reservation_room_name'));
        $tableNumber = trim($request->input('reservation_table_number'));

        if (empty($itemsFromRequest)) {
            return redirect('/pesanmenu')->withErrors(['msg' => 'Keranjang kosong!']);
        }

        $productIds = array_keys($itemsFromRequest);
        $products = Product::findMany($productIds);
        $totalPrice = 0;
        foreach ($products as $product) {
            $totalPrice += $product->price * $itemsFromRequest[$product->id];
        }

            // Validasi 2: Belum Pilih Reservasi
        if (empty($roomName) && empty($tableNumber)) {
            // Ini adalah skenario Anda!
            // Kirim mereka kembali ke halaman pilih reservasi, beri tahu alasannya.
            return redirect('/pilih-reservasi')
                ->with('show_alert_error', 'Anda harus memilih meja atau ruangan terlebih dahulu.');
        }

        if ($roomName) {
            $room = Room::where('nama_ruangan', $roomName)->first();
            if (!$room) return redirect('/pesanmenu')->withErrors(['msg' => 'Ruangan tidak valid.']);
            if ($totalPrice < $room->minimum_order) {
                return redirect('/pesanmenu')->withErrors(['msg' => 'Belanja belum memenuhi minimal ruangan.']);
            }
            return [
                'products' => $products,
                'items' => $itemsFromRequest,
                'totalPrice' => $totalPrice,
                'reservationType' => 'ruangan',
                'reservationDetail' => $roomName,
                'reservationFkId' => $room->id,
            ];
        }

        if ($tableNumber) {
            $meja = Meja::where('nomor_meja', $tableNumber)->where('status_aktif', true)->first();
            if (!$meja) return redirect('/pilih-meja')->withErrors(['msg' => 'Meja tidak tersedia.']);
            if ($totalPrice < self::MINIMUM_ORDER_FOR_TABLE) {
                return redirect('/pesanmenu')->withErrors(['msg' => 'Belanja belum memenuhi minimal meja.']);
            }
            return [
                'products' => $products,
                'items' => $itemsFromRequest,
                'totalPrice' => $totalPrice,
                'reservationType' => 'meja',
                'reservationDetail' => $tableNumber,
                'reservationFkId' => $meja->id,
            ];
        }

        return redirect('/pilih-reservasi')->withErrors(['msg' => 'Pilih jenis reservasi.']);
    }
} 