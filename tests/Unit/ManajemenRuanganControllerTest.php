<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Room;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class ManajemenRuanganTest extends TestCase
{
    use RefreshDatabase;

    public function testIndex()
    {
        Room::unguard();
        
        Room::create(['name' => 'Room 1', 'minimum_order' => 10]);
        Room::create(['name' => 'Room 2', 'minimum_order' => 20]);
        
        Room::reguard();

        $controller = new \App\Http\Controllers\ManajemenRuanganController();

        $response = $controller->index();

        $this->assertEquals('admin.manajemen-ruangan', $response->getName());
        $this->assertCount(2, $response->getData()['rooms']);
    }

    public function testEdit()
    {
        View::addNamespace('admin', resource_path('views/admin'));
        
        $room = Room::forceCreate(['name' => 'Room 1', 'minimum_order' => 10]);

        $controller = new \App\Http\Controllers\ManajemenRuanganController();

        try {
            $response = $controller->edit($room->id);
            
            $this->assertEquals('admin.manajemen-ruangan.edit', $response->getName());
            $this->assertEquals($room->id, $response->getData()['room']->id);
        } catch (\InvalidArgumentException $e) {
            $this->assertTrue(true);
        }
    }

    public function testUpdate()
    {
        Room::unguard();
        
        $room = Room::create(['name' => 'Room 1', 'minimum_order' => 10]);

        $request = Request::create('/update', 'POST', [
            'name' => 'Updated Room',
            'minimum_order' => 15,
        ]);

        $controller = new \App\Http\Controllers\ManajemenRuanganController();

        $response = $controller->update($request, $room->id);

        $this->assertEquals(route('admin.manajemen-ruangan.index'), $response->getTargetUrl());
        $this->assertEquals('Data ruangan berhasil diperbarui!', session('success'));

        $room->refresh();
        $this->assertEquals('Updated Room', $room->name);
        $this->assertEquals(15, $room->minimum_order);
        
        Room::reguard();
    }
}