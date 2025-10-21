<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class ManajemenRescheduleController extends Controller
{
    /**
     * Menampilkan halaman manajemen reschedule.
     */
    public function index(): View
    {
        // Data statis untuk reschedule
        $reschedules = [
            ['id_reschedule' => 'RSV001', 'id_transaksi' => 'TR5001', 'nomor_meja' => 1, 'nama_customer' => 'Sylya', 'nomor_telepon' => '0812 xxx xxx', 'jumlah_orang' => 4, 'tanggal' => '17/01/24', 'waktu_reschedule' => '11.00 WIB', 'status' => false],
            ['id_reschedule' => 'RSV002', 'id_transaksi' => 'TR5002', 'nomor_meja' => 7, 'nama_customer' => 'Gulum', 'nomor_telepon' => '0852 xxx xxx', 'jumlah_orang' => 1, 'tanggal' => '08/08/24', 'waktu_reschedule' => '15.00 WIB', 'status' => false],
            ['id_reschedule' => 'RSV003', 'id_transaksi' => 'TR5003', 'nomor_meja' => 3, 'nama_customer' => 'Zayya', 'nomor_telepon' => '0813 xxx xxx', 'jumlah_orang' => 2, 'tanggal' => '10/10/24', 'waktu_reschedule' => '19.00 WIB', 'status' => true],
            ['id_reschedule' => 'RSV004', 'id_transaksi' => 'TR5004', 'nomor_meja' => 5, 'nama_customer' => 'Fahira', 'nomor_telepon' => '0814 xxx xxx', 'jumlah_orang' => 5, 'tanggal' => '12/11/24', 'waktu_reschedule' => '20.00 WIB', 'status' => false],
            ['id_reschedule' => 'RSV005', 'id_transaksi' => 'TR5005', 'nomor_meja' => 2, 'nama_customer' => 'Dara', 'nomor_telepon' => '0815 xxx xxx', 'jumlah_orang' => 3, 'tanggal' => '15/12/24', 'waktu_reschedule' => '18.30 WIB', 'status' => true],
            ['id_reschedule' => 'RSV006', 'id_transaksi' => 'TR5006', 'nomor_meja' => 12, 'nama_customer' => 'Shally', 'nomor_telepon' => '0815 xxx xxx', 'jumlah_orang' => 3, 'tanggal' => '15/12/24', 'waktu_reschedule' => '18.30 WIB', 'status' => true],
            ['id_reschedule' => 'RSV007', 'id_transaksi' => 'TR5007', 'nomor_meja' => 4, 'nama_customer' => 'Kornel', 'nomor_telepon' => '0815 xxx xxx', 'jumlah_orang' => 3, 'tanggal' => '15/12/24', 'waktu_reschedule' => '18.30 WIB', 'status' => false],
            ['id_reschedule' => 'RSV008', 'id_transaksi' => 'TR5008', 'nomor_meja' => 9, 'nama_customer' => 'Aqsal', 'nomor_telepon' => '0815 xxx xxx', 'jumlah_orang' => 3, 'tanggal' => '15/12/24', 'waktu_reschedule' => '18.30 WIB', 'status' => true],
            ['id_reschedule' => 'RSV009', 'id_transaksi' => 'TR5009', 'nomor_meja' => 16, 'nama_customer' => 'Imam', 'nomor_telepon' => '0815 xxx xxx', 'jumlah_orang' => 3, 'tanggal' => '15/12/24', 'waktu_reschedule' => '18.30 WIB', 'status' => false],
        ];

        return view('ManajemenReschedule', ['reschedules' => $reschedules]);
    }
}
