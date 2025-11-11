<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Migrasi ini akan membuat tabel 'reservation_product'
// untuk menghubungkan 'reservations' dan 'products'

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('reservation_product', function (Blueprint $table) {
            $table->id();
            
            // Relasi ke tabel reservations
            // Ini sudah benar merujuk ke 'id_reservasi' Anda
            $table->foreignId('reservation_id')
                  ->constrained('reservations', 'id_reservasi') // <-- Menggunakan 'id_reservasi'
                  ->cascadeOnDelete();

            // Relasi ke tabel products (Asumsi primary key 'id')
            $table->foreignId('product_id')
                  ->constrained('products')
                  ->cascadeOnDelete();
            
            $table->integer('quantity'); // Jumlah item
            $table->decimal('price', 15, 2); // Harga item saat dibeli
            
            // $table->timestamps(); // Opsional
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservation_product');
    }
};

