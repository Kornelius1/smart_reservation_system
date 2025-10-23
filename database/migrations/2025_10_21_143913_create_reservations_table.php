<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   
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
            $table->boolean('status')->default(true); 
            $table->integer('nomor_meja')->nullable(); 
            $table->integer('nomor_ruangan')->nullable(); 
            $table->timestamps(); 
        });
    }

   
    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};
