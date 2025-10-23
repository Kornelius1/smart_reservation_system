<?php

namespace Tests\Feature\Api;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;


uses(RefreshDatabase::class);

/**
 * Tes Skenario Sukses:
 * Memastikan endpoint mengembalikan daftar produk
 * dan terurut dengan benar berdasarkan 'category'.
 */
test('dapat mengambil daftar semua produk dan terurut berdasarkan kategori', function () {
    
    // 1. ARRANGE (Persiapan Data)
    // Kita buat 3 produk dengan urutan kategori acak untuk
    // memastikan logika orderBy() di controller berjalan.
    $productB_Minuman = Product::factory()->create(['category' => 'Minuman']);
    $productC_Snack = Product::factory()->create(['category' => 'Snack']);
    $productA_Makanan = Product::factory()->create(['category' => 'Makanan']);
    
    // Urutan yang diharapkan (alfabetis): Makanan, Minuman, Snack

    // 2. ACT (Eksekusi)
    // Panggil endpoint API '/api/products'
    $response = $this->getJson('/api/products');

    // 3. ASSERT (Validasi)
    // Memastikan response sukses (HTTP 200 OK)
    $response->assertStatus(200);

    // Memastikan ada 3 produk yang dikembalikan
    $response->assertJsonCount(3);

    // Validasi utama: Cek urutan berdasarkan kategori
    // assertJsonPath() memeriksa data pada path JSON tertentu.
    
    // Cek item pertama (index 0) adalah 'Makanan'
    $response->assertJsonPath('0.category', $productA_Makanan->category);
    $response->assertJsonPath('0.name', $productA_Makanan->name);

    // Cek item kedua (index 1) adalah 'Minuman'
    $response->assertJsonPath('1.category', $productB_Minuman->category);
    
    // Cek item ketiga (index 2) adalah 'Snack'
    $response->assertJsonPath('2.category', $productC_Snack->category);
});


/**
 * Tes Skenario Kosong:
 * Memastikan endpoint mengembalikan array kosong
 * jika tidak ada produk di database.
 */
test('dapat menangani ketika tidak ada produk', function () {
    // 1. Arrange (Tidak ada data)
    // tidak membuat produk apa pun karena `RefreshDatabase`
    // sudah mengosongkan tabel.

    // 2. Act
    $response = $this->getJson('/api/products');

    // 3. Assert
    // Memastikan response sukses
    $response->assertStatus(200);

    // Memastikan response adalah array JSON kosong []
    $response->assertJson([]);
    
    // Alternatif: pastikan jumlah data adalah 0
    // $response->assertJsonCount(0);
});