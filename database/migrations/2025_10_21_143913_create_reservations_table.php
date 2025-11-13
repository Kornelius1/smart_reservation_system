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
            $table->decimal('total_price', 15, 2)->default(0)->after('id_transaksi');
            $table->string('nama'); 
            $table->string('nomor_telepon')->nullable(); 
            $table->string('email_customer')->nullable(); 
            $table->integer('jumlah_orang')->nullable();
            $table->date('tanggal'); 
            $table->time('waktu'); 
            $table->string('status')->default('Akan Datang'); 
            $table->integer('nomor_meja')->nullable(); 
            $table->integer('nomor_ruangan')->nullable(); 
            $table->string('payment_token', 64)->nullable()->after('id_transaksi');
            $table->timestamp('expired_at')->nullable()->after('waktu');
            $table->timestamps(); 
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};