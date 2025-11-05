<?php

namespace App\Http\Controllers\Customer;

use App\Models\Reservation;
use App\Models\Product;
use App\Models\Room;
use App\Models\Meja;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
// ==========================================================
// IMPORT BARU UNTUK DOKU
// ==========================================================
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

// ----------------------------------------------------------
// SEMUA 'USE XENDIT' SUDAH DIHAPUS
// ----------------------------------------------------------

class BayarController extends Controller
{
    /**
     * Menampilkan halaman konfirmasi (Tidak Berubah)
     */
    public function show(Request $request)
    {
        // ==========================================================
        // PERBAIKAN (dari history): Tangkap request GET
        // ==========================================================
        if ($request->isMethod('get')) {
            // Ini terjadi jika validasi di 'processPayment' gagal dan redirect back()
            return redirect('/pesanmenu') // Arahkan ke halaman menu
                ->withErrors(['msg' => 'Data tidak lengkap. Silakan ulangi pesanan Anda.']);
        }
        
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
                'id'     => $product->id,
                'name'     => $product->name,
                'price'    => $product->price,
                'quantity' => $quantity,
                'subtotal' => $product->price * $quantity,
            ];
        }
        $reservationData = [
            'type'   => $reservationType,
            'detail' => $reservationDetail
        ];

        return view('customer.BayarReservasi', [
            'cartItems'          => $cartItems,
            'totalPrice'         => $totalPrice,
            'reservationDetails' => $reservationData
        ]);
    }

    /**
     * Memproses data dan mengirim ke DOKU
     * Di-trigger oleh AJAX/Fetch dari BayarReservasi.blade.php
     * * @return JsonResponse
     */
    public function processPayment(Request $request): JsonResponse
    {
        // 1. VALIDASI PESANAN (Item & Reservasi)
        $validationResult = $this->validateOrder($request);
        
        // PERUBAHAN: Tangkap RedirectResponse dan ubah jadi JSON
        if ($validationResult instanceof RedirectResponse) {
            $errors = $validationResult->getSession()->get('errors');
            $errorMessage = $errors ? $errors->first() : 'Validasi pesanan gagal.';
            
            return response()->json([
                'success' => false,
                'message' => $errorMessage
            ], 422);
        }

        // 2. VALIDASI DATA CUSTOMER (dari Form)
        // PERUBAHAN: Gunakan Validator manual agar bisa return JSON
        $validator = Validator::make($request->all(), [
            'nama'          => 'required|string|max:255',
            'nomor_telepon' => 'required|string|regex:/^08[0-9]{8,12}$/',
            'email'         => 'required|email|max:255', // <-- Email ditambahkan (sesuai blade)
            'jumlah_orang'  => 'required|integer|min:1',
            'tanggal'       => 'required|date|after_or_equal:today',
            'waktu'         => 'required|date_format:H:i',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first() // Ambil pesan error pertama
            ], 422);
        }
        $customerData = $validator->validated(); // Ambil data yang sudah valid

        // Unpack data (Tidak berubah)
        $totalPrice = $validationResult['totalPrice'];
        $reservationType = $validationResult['reservationType'];
        $reservationFkId = $validationResult['reservationFkId'];
        $products = $validationResult['products'];
        $itemsFromRequest = $validationResult['items'];

        // 3. BUAT ID TRANSAKSI (Tidak berubah)
        $id_transaksi = 'HOMEY-' . now()->format('Ymd') . '-' . strtoupper(Str::random(6));

        // 4. SIMPAN RESERVASI 'PENDING' (Tidak berubah)
        $dataToSave = [
            'id_transaksi'  => $id_transaksi,
            'nama'          => $customerData['nama'],
            'nomor_telepon' => $customerData['nomor_telepon'],
            'email_customer'=> $customerData['email'], // <-- PERBAIKAN: Simpan email
            'jumlah_orang'  => $customerData['jumlah_orang'],
            'tanggal'       => $customerData['tanggal'],
            'waktu'         => $customerData['waktu'],
            'status'        => 'pending',
            'nomor_meja'    => ($reservationType === 'meja') ? $reservationFkId : null,
            'nomor_ruangan' => ($reservationType === 'ruangan') ? $reservationFkId : null,
        ];
        $reservation = Reservation::create($dataToSave);

        session(['last_transaction_id' => $id_transaksi]);

        // Simpan item ke pivot table (Tidak berubah)
        foreach ($products as $product) {
            $quantity = $itemsFromRequest[$product->id];
            $reservation->products()->attach($product->id, [
                'quantity' => $quantity,
                'price'    => $product->price 
            ]);
        }

        // ==========================================================
        // 5. PROSES DOKU (Menggantikan Xendit)
        // (Sesuai Fase 1, Langkah 6 dari doku_integration_guide.md)
        // ==========================================================
        try {
            // Siapkan variabel DOKU
            $clientId = config('services.doku.client_id');
            $secretKey = config('services.doku.secret_key');
            $apiUrl = config('services.doku.api_url');
            $path = '/checkout/v1/payment'; 

            // Siapkan data yang akan dikirim (Request Body)
            $body = [
                'order' => [
                    'invoice_number' => $id_transaksi, // Pakai ID Transaksi kita
                    'amount' => (int) $totalPrice      // Pastikan integer
                ],
                'payment' => [
                    'payment_due_date' => 60 // Waktu kadaluarsa (menit)
                ],
                'customer' => [
                    'name' => $customerData['nama'],  // Pakai data customer
                    'email' => $customerData['email'] // Pakai data customer
                ]
            ];

            // GENERATE SIGNATURE
            $requestTimestamp = now()->toIso8601String();
            $requestId = (string) Str::uuid();
            $digest = base64_encode(hash('sha256', json_encode($body), true));

            $stringToSign = "Client-Id:" . $clientId . "\n"
                          . "Request-Id:" . $requestId . "\n"
                          . "Request-Timestamp:" . $requestTimestamp . "\n"
                          . "Request-Target:" . $path . "\n"
                          . "Digest:" . $digest;

            $signature = base64_encode(hash_hmac('sha256', $stringToSign, $secretKey, true));

            // Kirim Request ke DOKU
            $response = Http::withHeaders([
                'Client-Id' => $clientId,
                'Request-Id' => $requestId,
                'Request-Timestamp' => $requestTimestamp,
                'Signature' => "HMACSHA256=" . $signature,
            ])
            ->withBody(json_encode($body), 'application/json')
            ->post($apiUrl . $path);

            $responseData = $response->json();

            // Cek jika sukses dan ada URL pembayaran
            if ($response->successful() && isset($responseData['response']['payment']['url'])) {
                
                // Kirim URL kembali ke Frontend (BayarReservasi.blade.php)
                return response()->json([
                    'success' => true,
                    'payment_url' => $responseData['response']['payment']['url']
                ]);
            }

            // Jika DOKU mengembalikan error
            Log::error('DOKU API Error', $responseData ?? ['message' => $response->body()]);
            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat pembayaran DOKU: ' . ($responseData['error']['message'] ?? 'Unknown Error'),
            ], 500);

        } catch (\Exception $e) {
            // Tangani error umum (koneksi, dll)
            Log::error('DOKU General Error: ' . $e->getMessage());
            return response()->json([
                'success' => false, 
                'message' => 'Terjadi kesalahan sistem: ' . $e->getMessage()
            ], 500);
        }
    }


    /**
     * Validasi Keranjang & Reservasi (Tidak Berubah)
     */
    private const MINIMUM_ORDER_FOR_TABLE = 50000;
    private function validateOrder(Request $request)
    {
        $itemsFromRequest = $request->input('items', []);
        $roomName = trim($request->input('reservation_room_name'));
        $tableNumber = trim($request->input('reservation_table_number'));

        if (empty($itemsFromRequest)) {
            return redirect('/pesanmenu')->withErrors(['msg' => 'Keranjang Anda kosong!']);
        }

        $productIds = array_keys($itemsFromRequest);
        $products = Product::findMany($productIds);
        $totalPrice = 0;
        foreach ($products as $product) {
            if ($product) {
                $totalPrice += $product->price * $itemsFromRequest[$product->id];
            }
        }

        $reservationType = null;
        $reservationDetail = null;
        $reservationFkId = null; 

        if ($roomName) {
            $room = Room::where('nama_ruangan', $roomName)->first();
            if (!$room) {
                return redirect('/pesanmenu')->withErrors(['msg' => 'Ruangan yang dipilih tidak valid.']);
            }
            if ($totalPrice < $room->minimum_order) {
                return redirect('/pesanmenu')->withErrors(['msg' => 'Total belanja tidak memenuhi syarat minimal pemesanan untuk ' . $room->nama_ruangan]);
            }
            $reservationType = 'ruangan';
            $reservationDetail = $roomName;
            $reservationFkId = $room->id;
        } elseif ($tableNumber) {
            $meja = Meja::where('nomor_meja', $tableNumber)->where('status_aktif', true)->first();
            if (!$meja) {
                return redirect('/pilih-meja')->withErrors(['msg' => 'Meja yang dipilih tidak valid atau tidak tersedia.']);
            }
            if ($totalPrice < self::MINIMUM_ORDER_FOR_TABLE) {
                return redirect('/pesanmenu')->withErrors(['msg' => 'Total belanja tidak memenuhi syarat minimal pemesanan meja (Rp ' . number_format(self::MINIMUM_ORDER_FOR_TABLE) . ')']);
            }
            $reservationType = 'meja';
            $reservationDetail = $tableNumber;
            $reservationFkId = $meja->id;
        } else {
            return redirect('/pilih-reservasi')->withErrors(['msg' => 'Silakan pilih jenis reservasi terlebih dahulu.']);
        }

        return [
            'products'          => $products,
            'items'             => $itemsFromRequest,
            'totalPrice'        => $totalPrice,
            'reservationType'   => $reservationType,
            'reservationDetail' => $reservationDetail,
            'reservationFkId'   => $reservationFkId,
        ];
    }
}