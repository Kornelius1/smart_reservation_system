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
            $table->id(); // <--- ID internal (Primary Key, auto-increment)
            $table->string('id_transaksi')->unique(); // <--- ID Anda (TRS001), dibuat unik
            $table->string('nama'); // Nama pemesan
            $table->date('tanggal'); // Tanggal reservasi
            $table->time('waktu'); // Waktu reservasi
            $table->timestamps(); // <--- Otomatis membuat kolom created_at dan updated_at
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