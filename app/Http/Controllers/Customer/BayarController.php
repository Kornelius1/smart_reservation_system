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


    // GANTI SELURUH FUNGSI ANDA DENGAN INI (VERSI DENGAN PERBAIKAN BUG)
    public function processPayment(Request $request): JsonResponse
    {
        // 1. Validasi Keamanan & Data Pemesan
        $validationResult = $this->validateOrderForPayment($request);
        if ($validationResult instanceof RedirectResponse) {
            return response()->json(['message' => 'Data pesanan tidak valid atau tidak memenuhi syarat.'], 422);
        }
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
                'id_transaksi' => $invoiceNumber, 'total_price' => $totalPrice, 'status' => 'pending', 
                'nama' => $customerData['nama'], 'email_customer' => $customerData['email'],
                'nomor_telepon' => $customerData['nomor_telepon'], 'jumlah_orang' => $customerData['jumlah_orang'],
                'tanggal' => $customerData['tanggal'], 'waktu' => $customerData['waktu'] . ':00',
            ];
            if ($validationResult['reservationType'] === 'meja') {
                $reservationData['nomor_meja'] = $validationResult['reservationFkId'];
            } elseif ($validationResult['reservationType'] === 'ruangan') {
                $reservationData['nomor_ruangan'] = $validationResult['reservationFkId'];
            }
            
            // 5. Buat reservasi di DB
            $reservation = Reservation::create($reservationData);
            
            
            // [PERBAIKAN BUG #2 - SIMPAN PRODUK KE PIVOT TABLE]
            // (Model Anda mengkonfirmasi 'products()' adalah relasi yang benar)
            $productsToSync = [];
            $products = $validationResult['products'];
            $itemsFromRequest = $validationResult['items'];
            
            foreach ($products as $product) {
                $quantity = $itemsFromRequest[$product->id];
                $productsToSync[$product->id] = [
                    'quantity' => $quantity,
                    'price' => $product->price 
                ];
            }
            // Simpan ke tabel 'reservation_product'
            $reservation->products()->attach($productsToSync);
            // [AKHIR PERBAIKAN BUG #2]

            
            // 6. Siapkan 'line_items' (Sudah Benar)
            $lineItems = [];
            foreach ($products as $product) {
                $lineItems[] = [
                    'name' => $this->sanitizeForDoku($product->name),
                    'price' => (int) $product->price,
                    'quantity' => (int) $itemsFromRequest[$product->id]
                ];
            }
            
            $customerPhone = $customerData['nomor_telepon'];
            if (str_starts_with($customerPhone, '08')) { $customerPhone = '+62' . substr($customerPhone, 1); }

            // 7. Buat FULL Request Body (Sudah Benar)
            $requestBody = [
                'order' => ['amount' => (int) $totalPrice, 'invoice_number' => $invoiceNumber, 'currency' => 'IDR', 'callback_url' => route('doku.notification'), 'callback_url_result' => route('payment.success', ['invoice' => $invoiceNumber]), 'line_items' => $lineItems],
                'payment' => ['payment_due_date' => 10], // Diubah ke 10 menit
                'customer' => ['name' => $this->sanitizeForDoku($customerData['nama']), 'email' => $customerData['email'], 'phone' => $customerPhone, 'address' => $this->sanitizeForDoku('Plaza Asia Office Park Unit 3'), 'country' => 'ID']
            ];
            $requestTarget = '/checkout/v1/payment'; 
            $jsonBody = json_encode($requestBody, JSON_UNESCAPED_SLASHES);
            $headers = DokuSignatureHelper::generate($jsonBody, $requestTarget);

            // 11. Hit API DOKU (Sudah Benar)
            $response = Http::withHeaders($headers)->withBody($jsonBody, 'application/json')->post(config('doku.base_url') . $requestTarget);
            $dokuResponse = $response->json();

            // 12. Periksa kegagalan (Sudah Benar)
            if (!isset($dokuResponse['response']['payment'])) {
                DB::rollBack();
                $errorMessage = 'DOKU mengembalikan respons tidak valid.';
                if (isset($dokuResponse['message'])) { $errorMessage = 'DOKU Error: ' . (is_array($dokuResponse['message']) ? implode(', ', $dokuResponse['message']) : $dokuResponse['message']);
                } elseif (isset($dokuResponse['error']['message'])) { $errorMessage = 'DOKU Error: ' . $dokuResponse['error']['message']; }
                Log::error('DOKU API Error (Logic Fail): ' . $response->body(), ['request' => $requestBody, 'response' => $dokuResponse]);
                throw new \Exception($errorMessage);
            }

            // 13. Sukses (Sudah Benar)
            $reservation->payment_token = $dokuResponse['response']['payment']['token_id'];
            $reservation->expired_at = $dokuResponse['response']['payment']['expired_datetime']; 
            $reservation->save(); 
            DB::commit(); 

            // 14. Kembalikan URL (Sudah Benar)
            return response()->json([
                'payment_url' => $dokuResponse['response']['payment']['url']
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('DOKU Payment Error (Caught): ' . $e->getMessage());
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    // Pastikan fungsi sanitizeForDoku Anda ada di sini
    private function sanitizeForDoku(string $string): string
    {
        return preg_replace("/[^a-zA-Z0-9\.\-\/\+\,=\_:'@%]/", '', $string);
    }
} 