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
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            
            // Kolom sesuai dengan model Room
            $table->string('nama_ruangan')->unique(); // unique() adalah asumsi yang baik untuk nama ruangan
            $table->integer('kapasitas');
            $table->integer('minimum_order');
            $table->string('lokasi');
            $table->text('fasilitas')->nullable(); // Menggunakan text() agar bisa menampung banyak fasilitas
            $table->text('keterangan')->nullable(); // Menggunakan text() dan nullable jika keterangan opsional
            $table->string('status')->default('tersedia');
            $table->string('image_url')->nullable()->after('status'); // Memberi nilai default 'tersedia'

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rooms');
    }
};