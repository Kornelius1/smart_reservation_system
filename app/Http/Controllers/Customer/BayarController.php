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
        // ... (Fungsi ini sudah benar, tidak ada perubahan) ...
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
     * 💳 Memproses pembayaran ke DOKU
     */
    public function processPayment(Request $request): JsonResponse
    {
        // 1️⃣ Validasi pesanan
        $validationResult = $this->validateOrder($request);
        if ($validationResult instanceof RedirectResponse) {
            return response()->json(['success' => false, 'message' => 'Pesanan tidak valid'], 422);
        }

        // 2️⃣ Validasi data customer
        $validator = Validator::make($request->all(), [
            'nama'          => 'required|string|max:255',
            'nomor_telepon' => 'required|string|regex:/^08[0-9]{8,12}$/',
            'email'         => 'required|email|max:255',
            'jumlah_orang'  => 'required|integer|min:1',
            'tanggal'       => 'required|date|after_or_equal:today',
            'waktu'         => 'required|date_format:H:i',
        ]);
        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first()], 422);
        }
        $customerData = $validator->validated();

        // 3️⃣ Data pesanan
        $totalPrice = $validationResult['totalPrice'];
        $reservationType = $validationResult['reservationType'];
        $reservationFkId = $validationResult['reservationFkId'];
        $products = $validationResult['products']; // <-- Kita akan gunakan ini
        $itemsFromRequest = $validationResult['items']; // <-- Kita akan gunakan ini

        $id_transaksi = 'HOMEY-' . now()->format('Ymd') . '-' . strtoupper(Str::random(6));

        try {
            DB::beginTransaction();

            // Simpan reservasi
            $reservation = Reservation::create([
                'id_transaksi'   => $id_transaksi,
                'nama'           => $customerData['nama'],
                'nomor_telepon'  => $customerData['nomor_telepon'],
                'email_customer' => $customerData['email'],
                'jumlah_orang'   => $customerData['jumlah_orang'],
                'tanggal'        => $customerData['tanggal'],
                'waktu'          => $customerData['waktu'],
                'status'         => 'pending',
                'nomor_meja'     => ($reservationType === 'meja') ? $reservationFkId : null,
                'nomor_ruangan'  => ($reservationType === 'ruangan') ? $reservationFkId : null,
            ]);

            // Lampirkan produk ke reservasi DAN siapkan line_items
            $lineItems = [];
            foreach ($products as $product) {
                $quantity = $itemsFromRequest[$product->id];
                // 1. Lampirkan ke database kita
                $reservation->products()->attach($product->id, [
                    'quantity' => $quantity,
                    'price'    => $product->price,
                ]);
                
                // 2. Siapkan untuk DOKU
                $lineItems[] = [
                    'name'     => (string) $product->name,
                    'price'    => (int) $product->price,
                    'quantity' => (int) $quantity,
                ];
            }

            // 4️⃣ Siapkan body JSON untuk DOKU (Termasuk line_items)
            $body = [
                'order' => [
                    'invoice_number' => $id_transaksi,
                    'amount'         => (int) round($totalPrice), // Total amount HARUS sama dengan total line_items
                    'currency'       => 'IDR',
                    'auto_redirect'  => true,
                    'success_url'    => route('payment.success'),
                    'failed_url'     => route('payment.failed'),
                ],
                'line_items' => $lineItems, // <-- INI DIA BAGIAN YANG HILANG
                'customer' => [
                    'name'  => $customerData['nama'],
                    'email' => $customerData['email'],
                ],
            ];
            $bodyJson = json_encode($body);

        
            $clientId  = config('services.doku.client_id');
            $secretKey = config('services.doku.secret_key');
            $apiUrl    = config('services.doku.api_base'); 
            $path      = '/checkout/v1/payment'; // Endpoint v2
            $url       = $apiUrl . $path;

            $requestId    = (string) Str::uuid();
            $isoTimestamp = now()->utc()->format('Y-m-d\TH:i:s'); // Format UTC "Z"

            $signature = DokuSignatureHelper::generateSignature(
                $clientId,
                $secretKey,
                $requestId,
                $isoTimestamp,
                $path,
                $bodyJson
            );
        
            $headers = [
                'Client-Id'       => $clientId,
                'Request-Id'      => $requestId,
                'Request-Timestamp' => $isoTimestamp,
                'Signature'       => $signature,
                'Content-Type'    => 'application/json',
                'Accept'          => 'application/json',
            ];

            // 7️⃣ Log data untuk debugging (versi bersih)
            Log::info('--- DATA DIKIRIM KE DOKU ---', [
                'url'     => $url,
                'headers' => $headers,
                'body'    => $body, // Log array PHP, lebih mudah dibaca
            ]);

        
            $response = Http::withHeaders($headers)
                ->withBody($bodyJson, 'application/json')
                ->post($url);

            // 9️⃣ Cek response
            if ($response->successful() && isset($response['payment']['url'])) {
                $reservation->update([
                    'raw_response' => json_encode($response->json()),
                ]);
                DB::commit();

                return response()->json([
                    'success'     => true,
                    'payment_url' => $response['payment']['url'],
                ]);
            }

            DB::rollBack();
            Log::error('DOKU Payment Failed:', $response->json());
            return response()->json([
                'success'  => false,
                'message'  => 'DOKU gagal memproses pembayaran',
                'response' => $response->json(),
            ], 500);

        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('CRITICAL Payment Error: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            // ⬇️⬇️ PERBAIKAN DI SINI ⬇️⬇️
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan kritis di server: ' . $e->getMessage()
            ], 500);
        }
    } // ⬅️ Kurung kurawal penutup 'processPayment' yang hilang

    /**
     * 🧮 Validasi pesanan
     */
    private function validateOrder(Request $request)
    {
        // ⬇️⬇️ KODE LENGKAP FUNGSI INI YANG TERPOTONG ⬇️⬇️
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
} // ⬅️ Kurung kurawal penutup 'class' yang hilang