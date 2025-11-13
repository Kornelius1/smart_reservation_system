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

    /**
     * Validasi data yang disubmit untuk *diproses* ke pembayaran.
     * HARUS KETAT dan bisa mengembalikan redirect DENGAN error
     * (untuk menangani manipulasi data).
     *
     * @param Request $request
     * @return array|RedirectResponse
     */
    private function validateOrderForPayment(Request $request)
    {
        // Ambil data (sama seperti fungsi 'show')
        $itemsFromRequest = $request->input('items', []);
        $roomName = trim($request->input('reservation_room_name'));
        $tableNumber = trim($request->input('reservation_table_number'));

        // 1. Validasi Keranjang Kosong (Harus ada error di sini)
        if (empty($itemsFromRequest)) {
            // Ini seharusnya tidak terjadi jika JS berjalan,
            // jadi jika terjadi, ini adalah masalah keamanan/manipulasi.
            return redirect('/pesanmenu')->withErrors(['msg' => 'Keranjang kosong!']);
        }

        $productIds = array_keys($itemsFromRequest);
        $products = Product::findMany($productIds);
        $totalPrice = 0;
        foreach ($products as $product) {
            $totalPrice += $product->price * $itemsFromRequest[$product->id];
        }

        // 2. Validasi untuk Reservasi Ruangan
        if ($roomName) {
            $room = Room::where('nama_ruangan', $roomName)->first();

            // Jika ruangan tidak valid atau minimal order tidak terpenuhi
            if (!$room) {
                 return redirect('/pesanmenu')->withErrors(['msg' => 'Ruangan tidak valid.']);
            }
            if ($totalPrice < $room->minimum_order) {
                 return redirect('/pesanmenu')->withErrors(['msg' => 'Belanja belum memenuhi minimal ruangan.']);
            }

            // Sukses validasi ruangan
            return [
                'products' => $products,
                'items' => $itemsFromRequest,
                'totalPrice' => $totalPrice,
                'reservationType' => 'ruangan',
                'reservationDetail' => $roomName,
                'reservationFkId' => $room->id,
            ];
        }

        // 3. Validasi untuk Reservasi Meja
        if ($tableNumber) {
            $meja = Meja::where('nomor_meja', $tableNumber)->where('status_aktif', true)->first();

            // Jika meja tidak tersedia
            if (!$meja) {
                return redirect('/pilih-meja')->withErrors(['msg' => 'Meja tidak tersedia.']);
            }
            
            // Jika minimal order tidak terpenuhi
            if ($totalPrice < self::MINIMUM_ORDER_FOR_TABLE) {
                return redirect('/pesanmenu')->withErrors(['msg' => 'Belanja belum memenuhi minimal meja.']);
            }

            // Sukses validasi meja
            return [
                'products' => $products,
                'items' => $itemsFromRequest,
                'totalPrice' => $totalPrice,
                'reservationType' => 'meja',
                'reservationDetail' => $tableNumber,
                'reservationFkId' => $meja->id,
            ];
        }

        // 4. Jika tidak ada reservasi yang dipilih
        return redirect('/pilih-reservasi')->withErrors(['msg' => 'Pilih jenis reservasi.']);
    }

/**
     * 🚀 Memproses pembayaran dan membuat invoice DOKU (via AJAX)
     * Ini adalah method yang dipanggil oleh route('doku.createPayment')
     * * [VERSI INI SUDAH DISESUAIKAN DENGAN SKEMA DB ANDA]
     */

    public function processPayment(Request $request): JsonResponse
    {
        // 1. Validasi Keamanan (Benteng Backend)
        $validationResult = $this->validateOrderForPayment($request);
        if ($validationResult instanceof RedirectResponse) {
            return response()->json(['message' => 'Data pesanan tidak valid atau tidak memenuhi syarat.'], 422);
        }

        // 2. Validasi Data Pemesan
        $customerData = $request->validate([
            'nama' => 'required|string|min:3',
            'nomor_telepon' => 'required|string|regex:/^08[0-9]{8,12}$/',
            'email' => 'required|email',
            'jumlah_orang' => 'required|integer|min:1',
            'tanggal' => 'required|date|after_or_equal:today',
            'waktu' => 'required|date_format:H:i',
        ]);
        
        // 3. Siapkan Data Internal
        $totalPrice = $validationResult['totalPrice'];
        $invoiceNumber = 'INV-' . time() . Str::random(5);

        // --- MULAI TRANSAKSI DATABASE DAN LOGIKA DOKU ---
        DB::beginTransaction();
        try {
            // 4. Siapkan data untuk tabel 'reservations'
            $reservationData = [
                'id_transaksi' => $invoiceNumber,
                'total_price' => $totalPrice, 
                'status' => 'PENDING',
                'nama' => $customerData['nama'], // Simpan data MENTAH di DB Anda
                'email_customer' => $customerData['email'],
                'nomor_telepon' => $customerData['nomor_telepon'],
                'jumlah_orang' => $customerData['jumlah_orang'],
                'tanggal' => $customerData['tanggal'],
                'waktu' => $customerData['waktu'] . ':00',
            ];
            
            if ($validationResult['reservationType'] === 'meja') {
                $reservationData['nomor_meja'] = $validationResult['reservationFkId'];
            } elseif ($validationResult['reservationType'] === 'ruangan') {
                $reservationData['nomor_ruangan'] = $validationResult['reservationFkId'];
            }

            // 5. Buat reservasi di DB
            $reservation = Reservation::create($reservationData);
            
            // 6. [PERBAIKAN] Siapkan 'line_items' DENGAN SANITASI
            $lineItems = [];
            $products = $validationResult['products'];
            $itemsFromRequest = $validationResult['items'];
            foreach ($products as $product) {
                $lineItems[] = [
                    'name' => $this->sanitizeForDoku($product->name), // <-- DIBERSIHKAN
                    'price' => (int) $product->price,
                    'quantity' => (int) $itemsFromRequest[$product->id]
                ];
            }
            
            // Konversi nomor telepon
            $customerPhone = $customerData['nomor_telepon'];
            if (str_starts_with($customerPhone, '08')) {
                $customerPhone = '+62' . substr($customerPhone, 1);
            }

            // 7. [PERBAIKAN] Buat FULL Request Body DENGAN SANITASI
            $requestBody = [
                'order' => [
                    'amount' => (int) $totalPrice,
                    'invoice_number' => $invoiceNumber, // (Aman, kita buat sendiri)
                    'currency' => 'IDR',
                    'callback_url' => route('doku.notification'),
                    'line_items' => $lineItems // (Sudah bersih)
                ],
                'payment' => [
                    'payment_due_date' => 60
                ],
                'customer' => [
                    'name' => $this->sanitizeForDoku($customerData['nama']), // <-- DIBERSIHKAN
                    'email' => $customerData['email'], // (Aman, divalidasi)
                    'phone' => $customerPhone, // (Aman, divalidasi)
                    'address' => $this->sanitizeForDoku('Plaza Asia Office Park Unit 3'), // <-- DIBERSIHKAN
                    'country' => 'ID'
                ]
            ];

            // 8. Tentukan Endpoint DOKU
            $requestTarget = '/checkout/v1/payment'; 

            // 9. Encode body SEKALI SAJA
            $jsonBody = json_encode($requestBody, JSON_UNESCAPED_SLASHES);

            // 10. Panggil Helper
            $headers = DokuSignatureHelper::generate($jsonBody, $requestTarget);

            // 11. Hit API DOKU
            $response = Http::withHeaders($headers)
                ->withBody($jsonBody, 'application/json') 
                ->post(config('doku.base_url') . $requestTarget);

            if (!$response->successful()) {
                DB::rollBack();
                Log::error('DOKU API Error: ' . $response->body(), ['request' => $requestBody]);
                throw new \Exception('Gagal menghubungi DOKU: ' . $response->body());
            }

            // 12. Sukses!
            $dokuResponse = $response->json();
            $reservation->payment_token = $dokuResponse['payment']['token_id'];
            $reservation->expired_at = $dokuResponse['payment']['expired_datetime']; 
            $reservation->save(); 
            DB::commit(); 

            // 13. Kembalikan URL ke Frontend
            return response()->json([
                'payment_url' => $dokuResponse['payment']['url']
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            return response()->json(['message' => 'Data pemesan tidak valid.', 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('DOKU Payment Error: ' . $e->getMessage());
            return response()->json(['message' => 'Terjadi kesalahan internal. Silakan coba beberapa saat lagi.'], 500);
        }
    }
    /**
     * Membersihkan string agar sesuai dengan aturan karakter DOKU.
     * Hanya mengizinkan: a-z A-Z 0-9 dan simbol . - / + , = _ : ' @ %
     *
     * @param string $string
     * @return string
     */
    private function sanitizeForDoku(string $string): string
    {
        // Regex ini akan MENGHAPUS semua karakter
        // yang BUKAN (^) karakter di dalam daftar.
        
        // [^a-zA-Z0-9\.\-\/\+\,=\_:'@%]
        
        // Kita ganti karakter yang tidak valid dengan string kosong ''
        return preg_replace("/[^a-zA-Z0-9\.\-\/\+\,=\_:'@%]/", '', $string);
    }
    
} 