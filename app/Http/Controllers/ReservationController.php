<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class ReservationController extends Controller
{
    /**
     * Menampilkan halaman manajemen reservasi.
     */
    public function index(): View
    {
        // Data statis untuk reservasi
        $reservations = [
            ['id_reservasi' => 'RSVM001', 'id_transaksi' => 'TR5001', 'nomor_meja' => 1,  'nomor_ruangan' => null, 'nama_customer' => 'Sylya', 'nomor_telepon' => '0812 xxx xxx', 'jumlah_orang' => 4, 'tanggal' => '17/01/24', 'waktu_reservasi' => '11.00 WIB', 'status' => false],
            ['id_reservasi' => 'RSVM002', 'id_transaksi' => 'TR5002', 'nomor_meja' => 7, 'nomor_ruangan' => null, 'nama_customer' => 'Gulum', 'nomor_telepon' => '0852 xxx xxx', 'jumlah_orang' => 1, 'tanggal' => '08/08/24', 'waktu_reservasi' => '15.00 WIB', 'status' => false],
            ['id_reservasi' => 'RSVM003', 'id_transaksi' => 'TR5003', 'nomor_meja' => 3, 'nomor_ruangan' => null, 'nama_customer' => 'Zayya', 'nomor_telepon' => '0813 xxx xxx', 'jumlah_orang' => 2, 'tanggal' => '10/10/24', 'waktu_reservasi' => '19.00 WIB', 'status' => true],
            ['id_reservasi' => 'RSVM004', 'id_transaksi' => 'TR5004', 'nomor_meja' => 5, 'nomor_ruangan' => null, 'nama_customer' => 'Fahira', 'nomor_telepon' => '0814 xxx xxx', 'jumlah_orang' => 5, 'tanggal' => '12/11/24', 'waktu_reservasi' => '20.00 WIB', 'status' => false],
            ['id_reservasi' => 'RSVM005', 'id_transaksi' => 'TR5005', 'nomor_meja' => 2, 'nomor_ruangan' => null, 'nama_customer' => 'Dara', 'nomor_telepon' => '0815 xxx xxx', 'jumlah_orang' => 3, 'tanggal' => '15/12/24', 'waktu_reservasi' => '18.30 WIB', 'status' => true],
            ['id_reservasi' => 'RSVM006', 'id_transaksi' => 'TR5006', 'nomor_meja' => 12, 'nomor_ruangan' => null, 'nama_customer' => 'Shally', 'nomor_telepon' => '0815 xxx xxx', 'jumlah_orang' => 3, 'tanggal' => '15/12/24', 'waktu_reservasi' => '18.30 WIB', 'status' => true],
            ['id_reservasi' => 'RSVR001', 'id_transaksi' => 'TR5007', 'nomor_meja' => null, 'nomor_ruangan' => 1, 'nama_customer' => 'Kornel', 'nomor_telepon' => '0815 xxx xxx', 'jumlah_orang' => 12, 'tanggal' => '20/12/24', 'waktu_reservasi' => '18.30 WIB', 'status' => false],
            ['id_reservasi' => 'RSVR002', 'id_transaksi' => 'TR5008', 'nomor_meja' => null, 'nomor_ruangan' => 2, 'nama_customer' => 'Aqsal', 'nomor_telepon' => '0815 xxx xxx', 'jumlah_orang' => 8, 'tanggal' => '21/12/24', 'waktu_reservasi' => '18.30 WIB', 'status' => true],
            ['id_reservasi' => 'RSVR003', 'id_transaksi' => 'TR5009', 'nomor_meja' => null, 'nomor_ruangan' => 1, 'nama_customer' => 'Imam', 'nomor_telepon' => '0815 xxx xxx', 'jumlah_orang' => 15, 'tanggal' => '22/12/24', 'waktu_reservasi' => '18.30 WIB', 'status' => false],
          
    
        ];

        return view('admin.manajemen-reservasi', ['reservations' => $reservations]);
    }
}