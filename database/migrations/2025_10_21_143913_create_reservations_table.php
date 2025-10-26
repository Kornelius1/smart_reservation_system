<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('reservations', function (Blueprint $table) {
          
            $table->id('id_reservasi');
            $table->string('id_transaksi')->unique(); 
            $table->string('nama'); 
            $table->string('nomor_telepon')->nullable(); 
            $table->integer('jumlah_orang')->nullable();
            $table->date('tanggal'); 
            $table->time('waktu'); 

            // =======================================================
            // PERUBAHAN DI SINI
            // 
            // Kode Lama (dihapus):
            // $table->boolean('status')->default(true); 
            //
            // Kode Baru:
            // Menggunakan string untuk menyimpan status seperti "Akan Datang", "Berlangsung", dll.
            $table->string('status')->default('Akan Datang'); 
            // =======================================================

            $table->integer('nomor_meja')->nullable(); 
            $table->integer('nomor_ruangan')->nullable(); 
            $table->timestamps(); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};