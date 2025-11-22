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
        $reservationFkId = $validationResult['reservationFkId'];

        $kapasitas = null;

        if ($reservationType === 'ruangan') {
            $ruangan = Room::find($reservationFkId);
            $kapasitas = $ruangan->kapasitas ?? 10;
        }

        if ($reservationType === 'meja') {
            $meja = Meja::find($reservationFkId);
            $kapasitas = $meja->kapasitas ?? 4;
        }

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
            'cartItems'          => $cartItems,
            'totalPrice'         => $totalPrice,
            'reservationDetails' => $reservationData,
            'kapasitas'          => $kapasitas,
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

        if (empty($roomName) && empty($tableNumber)) {
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
                'products'           => $products,
                'items'              => $itemsFromRequest,
                'totalPrice'         => $totalPrice,
                'reservationType'    => 'ruangan',
                'reservationDetail'  => $roomName,
                'reservationFkId'    => $room->id,
            ];
        }

        if ($tableNumber) {
            $meja = Meja::where('nomor_meja', $tableNumber)->where('status_aktif', true)->first();
            if (!$meja) return redirect('/pilih-meja')->withErrors(['msg' => 'Meja tidak tersedia.']);
            if ($totalPrice < self::MINIMUM_ORDER_FOR_TABLE) {
                return redirect('/pesanmenu')->withErrors(['msg' => 'Belanja belum memenuhi minimal meja.']);
            }
            return [
                'products'           => $products,
                'items'              => $itemsFromRequest,
                'totalPrice'         => $totalPrice,
                'reservationType'    => 'meja',
                'reservationDetail'  => $tableNumber,
                'reservationFkId'    => $meja->id,
            ];
        }

        return redirect('/pilih-reservasi')->withErrors(['msg' => 'Pilih jenis reservasi.']);
    }

    private function validateOrderForPayment(Request $request)
    {
        $itemsFromRequest = $request->input('items', []);
        $roomName = trim($request->input('reservation_room_name'));
        $tableNumber = trim($request->input('reservation_table_number'));
        $isAjax = $request->expectsJson();

        if (empty($itemsFromRequest)) {
            return $isAjax
                ? response()->json(['message' => 'Keranjang kosong!'], 422)
                : redirect('/pesanmenu')->withErrors(['msg' => 'Keranjang kosong!']);
        }

        $productIds = array_keys($itemsFromRequest);
        $products = Product::findMany($productIds);
        $totalPrice = 0;
        foreach ($products as $product) {
            $totalPrice += $product->price * $itemsFromRequest[$product->id];
        }

        if ($roomName) {
            $room = Room::where('nama_ruangan', $roomName)->first();
            if (!$room) {
                return $isAjax
                    ? response()->json(['message' => 'Ruangan tidak valid.'], 422)
                    : redirect('/pesanmenu')->withErrors(['msg' => 'Ruangan tidak valid.']);
            }
            if ($totalPrice < $room->minimum_order) {
                return $isAjax
                    ? response()->json(['message' => 'Belanja belum memenuhi minimal ruangan.'], 422)
                    : redirect('/pesanmenu')->withErrors(['msg' => 'Belanja belum memenuhi minimal ruangan.']);
            }

            return [
                'products'           => $products,
                'items'              => $itemsFromRequest,
                'totalPrice'         => $totalPrice,
                'reservationType'    => 'ruangan',
                'reservationDetail'  => $roomName,
                'reservationFkId'    => $room->id,
            ];
        }

        if ($tableNumber) {
            $meja = Meja::where('nomor_meja', $tableNumber)->where('status_aktif', true)->first();
            if (!$meja) {
                return $isAjax
                    ? response()->json(['message' => 'Meja tidak tersedia.'], 422)
                    : redirect('/pilih-meja')->withErrors(['msg' => 'Meja tidak tersedia.']);
            }
            if ($totalPrice < self::MINIMUM_ORDER_FOR_TABLE) {
                return $isAjax
                    ? response()->json(['message' => 'Belanja belum memenuhi minimal meja.'], 422)
                    : redirect('/pesanmenu')->withErrors(['msg' => 'Belanja belum memenuhi minimal meja.']);
            }

            return [
                'products'           => $products,
                'items'              => $itemsFromRequest,
                'totalPrice'         => $totalPrice,
                'reservationType'    => 'meja',
                'reservationDetail'  => $tableNumber,
                'reservationFkId'    => $meja->id,
            ];
        }

        return $isAjax
            ? response()->json(['message' => 'Pilih jenis reservasi.'], 422)
            : redirect('/pilih-reservasi')->withErrors(['msg' => 'Pilih jenis reservasi.']);
    }

    public function confirmReservation(Request $request)
    {
        $validationResult = $this->validateOrderForPayment($request);
        if ($validationResult instanceof RedirectResponse) {
            return $validationResult;
        }

        $tanggal = $request->tanggal;
        $start   = $request->waktu;
        $type    = $validationResult['reservationType'] === 'ruangan' ? 'room' : 'table';
        $id      = $validationResult['reservationFkId'];

        if ($this->checkAvailability($tanggal, $start, $type, $id)) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => $validationResult['reservationType'] === 'meja'
                        ? "Maaf, pada tanggal dan jam ini, meja sudah terisi."
                        : "Maaf, pada tanggal dan jam ini, ruangan sudah terisi."
                ], 422);
            }
            return redirect()->back()->withErrors([
                'waktu' => 'Jam tersebut sudah dibooking orang lain. Silakan pilih jam lain.'
            ]);
        }

        return $this->processPayment($request);
    }

    private function checkAvailability($tanggal, $startTime, $type, $id)
    {
        $duration = $type === 'room' ? 3 : 1;
        $start = date('H:i:s', strtotime($startTime));
        $end = date('H:i:s', strtotime($startTime . " +{$duration} hours"));

        $query = DB::table('reservations')
            ->whereDate('tanggal', $tanggal)
            ->whereIn('status', ['pending', 'akan datang', 'check-in']);

        if ($type === 'room') {
            $query->where('nomor_ruangan', $id); 
        } else {
            $query->where('nomor_meja', $id);
        }

        return $query->where(function($q) use ($start, $end) {
            $q->where('waktu', '<', $end)
              ->where(DB::raw("waktu + interval '1 hour'"), '>', $start);
        })->exists();
    }

    public function processPayment(Request $request): JsonResponse
    {
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
        
        $totalPrice = $validationResult['totalPrice'];
        $invoiceNumber = 'INV-' . time() . Str::random(5);

        $type = $validationResult['reservationType'] === 'ruangan' ? 'room' : 'table';
        $id   = $validationResult['reservationFkId'];
        $tanggal = $customerData['tanggal'];
        $waktu = date('H:i:s', strtotime($customerData['waktu']));

        if ($this->checkAvailability($tanggal, $waktu, $type, $id)) {
            return response()->json([
                'message' => $validationResult['reservationType'] === 'meja'
                    ? "Maaf, pada tanggal dan jam ini, meja sudah terisi."
                    : "Maaf, pada tanggal dan jam ini, ruangan sudah terisi."
            ], 422);
        }

        DB::beginTransaction();
        try {
            $reservationData = [
                'id_transaksi' => $invoiceNumber,
                'total_price' => $totalPrice,
                'status' => 'pending', 
                'nama' => $customerData['nama'],
                'email_customer' => $customerData['email'],
                'nomor_telepon' => $customerData['nomor_telepon'],
                'jumlah_orang' => $customerData['jumlah_orang'],
                'tanggal' => $customerData['tanggal'],
                'waktu' => $waktu,
            ];

            if ($validationResult['reservationType'] === 'meja') {
                $reservationData['nomor_meja'] = $validationResult['reservationFkId'];
            } elseif ($validationResult['reservationType'] === 'ruangan') {
                $reservationData['nomor_ruangan'] = $validationResult['reservationFkId'];
            }

            $reservation = Reservation::create($reservationData);

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

            $reservation->products()->attach($productsToSync);

            $lineItems = [];
            foreach ($products as $product) {
                $lineItems[] = [
                    'name' => $this->sanitizeForDoku($product->name),
                    'price' => (int) $product->price,
                    'quantity' => (int) $itemsFromRequest[$product->id]
                ];
            }

            $customerPhone = $customerData['nomor_telepon'];
            if (str_starts_with($customerPhone, '08')) {
                $customerPhone = '+62' . substr($customerPhone, 1);
            }

            $requestBody = [
                'order' => [
                    'amount' => (int) $totalPrice,
                    'invoice_number' => $invoiceNumber,
                    'currency' => 'IDR',
                    'callback_url' => route('pesanmenu'),
                    'callback_url_result' => route('payment.success', ['invoice' => $invoiceNumber]),            
                    'line_items' => $lineItems
                ],
                'payment' => [
                    'payment_due_date' => 10
                ],
                'customer' => [
                    'name' => $this->sanitizeForDoku($customerData['nama']),
                    'email' => $customerData['email'],
                    'phone' => $customerPhone,
                    'address' => $this->sanitizeForDoku('Plaza Asia Office Park Unit 3'),
                    'country' => 'ID'
                ],
                'additional_info' => [
                    'override_notification_url' => route('doku.notification')
                ]
            ];

            $requestTarget = '/checkout/v1/payment'; 
            $jsonBody = json_encode($requestBody, JSON_UNESCAPED_SLASHES);
            $headers = DokuSignatureHelper::generate($jsonBody, $requestTarget);

            $response = Http::withHeaders($headers)->withBody($jsonBody, 'application/json')->post(config('doku.base_url') . $requestTarget);
            $dokuResponse = $response->json();

            if (!isset($dokuResponse['response']['payment'])) {
                DB::rollBack();
                $errorMessage = 'DOKU mengembalikan respons tidak valid.';
                if (isset($dokuResponse['message'])) {
                    $errorMessage = 'DOKU Error: ' . (is_array($dokuResponse['message']) ? implode(', ', $dokuResponse['message']) : $dokuResponse['message']);
                } elseif (isset($dokuResponse['error']['message'])) {
                    $errorMessage = 'DOKU Error: ' . $dokuResponse['error']['message'];
                }
                Log::error('DOKU API Error (Logic Fail): ' . $response->body(), ['request' => $requestBody, 'response' => $dokuResponse]);
                throw new \Exception($errorMessage);
            }

            $reservation->payment_token = $dokuResponse['response']['payment']['token_id'];
            $reservation->expired_at = $dokuResponse['response']['payment']['expired_datetime']; 
            $reservation->save(); 
            DB::commit(); 

            return response()->json([
                'payment_url' => $dokuResponse['response']['payment']['url']
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('DOKU Payment Error (Caught): ' . $e->getMessage());
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    private function sanitizeForDoku(string $string): string
    {
        return preg_replace("/[^a-zA-Z0-9\.\-\/\+\,=\_:'@%]/", '', $string);
    }
}
