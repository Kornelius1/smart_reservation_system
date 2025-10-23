<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

class ProfileControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testEdit()
    {
        $user = User::forceCreate([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
        ]);

        Auth::login($user);

        $request = Request::create('/profile', 'GET');
        $request->setUserResolver(function () use ($user) {
            return $user;
        });

        $controller = new \App\Http\Controllers\ProfileController();
        $response = $controller->edit($request);

        $this->assertEquals('profile.edit', $response->getName());
        $this->assertEquals($user->id, $response->getData()['user']->id);
    }

    public function testUpdate()
    {
        if (!Route::has('profile.edit')) {
            Route::get('/profile', function () {
                return redirect('/profile');
            })->name('profile.edit');
        }
        
        app('router')->getRoutes()->refreshNameLookups();
        
        $user = User::forceCreate([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
        ]);

        Auth::login($user);

        $data = [
            'name' => 'Updated Name',
            'email' => 'test@example.com',
        ];

        $request = \App\Http\Requests\ProfileUpdateRequest::create('/profile', 'PATCH', $data);
        $request->setUserResolver(function () use ($user) {
            return $user;
        });
        
        $request->setContainer(app());
        $request->validateResolved();

        $controller = new \App\Http\Controllers\ProfileController();
        $response = $controller->update($request);

        $this->assertTrue($response->isRedirect());
        $this->assertEquals('profile-updated', session('status'));

        $user->refresh();
        $this->assertEquals('Updated Name', $user->name);
    }

    public function testDestroy()
    {
        $user = User::forceCreate([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
        ]);

        $this->actingAs($user);

        $request = Request::create('/profile', 'DELETE', [
            'password' => 'password123',
        ]);
        
        $request->setLaravelSession(app('session.store'));
        $request->setUserResolver(function () use ($user) {
            return $user;
        });

        $controller = new \App\Http\Controllers\ProfileController();
        $response = $controller->destroy($request);

        $this->assertTrue($response->isRedirect());
        $this->assertStringContainsString('localhost', $response->getTargetUrl());
        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }
}