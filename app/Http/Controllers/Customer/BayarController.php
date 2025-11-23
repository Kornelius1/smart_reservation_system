<?php

namespace App\Http\Controllers\Customer;

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
    private const DURATION_MEJA = 12;  
    private const DURATION_RUANGAN = 3;  
    private const BUFFER_MINUTES = 15;   

    public function show(Request $request)
    {
        // 1. Validasi Awal
        $validationResult = $this->validateOrder($request, false);

        if ($validationResult instanceof RedirectResponse) {
            return $validationResult;
        }

        // 2. Ambil data SECARA MANUAL (Jangan pakai extract agar variabel terbaca jelas)
        $items = $validationResult['items'];       // <-- Ini solusi error $items tidak terbaca
        $products = $validationResult['products'];
        $totalPrice = $validationResult['totalPrice'];

        // 3. Tentukan Tipe Reservasi (Meja/Ruangan) dari hasil validasi
        if (isset($validationResult['room'])) {
            $reservationType = 'ruangan';
            $reservationDetail = $validationResult['roomName'];
            $room = $validationResult['room'];
            $kapasitas = $room->kapasitas ?? 10;
        } elseif (isset($validationResult['meja'])) {
            $reservationType = 'meja';
            $reservationDetail = $validationResult['tableNumber'];
            $meja = $validationResult['meja'];
            $kapasitas = $meja->kapasitas ?? 4;
        } else {
            return redirect('/pesanmenu')->withErrors(['msg' => 'Data reservasi tidak valid.']);
        }

        // 4. Bangun cartItems
        // Sekarang $items pasti terbaca karena sudah didefinisikan manual di poin 2
        $cartItems = collect($products)->map(function ($product) use ($items) {
            $quantity = $items[$product->id];
            return [
                'id'       => $product->id,
                'name'     => $product->name,
                'price'    => $product->price,
                'quantity' => $quantity,
                'subtotal' => $product->price * $quantity,
            ];
        })->all();

        // 5. Kirim ke View
        return view('customer.BayarReservasi', [
            'cartItems'          => $cartItems,
            'totalPrice'         => $totalPrice,
            'reservationDetails' => ['type' => $reservationType, 'detail' => $reservationDetail],
            'kapasitas'          => $kapasitas,
            // Data tambahan untuk JavaScript dropdown
            'mejas'              => Meja::where('status_aktif', true)->get(), 
            'rooms'              => Room::all(),
        ]);
    }

    /**
     * Validasi pesanan — mendukung mode redirect (web) atau JSON (API).
     */
    private function validateOrder(Request $request, bool $isAjax = null)
    {
        $isAjax = $isAjax ?? $request->expectsJson();
        $items = $request->input('items', []);
        $roomName = trim($request->input('reservation_room_name'));
        $tableNumber = trim($request->input('reservation_table_number'));

        if (empty($items)) {
            return $this->errorResponse($isAjax, 'Keranjang kosong!', '/pesanmenu');
        }

        $productIds = array_keys($items);
        $products = Product::findMany($productIds);
        $totalPrice = collect($products)->sum(fn($p) => $p->price * ($items[$p->id] ?? 0));

        // Validasi pilihan reservasi
        if (empty($roomName) && empty($tableNumber)) {
            return $isAjax
                ? response()->json(['message' => 'Pilih jenis reservasi.'], 422)
                : redirect('/pilih-reservasi')->withErrors(['msg' => 'Anda harus memilih meja atau ruangan terlebih dahulu.']);
        }

        // Cek ruangan
        if ($roomName) {
            $room = Room::where('nama_ruangan', $roomName)->first();
            if (!$room) return $this->errorResponse($isAjax, 'Ruangan tidak valid.', '/pesanmenu');
            if ($totalPrice < $room->minimum_order) {
                return $this->errorResponse($isAjax, 'Belanja belum memenuhi minimal ruangan.', '/pesanmenu');
            }
            return compact('products', 'items', 'totalPrice', 'roomName', 'room');
        }

        // Cek meja
        if ($tableNumber) {
            $meja = Meja::where('nomor_meja', $tableNumber)->where('status_aktif', true)->first();
            if (!$meja) return $this->errorResponse($isAjax, 'Meja tidak tersedia.', '/pilih-meja');
            if ($totalPrice < self::MINIMUM_ORDER_FOR_TABLE) {
                return $this->errorResponse($isAjax, 'Belanja belum memenuhi minimal meja.', '/pesanmenu');
            }
            return compact('products', 'items', 'totalPrice', 'tableNumber', 'meja');
        }

        return $this->errorResponse($isAjax, 'Pilih jenis reservasi.', '/pilih-reservasi');
    }

    /**
     * Helper untuk respons error konsisten.
     */
    private function errorResponse(bool $isAjax, string $message, string $redirectUrl)
    {
        return $isAjax
            ? response()->json(['message' => $message], 422)
            : redirect($redirectUrl)->withErrors(['msg' => $message]);
    }

    /**
     * Validasi pesanan khusus untuk pembayaran (mengembalikan array validasi).
     */
    private function validateOrderForPayment(Request $request)
    {
        $result = $this->validateOrder($request, true);
        if ($result instanceof JsonResponse) {
            return $result; // error
        }

        // Normalisasi hasil ke format konsisten
        if (isset($result['room'])) {
            return [
                'products' => $result['products'],
                'items' => $result['items'],
                'totalPrice' => $result['totalPrice'],
                'reservationType' => 'ruangan',
                'reservationDetail' => $result['roomName'],
                'reservationFkId' => $result['room']->id,
            ];
        }

        return [
            'products' => $result['products'],
            'items' => $result['items'],
            'totalPrice' => $result['totalPrice'],
            'reservationType' => 'meja',
            'reservationDetail' => $result['tableNumber'],
            'reservationFkId' => $result['meja']->id,
        ];
    }

   public function getAvailableTimes(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'tanggal' => 'required|date', // Hapus after_or_equal hari ini dulu untuk debugging
            'reservation_type' => 'required|in:meja,ruangan',
            'id' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => 'Data tidak valid'], 400);
        }

        $tanggal = $request->tanggal;
        $type = $request->reservation_type;
        $id = $request->id;
        $dbType = $type === 'ruangan' ? 'room' : 'table';

        $availableTimes = [];
        
        // UBAH DISINI: Loop menggunakan timestamp per 15 menit
        // Agar jika jam 10:00 terisi sampai 11:15, opsi 11:15 bisa muncul
        $startJam = strtotime('10:00');
        $endJam   = strtotime('21:00');
        $interval = 15 * 60; // 15 menit dalam detik

        while ($startJam <= $endJam) {
            $timeString = date('H:i', $startJam); // Format 10:00, 10:15, dst
            
            // Cek ketersediaan
            if (!$this->checkAvailability($tanggal, $timeString, $dbType, $id)) {
                $availableTimes[] = $timeString;
            }

            $startJam += $interval; // Tambah 15 menit
        }

        return response()->json(['available_times' => $availableTimes]);
    }

    private function getDurationFromReservation($reservation): int
    {
        if (!empty($reservation->nomor_meja)) return self::DURATION_MEJA;
        if (!empty($reservation->nomor_ruangan)) return self::DURATION_RUANGAN;
        return self::DURATION_MEJA; // fallback
    }

   private function checkAvailability(string $tanggal, string $startTime, string $type, int $id): bool
    {
        $buffer = self::BUFFER_MINUTES; // 15 menit
        
        // Hitung durasi pesanan BARU yang mau dicek
        $durationNew = $type === 'room' ? self::DURATION_RUANGAN : self::DURATION_MEJA;
        
        // Konversi ke timestamp agar akurat
        $startNewTs = strtotime("$tanggal $startTime");
        $endNewTs   = $startNewTs + ($durationNew * 3600) + ($buffer * 60);

        // Ambil reservasi eksisting
        $query = Reservation::whereDate('tanggal', $tanggal)
            ->whereIn('status', ['pending', 'akan datang', 'check-in']);

        if ($type === 'room') {
            $query->where('nomor_ruangan', $id);
        } else {
            $query->where('nomor_meja', $id);
        }

        $existingReservations = $query->get();

        foreach ($existingReservations as $existing) {
            $durationExisting = $this->getDurationFromReservation($existing);
            
            // Hitung start dan end reservasi LAMA (Existing)
            // End Existing = Waktu Mulai + Durasi + Buffer (Clean up)
            $startExistingTs = strtotime("$tanggal " . $existing->waktu);
            $endExistingTs   = $startExistingTs + ($durationExisting * 3600) + ($buffer * 60);

            // LOGIKA BENTROK (Overlap)
            // Pesanan baru bentrok jika:
            // (Start Baru < End Lama) DAN (Start Lama < End Baru)
            if ($startNewTs < $endExistingTs && $startExistingTs < $endNewTs) {
                return true; // Bentrok / Tidak Tersedia
            }
        }

        return false; // Aman / Tersedia
    }

    public function confirmReservation(Request $request)
    {
        $validationResult = $this->validateOrderForPayment($request);
        if ($validationResult instanceof JsonResponse) {
            return $validationResult;
        }

        $tanggal = $request->tanggal;
        $waktu = $request->waktu;
        $type = $validationResult['reservationType'] === 'ruangan' ? 'room' : 'table';
        $id = $validationResult['reservationFkId'];

        if ($this->checkAvailability($tanggal, $waktu, $type, $id)) {
            $message = $validationResult['reservationType'] === 'meja'
                ? "Maaf, pada tanggal dan jam ini, meja sudah terisi."
                : "Maaf, pada tanggal dan jam ini, ruangan sudah terisi.";

            return $request->expectsJson()
                ? response()->json(['message' => $message], 422)
                : redirect()->back()->withErrors(['waktu' => 'Jam tersebut sudah dibooking orang lain. Silakan pilih jam lain.']);
        }

        return $this->processPayment($request);
    }

    public function processPayment(Request $request): JsonResponse
    {
        $validationResult = $this->validateOrderForPayment($request);
        if ($validationResult instanceof JsonResponse) {
            return $validationResult;
        }

        $customerData = $request->validate([
            'nama' => 'required|string|min:3',
            'nomor_telepon' => 'required|string|regex:/^08[0-9]{8,12}$/',
            'email' => 'required|email',
            'jumlah_orang' => 'required|integer|min:1',
            'tanggal' => 'required|date|',
            'waktu' => 'required|date_format:H:i',
        ]);

        $productTotal = $validationResult['totalPrice']; 
        $serviceFee = config('doku.service_fee', 4440); 
        $finalTotal = $productTotal + $serviceFee; 
        $invoiceNumber = 'INV-' . time() . Str::random(5);
        $type = $validationResult['reservationType'] === 'ruangan' ? 'room' : 'table';
        $id = $validationResult['reservationFkId'];
        $tanggal = $customerData['tanggal'];
        $waktu = date('H:i:s', strtotime($customerData['waktu']));

        if ($this->checkAvailability($tanggal, $waktu, $type, $id)) {
            $message = $validationResult['reservationType'] === 'meja'
                ? "Maaf, pada tanggal dan jam ini, meja sudah terisi."
                : "Maaf, pada tanggal dan jam ini, ruangan sudah terisi.";
            return response()->json(['message' => $message], 422);
        }

        DB::beginTransaction();
        try {
            $reservationData = [
                'id_transaksi' => $invoiceNumber,
                'total_price' => $finalTotal,
                'status' => 'pending',
                'nama' => $customerData['nama'],
                'email_customer' => $customerData['email'],
                'nomor_telepon' => $customerData['nomor_telepon'],
                'jumlah_orang' => $customerData['jumlah_orang'],
                'tanggal' => $customerData['tanggal'],
                'waktu' => $waktu,
                'nomor_meja' => $validationResult['reservationType'] === 'meja' ? $id : null,
                'nomor_ruangan' => $validationResult['reservationType'] === 'ruangan' ? $id : null,
            ];

            $reservation = Reservation::create($reservationData);

            // Attach products
            $productsToSync = [];
            foreach ($validationResult['products'] as $product) {
                $qty = $validationResult['items'][$product->id];
                $productsToSync[$product->id] = ['quantity' => $qty, 'price' => $product->price];
            }
            $reservation->products()->attach($productsToSync);

            // Siapkan DOKU payload
            $lineItems = $validationResult['products']->map(function ($product) use ($validationResult) {
                return [
                    'name' => $this->sanitizeForDoku($product->name),
                    'price' => (int) $product->price,
                    'quantity' => (int) $validationResult['items'][$product->id]
                ];
            })->toArray();

            $customerPhone = str_starts_with($customerData['nomor_telepon'], '08')
                ? '+62' . substr($customerData['nomor_telepon'], 1)
                : $customerData['nomor_telepon'];

            $requestBody = [
                'order' => [
                    'amount' => (int) $finalTotal,
                    'invoice_number' => $invoiceNumber,
                    'currency' => 'IDR',
                    'callback_url' => route('pesanmenu'),
                    'callback_url_result' => route('payment.success', ['invoice' => $invoiceNumber]),
                    'line_items' => $lineItems
                ],
                'payment' => ['payment_due_date' => 10],
                'customer' => [
                    'name' => $this->sanitizeForDoku($customerData['nama']),
                    'email' => $customerData['email'],
                    'phone' => $customerPhone,
                    'address' => $this->sanitizeForDoku('Plaza Asia Office Park Unit 3'),
                    'country' => 'ID'
                ],
                'additional_info' => ['override_notification_url' => route('doku.notification')]
            ];

            $requestTarget = '/checkout/v1/payment';
            $jsonBody = json_encode($requestBody, JSON_UNESCAPED_SLASHES);
            $headers = DokuSignatureHelper::generate($jsonBody, $requestTarget);

            $response = Http::withHeaders($headers)
                ->withBody($jsonBody, 'application/json')
                ->post(config('doku.base_url') . $requestTarget);

            $dokuResponse = $response->json();

            if (!isset($dokuResponse['response']['payment'])) {
                DB::rollBack();
                $errorMessage = $this->extractDokuErrorMessage($dokuResponse);
                Log::error('DOKU API Error: ' . $response->body(), ['request' => $requestBody]);
                return response()->json(['message' => $errorMessage], 500);
            }

            $reservation->update([
                'payment_token' => $dokuResponse['response']['payment']['token_id'],
                'expired_at' => $dokuResponse['response']['payment']['expired_datetime']
            ]);

            DB::commit();
            return response()->json(['payment_url' => $dokuResponse['response']['payment']['url']]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('DOKU Payment Exception: ' . $e->getMessage());
            return response()->json(['message' => 'Terjadi kesalahan saat memproses pembayaran.'], 500);
        }
    }

    private function extractDokuErrorMessage($dokuResponse): string
    {
        if (isset($dokuResponse['message'])) {
            return 'DOKU Error: ' . (is_array($dokuResponse['message']) ? implode(', ', $dokuResponse['message']) : $dokuResponse['message']);
        }
        if (isset($dokuResponse['error']['message'])) {
            return 'DOKU Error: ' . $dokuResponse['error']['message'];
        }
        return 'DOKU mengembalikan respons tidak valid.';
    }

    private function sanitizeForDoku(string $string): string
    {
        return preg_replace("/[^a-zA-Z0-9\.\-\/\+\,=\_:'@%]/", '', $string);
    }
}