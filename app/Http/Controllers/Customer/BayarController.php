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
// IMPORT XENDIT SDK v7 (YANG BENAR)
// ==========================================================
use Xendit\Configuration;
use Xendit\Invoice\InvoiceApi;
use Xendit\Invoice\CreateInvoiceRequest;
use Xendit\Invoice\InvoiceItem;
use Xendit\ApiException; // <-- PERBAIKAN: Hapus namespace 'Exceptions'


class BayarController extends Controller
{
    /**
     * Menampilkan halaman konfirmasi
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

    /**
     * Memproses data dan mengirim ke Xendit
     */
    public function processPayment(Request $request)
    {
        // 1. VALIDASI PESANAN (Item & Reservasi)
        $validationResult = $this->validateOrder($request);
        if ($validationResult instanceof RedirectResponse) {
            return redirect()->back()
                ->withErrors($validationResult->getSession()->get('errors'))
                ->withInput();
        }

        // 2. VALIDASI DATA CUSTOMER
        $customerData = $request->validate([
            'nama'          => 'required|string|max:255',
            'nomor_telepon' => 'required|string|regex:/^08[0-9]{8,12}$/',
            'jumlah_orang'  => 'required|integer|min:1',
            'tanggal'       => 'required|date|after_or_equal:today',
            'waktu'         => 'required|date_format:H:i',
        ]);

        // Unpack data
        $totalPrice = $validationResult['totalPrice'];
        $reservationType = $validationResult['reservationType'];
        $reservationFkId = $validationResult['reservationFkId'];
        $products = $validationResult['products'];
        $itemsFromRequest = $validationResult['items'];

        // 3. BUAT ID TRANSAKSI
        $id_transaksi = 'HOMEY-' . now()->format('Ymd') . '-' . strtoupper(Str::random(6));

        // 4. SIMPAN RESERVASI 'PENDING'
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

   // ==========================================================
        // PERBAIKAN BARU: Simpan ID Transaksi ke Session
        // ==========================================================
        session(['last_transaction_id' => $id_transaksi]);

         // ==========================================================
        // TAMBAHAN BARU (MASALAH #3): Simpan item ke pivot table
        // ==========================================================
        foreach ($products as $product) {
            $quantity = $itemsFromRequest[$product->id];
            
            // 'attach' akan menyimpan data ke tabel pivot 'reservation_product'
            $reservation->products()->attach($product->id, [
                'quantity' => $quantity,
                'price'    => $product->price // Simpan harga saat itu
            ]);
        }

        // 5. PROSES XENDIT (SDK v7)
        
        // Set API Key
        Configuration::getDefaultConfiguration()->setApiKey(config('xendit.api_key'));

        // Siapkan item
        $items_xendit = [];
        foreach ($products as $product) {
            $quantity = $itemsFromRequest[$product->id];
            $items_xendit[] = new InvoiceItem([
                'name'     => $product->name,
                'quantity' => $quantity,
                'price'    => $product->price,
            ]);
        }

        // Siapkan customer (sebagai array)
        $customer_data_array = [
            'given_name'   => $customerData['nama'],
            'mobile_number' => $customerData['nomor_telepon'],
        ];

        // Buat request invoice
        $createInvoiceRequest = new CreateInvoiceRequest([
            'external_id'           => $id_transaksi,
            'amount'                => $totalPrice,
            'description'           => 'Reservasi Homey Cafe ' . $id_transaksi,
            'customer_object'       => $customer_data_array, // <-- PERBAIKAN: 'customer_object'
            'items'                 => $items_xendit,
            'currency'              => 'IDR',
            'invoice_duration'      => 30, 
            'success_redirect_url'  => route('payment.success'),
            'failure_redirect_url'  => route('payment.failed'),
        ]);

        try {
            // Buat instance API
            $apiInstance = new InvoiceApi();
            
            // Buat Invoice
            $invoice = $apiInstance->createInvoice($createInvoiceRequest);

            // Redirect ke Xendit
            return redirect($invoice['invoice_url']);

        } catch (ApiException $e) { // <-- PERBAIKAN: Ini sekarang sudah benar
            // Tangani error dari Xendit
            return redirect()->back()
                ->withErrors(['msg' => 'Gagal membuat invoice Xendit: ' . $e->getMessage()])
                ->withInput();
        
        } catch (\Exception $e) {
            // Tangani error umum
            return redirect()->back()
                ->withErrors(['msg' => 'Terjadi kesalahan umum: ' . $e->getMessage()])
                ->withInput();
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