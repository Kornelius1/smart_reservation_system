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

// Import class Xendit v7
use Xendit\Configuration;
use Xendit\Invoice\InvoiceApi;
use Xendit\Invoice\CreateInvoiceRequest;
use Xendit\Invoice\Customer;
use Xendit\Invoice\InvoiceItem;
use Xendit\Exceptions\ApiException;

class BayarController extends Controller
{
    // ... (Fungsi show() Anda sudah benar dan tidak berubah)
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
            'detail' => $reservationDetail
        ];

        return view('customer.BayarReservasi', [
            'cartItems'          => $cartItems,
            'totalPrice'         => $totalPrice,
            'reservationDetails' => $reservationData
        ]);
    }


    public function processPayment(Request $request)
    {
        // 1. & 2. VALIDASI (Tidak berubah)
        $validationResult = $this->validateOrder($request);
        if ($validationResult instanceof RedirectResponse) {
            return redirect()->back()
                ->withErrors($validationResult->getSession()->get('errors'))
                ->withInput();
        }

        $customerData = $request->validate([
            'nama'          => 'required|string|max:255',
            'nomor_telepon' => 'required|string|regex:/^08[0-9]{8,12}$/',
            'jumlah_orang'  => 'required|integer|min:1',
            'tanggal'       => 'required|date|after_or_equal:today',
            'waktu'         => 'required|date_format:H:i',
        ]);

        // Unpack data (Tidak berubah)
        $totalPrice = $validationResult['totalPrice'];
        $reservationType = $validationResult['reservationType'];
        $reservationFkId = $validationResult['reservationFkId'];
        $products = $validationResult['products'];
        $itemsFromRequest = $validationResult['items'];

        // 3. BUAT ID TRANSAKSI (Tidak berubah)
        $id_transaksi = 'HOMEY-' . now()->format('Ymd') . '-' . strtoupper(Str::random(6));

        // 4. SIMPAN RESERVASI (Tidak berubah)
        $dataToSave = [
            'id_transaksi'  => $id_transaksi,
            'nama'          => $customerData['nama'],
            'nomor_telepon' => $customerData['nomor_telepon'],
            'jumlah_orang'  => $customerData['jumlah_orang'],
            'tanggal'       => $customerData['tanggal'],
            'waktu'         => $customerData['waktu'],
            'status'        => 'pending',
            'nomor_meja'    => ($reservationType === 'meja') ? $reservationFkId : null,
            'nomor_ruangan' => ($reservationType === 'ruangan') ? $reservationFkId : null,
        ];
        $reservation = Reservation::create($dataToSave);

        // 5. PROSES XENDIT (SDK v7) (Tidak berubah)
        Configuration::setApiKey(config('xendit.api_key'));

        $items_xendit = [];
        foreach ($products as $product) {
            $quantity = $itemsFromRequest[$product->id];
            $items_xendit[] = new InvoiceItem([
                'name'     => $product->name,
                'quantity' => $quantity,
                'price'    => $product->price,
            ]);
        }

        $customer_xendit = new Customer([
            'given_name'   => $customerData['nama'],
            'mobile_number' => $customerData['nomor_telepon'],
        ]);

        $createInvoiceRequest = new CreateInvoiceRequest([
            'external_id'           => $id_transaksi,
            'amount'                => $totalPrice,
            'description'           => 'Reservasi Homey Cafe ' . $id_transaksi,
            'customer'              => $customer_xendit,
            'items'                 => $items_xendit,
            'currency'              => 'IDR',
            'success_redirect_url'  => route('payment.success'),
            'failure_redirect_url'  => route('payment.failed'),
        ]);

        try {
            $apiInstance = new InvoiceApi();
            $invoice = $apiInstance->createInvoice($createInvoiceRequest);
            return redirect($invoice['invoice_url']);

        } catch (ApiException $e) {
            // Tangani error dari Xendit
            return redirect()->back()
                ->withErrors(['msg' => 'Gagal membuat invoice Xendit: ' . $e->getMessage()])
                ->withInput();
        
        // ==========================================================
        // PERBAIKAN: Tambahkan catch ini
        // ==========================================================
        } catch (\Exception $e) {
            // Tangani error umum (misal: rute not found, dll)
            return redirect()->back()
                ->withErrors(['msg' => 'Terjadi kesalahan umum: ' . $e->getMessage()])
                ->withInput();
        }
    }


    // ... (Fungsi validateOrder() Anda sudah benar dan tidak berubah)
    private const MINIMUM_ORDER_FOR_TABLE = 50000;
    private function validateOrder(Request $request)
    {
        // ... (Kode Anda di sini sudah benar)
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