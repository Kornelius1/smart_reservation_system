<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->decimal('price', 10, 2);

            // PENAMBAHAN 1: Kolom stock, default 0
            $table->integer('stock')->default(0);

            $table->string('image_url');
            $table->string('category'); // Contoh: 'coffee', 'snack', 'heavy-meal'

            // PENAMBAHAN 2: Kolom tersedia (status), default false
            // Ini akan otomatis di-set 'true' oleh Controller jika stok > 0
            $table->boolean('tersedia')->default(false);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};