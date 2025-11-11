<?php
namespace App\Http\Controllers\Customer;


use App\Http\Controllers\Controller;
use App\Models\Meja;

class DenahMejaController extends Controller
{

    private const MINIMUM_ORDER_FOR_TABLE = 50000;
    public function index()
    {
        // Ambil semua meja dan kelompokkan berdasarkan lokasinya
        $mejas = Meja::all()->groupBy('lokasi');

 // 2. KIRIM ATURAN INI KE VIEW BERSAMA DATA MEJA
        return view('customer.DenahMeja', [
            'mejasByLocation' => $mejas,
            'minimumOrder' => self::MINIMUM_ORDER_FOR_TABLE
        ]);
    }
}