<?php

namespace Tests\Unit; 

use Tests\TestCase;
use App\Models\Menu;
use App\Models\User; 
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use PHPUnit\Framework\Attributes\Test;

class MenuControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    public function setUp(): void
    {
        parent::setUp();
     
        $this->user = User::factory()->create();


        Storage::fake('public');

 
        Menu::unguard();
    }

    public function tearDown(): void
    {
        Menu::reguard();
        parent::tearDown();
    }

    // ===============================================
    // TEST UNTUK METHOD INDEX
    // ===============================================

    #[Test]
    public function test_index_displays_view_with_menu_data()
    {
        // Arrange: Buat data menu manual
        Menu::create(['name' => 'Nasi Goreng', 'category' => 'heavy-meal', 'price' => 15000, 'image_url' => 'dummy.jpg', 'tersedia' => true]);
        Menu::create(['name' => 'Es Teh', 'category' => 'juice', 'price' => 5000, 'image_url' => 'dummy.jpg', 'tersedia' => true]);
        Menu::create(['name' => 'Sop Buntut', 'category' => 'heavy-meal', 'price' => 35000, 'image_url' => 'dummy.jpg', 'tersedia' => true]);

        // Act: Panggil controller langsung
        $controller = new \App\Http\Controllers\MenuController();
        $response = $controller->index();

        // Assert: Cek nama view dan data yang dikirim
        $this->assertEquals('admin.manajemen-menu', $response->getName());

        // Cek data $menuItems
        $this->assertCount(3, $response->getData()['menuItems']);
        $this->assertEquals('Es Teh', $response->getData()['menuItems'][0]->name);
        $this->assertEquals('Nasi Goreng', $response->getData()['menuItems'][1]->name);

        // Cek data $categories (unik dan terurut)
        $categories = $response->getData()['categories'];
        $this->assertCount(2, $categories);
        $this->assertEquals('heavy-meal', $categories[0]);
        $this->assertEquals('juice', $categories[1]);
    }

    // ===============================================
    // TEST UNTUK METHOD STORE (Simpan Baru)
    // ===============================================
    #[Test]
    public function test_store_creates_new_menu()
    {
        // Arrange: Buat kategori awal
        Menu::create(['name' => 'Dummy Coffee', 'category' => 'coffee', 'price' => 1000, 'image_url' => 'dummy.jpg']);
        $image = UploadedFile::fake()->image('kopi.jpg');

        // Buat objek Request palsu dengan cara yang benar untuk file
        $request = new Request();
        $request->merge([
            'nama_menu'     => 'Kopi Susu Baru',
            'harga_menu'    => 18000,
            'kategori_menu' => 'coffee',
        ]);
        $request->files->set('gambar_menu', $image);

        // Act: Panggil controller langsung
        $controller = new \App\Http\Controllers\MenuController();
        $response = $controller->store($request);

        // Assert: Cek redirect, session, dan database
        $this->assertInstanceOf(\Illuminate\Http\RedirectResponse::class, $response);
        $this->assertEquals(route('menu.index'), $response->getTargetUrl());
        $this->assertEquals('Menu baru berhasil ditambahkan!', session('success'));

        $this->assertDatabaseHas('products', [
            'name' => 'Kopi Susu Baru',
            'price' => 18000,
            'category' => 'coffee',
            'tersedia' => true,
        ]);

        $menu = Menu::where('name', 'Kopi Susu Baru')->first();
        $this->assertNotNull($menu, "Menu 'Kopi Susu Baru' tidak ditemukan di database.");
        Storage::disk('public')->assertExists($menu->image_url);
    }

    #[Test]
    public function test_store_fails_validation_with_invalid_data()
    {
        // Arrange: Buat kategori agar validasi 'in' bisa dites
        Menu::create(['name' => 'Dummy Drink', 'category' => 'Minuman', 'price' => 1000, 'image_url' => 'dummy.jpg']);

        $request = Request::create('/store', 'POST', [
            'nama_menu'     => '',
            'harga_menu'    => -5000,
            'kategori_menu' => 'Kategori Baru Yg Tidak Ada',
            // gambar_menu kosong (required)
        ]);

        // Act & Assert: Gunakan try-catch untuk menangkap ValidationException
        try {
            $controller = new \App\Http\Controllers\MenuController();
            $controller->store($request);
            $this->fail('ValidationException tidak terlempar padahal seharusnya.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            $errors = $e->errors();
            $this->assertArrayHasKey('nama_menu', $errors);
            $this->assertArrayHasKey('harga_menu', $errors);
            $this->assertArrayHasKey('kategori_menu', $errors);
            $this->assertArrayHasKey('gambar_menu', $errors);
        }

        $this->assertDatabaseMissing('products', ['name' => '']); // Perbaikan nama kolom
        $this->assertDatabaseCount('products', 1);
    }


    // ===============================================
    // TEST UNTUK METHOD UPDATE
    // ===============================================
    #[Test]
    public function test_update_modifies_existing_menu_without_image_change()
    {
        // Arrange: Buat menu awal dan kategori valid
        Menu::create(['name' => 'Dummy Juice', 'category' => 'juice', 'price' => 1000, 'image_url' => 'dummy.jpg']);
        $menu = Menu::forceCreate([
            'name' => 'Jus Lama',
            'price' => 10000,
            'category' => 'juice',
            'image_url' => 'images/menu/old.jpg',
            'tersedia' => true,
        ]);

        $request = new Request();
        $request->setMethod('PUT');
        $request->merge([
            'ubah_nama_menu'    => 'Jus Baru Segar',
            'ubah_harga_menu'   => 12000,
            'ubah_kategori_menu'=> 'juice',
        ]);

        // Act: Panggil controller langsung
        $controller = new \App\Http\Controllers\MenuController();
        $response = $controller->update($request, $menu->id);

        // Assert: Cek redirect, session, dan database
        $this->assertInstanceOf(\Illuminate\Http\RedirectResponse::class, $response);
        $this->assertEquals(route('menu.index'), $response->getTargetUrl());
        $this->assertEquals('Detail menu berhasil diperbarui!', session('success'));

        $this->assertDatabaseHas('products', [
            'id' => $menu->id,
            'name' => 'Jus Baru Segar',
            'price' => 12000,
            'image_url' => 'images/menu/old.jpg',
        ]);
        $this->assertDatabaseMissing('products', ['name' => 'Jus Lama']);
    }

    #[Test]
    public function test_update_replaces_image()
    {
        // Arrange: Buat kategori, file lama, dan menu lama
        Menu::create(['name' => 'Dummy Snack', 'category' => 'snack', 'price' => 1000, 'image_url' => 'dummy.jpg']);
        $oldImagePath = 'images/menu/old_burger.jpg';
        Storage::disk('public')->put($oldImagePath, 'dummy old content');
        $menu = Menu::forceCreate([
            'name' => 'Burger Lama',
            'price' => 15000,
            'category' => 'snack',
            'image_url' => $oldImagePath,
            'tersedia' => true,
        ]);
        $newImage = UploadedFile::fake()->image('new_burger.jpg');

        // Buat objek Request palsu dengan file baru
        $request = new Request();
        $request->setMethod('PUT');
        $request->merge([
            'ubah_nama_menu'    => 'Burger Baru Spesial',
            'ubah_harga_menu'   => 17000,
            'ubah_kategori_menu'=> 'snack',
        ]);
        $request->files->set('ubah_gambar_menu', $newImage);

        // Act
        $controller = new \App\Http\Controllers\MenuController();
        $response = $controller->update($request, $menu->id);

        // Assert
        $this->assertInstanceOf(\Illuminate\Http\RedirectResponse::class, $response);
        $this->assertEquals(route('menu.index'), $response->getTargetUrl());
        $this->assertEquals('Detail menu berhasil diperbarui!', session('success'));

        $menu->refresh();
        $this->assertEquals('Burger Baru Spesial', $menu->name);
        Storage::disk('public')->assertMissing($oldImagePath);
        Storage::disk('public')->assertExists($menu->image_url);
        $this->assertNotEquals($oldImagePath, $menu->image_url);
    }

    // ===============================================
    // TEST UNTUK METHOD UPDATESTATUS
    // ===============================================
    #[Test]
    public function test_update_status_changes_availability()
    {
        // Arrange: Buat menu awal
        $menu = Menu::forceCreate([
            'name' => 'Menu Status',
            'price' => 10000,
            'category' => 'snack',
            'image_url' => 'dummy.jpg',
            'tersedia' => true, // Awalnya true
        ]);

        // Buat objek Request palsu untuk mengubah jadi false
        $request = Request::create('/updateStatus', 'PATCH', ['tersedia' => 'false']);

        // Act: Panggil controller langsung
        $controller = new \App\Http\Controllers\MenuController();
        $response = $controller->updateStatus($request, $menu->id);

        // Assert: Cek redirect, session, dan database
        $this->assertInstanceOf(\Illuminate\Http\RedirectResponse::class, $response);
        $this->assertEquals(route('menu.index'), $response->getTargetUrl());
        $this->assertEquals('Status menu berhasil diubah.', session('success'));

        $this->assertDatabaseHas('products', [
            'id' => $menu->id,
            'tersedia' => false,
        ]);

        // Arrange 2: Request untuk mengubah jadi true
        $requestTrue = Request::create('/updateStatus', 'PATCH', ['tersedia' => 'true']);

        // Act 2: Panggil lagi untuk mengubah kembali
        $controller->updateStatus($requestTrue, $menu->id);

        // Assert 2: Cek DB lagi
         $this->assertDatabaseHas('products', [
            'id' => $menu->id,
            'tersedia' => true,
        ]);
    }

    // ===============================================
    // TEST UNTUK METHOD DESTROY
    // ===============================================
    #[Test]
    public function test_destroy_deletes_menu_and_image()
    {
        // Arrange: Buat file dan menu manual
        $imagePath = 'images/menu/to_delete.jpg';
        Storage::disk('public')->put($imagePath, 'dummy');
        $menu = Menu::forceCreate([
            'name' => 'Menu Hapus',
            'price' => 10000,
            'category' => 'snack',
            'image_url' => $imagePath,
            'tersedia' => true,
        ]);
        $this->assertDatabaseHas('products', ['id' => $menu->id]);
        Storage::disk('public')->assertExists($imagePath);

        // Act: Panggil controller langsung
        $controller = new \App\Http\Controllers\MenuController();
        $response = $controller->destroy($menu->id);

        // Assert: Cek redirect, session, database, dan storage
        $this->assertInstanceOf(\Illuminate\Http\RedirectResponse::class, $response);
        $this->assertEquals(route('menu.index'), $response->getTargetUrl());
        $this->assertEquals('Menu berhasil dihapus.', session('success'));

        $this->assertDatabaseMissing('products', ['id' => $menu->id]);
        Storage::disk('public')->assertMissing($imagePath);
    }

     #[Test]
    public function test_destroy_does_not_delete_external_image()
    {
        // Arrange: Buat menu dengan URL eksternal
        $externalUrl = 'https://contoh.com/gambar.jpg';
        $menu = Menu::forceCreate([
            'name' => 'Menu URL Luar',
            'price' => 10000,
            'category' => 'snack',
            'image_url' => $externalUrl,
            'tersedia' => true,
        ]);
        $this->assertDatabaseHas('products', ['id' => $menu->id]);

        // Act
        $controller = new \App\Http\Controllers\MenuController();
        $response = $controller->destroy($menu->id);

        // Assert
        $this->assertInstanceOf(\Illuminate\Http\RedirectResponse::class, $response);
        $this->assertEquals(route('menu.index'), $response->getTargetUrl());
        $this->assertEquals('Menu berhasil dihapus.', session('success'));
        $this->assertDatabaseMissing('products', ['id' => $menu->id]);

        $this->assertTrue(true); // Tes berhasil jika tidak ada error
    }

} // <-- Kurung kurawal penutup Class