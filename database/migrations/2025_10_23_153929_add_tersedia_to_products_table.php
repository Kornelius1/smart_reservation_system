<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * (Perintah "MAJU" / Menambah Kolom)
     */
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            
            // INI KODE UNTUK MENAMBAH KOLOM
            // Kita tambahkan kolom 'tersedia' (tipe boolean),
            // nilai default-nya true,
            // dan letakkan setelah kolom 'image_url'
            $table->boolean('tersedia')->default(true)->after('image_url');
        });
    }

    /**
     * Reverse the migrations.
     * (Perintah "MUNDUR" / Menghapus Kolom)
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            
            // INI KODE UNTUK MENGHAPUS KOLOM (JIKA ROLLBACK)
            $table->dropColumn('tersedia');
        });
    }
};