<?php

use App\Http\Controllers\LoginController;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\InvalidStateException;
use Laravel\Socialite\Two\User as SocialiteUser;

uses(RefreshDatabase::class);

test('redirectToGoogle returns redirect response', function () {
    // Mock Socialite driver
    $mockDriver = mock();
    $mockDriver->shouldReceive('with')
        ->with(['prompt' => 'select_account'])
        ->andReturnSelf();
    $mockDriver->shouldReceive('redirect')
        ->andReturn('redirect response');

    Socialite::shouldReceive('driver')
        ->with('google')
        ->andReturn($mockDriver);

    $controller = new LoginController();
    $response = $controller->redirectToGoogle();

    expect($response)->toBe('redirect response');
});

test('handleGoogleCallback logs in existing user successfully', function () {
    // Create existing user
    $user = User::factory()->create([
        'email' => 'test@example.com',
        'name' => 'Test User',
    ]);

    // Mock Socialite user
    $mockSocialiteUser = mock(SocialiteUser::class);
    $mockSocialiteUser->shouldReceive('getEmail')->andReturn('test@example.com');
    $mockSocialiteUser->shouldReceive('getName')->andReturn('Test User');

    // Mock Socialite driver
    $mockDriver = mock();
    $mockDriver->shouldReceive('user')->andReturn($mockSocialiteUser);

    Socialite::shouldReceive('driver')
        ->with('google')
        ->andReturn($mockDriver);

    $controller = new LoginController();
    $response = $controller->handleGoogleCallback();

    expect($response->getTargetUrl())->toBe('http://localhost/dashboard');
});

test('handleGoogleCallback creates new user and logs in', function () {
    // Mock Socialite user
    $mockSocialiteUser = mock(SocialiteUser::class);
    $mockSocialiteUser->shouldReceive('getEmail')->andReturn('new@example.com');
    $mockSocialiteUser->shouldReceive('getName')->andReturn('New User');

    // Mock Socialite driver
    $mockDriver = mock();
    $mockDriver->shouldReceive('user')->andReturn($mockSocialiteUser);

    Socialite::shouldReceive('driver')
        ->with('google')
        ->andReturn($mockDriver);

    $controller = new LoginController();
    $response = $controller->handleGoogleCallback();

    expect($response->getTargetUrl())->toBe('http://localhost/dashboard');
});

test('handleGoogleCallback handles InvalidStateException with stateless fallback', function () {
    // Create existing user
    $user = User::factory()->create([
        'email' => 'test@example.com',
        'name' => 'Test User',
    ]);

    // Mock Socialite to throw InvalidStateException first
    $mockDriver = mock();
    $mockDriver->shouldReceive('user')
        ->andThrow(new InvalidStateException('Invalid state'));

    $mockSocialiteUser = mock(SocialiteUser::class);
    $mockSocialiteUser->shouldReceive('getEmail')->andReturn('test@example.com');
    $mockSocialiteUser->shouldReceive('getName')->andReturn('Test User');

    $mockStatelessDriver = mock();
    $mockStatelessDriver->shouldReceive('stateless')->andReturnSelf();
    $mockStatelessDriver->shouldReceive('user')->andReturn($mockSocialiteUser);

    Socialite::shouldReceive('driver')
        ->with('google')
        ->andReturn($mockDriver, $mockStatelessDriver);

    $controller = new LoginController();
    $response = $controller->handleGoogleCallback();

    expect($response->getTargetUrl())->toBe('http://localhost/dashboard');
});

test('handleGoogleCallback handles general exception in user fetch', function () {
    // Mock Socialite to throw exception
    $mockDriver = mock();
    $mockDriver->shouldReceive('user')
        ->andThrow(new \Exception('Fetch failed'));

    Socialite::shouldReceive('driver')
        ->with('google')
        ->andReturn($mockDriver);

    // Mock Log
    Log::shouldReceive('error')
        ->with('Google login gagal (sebelum fetch user): Fetch failed')
        ->once();

    $controller = new LoginController();
    $response = $controller->handleGoogleCallback();

    expect($response->getTargetUrl())->toBe('http://localhost/login');
});

test('handleGoogleCallback handles exception after user fetch', function () {
    // Mock Socialite user
    $mockSocialiteUser = mock(SocialiteUser::class);
    $mockSocialiteUser->shouldReceive('getEmail')->andReturn('test@example.com');
    $mockSocialiteUser->shouldReceive('getName')->andReturn('Test User');

    // Mock Socialite driver
    $mockDriver = mock();
    $mockDriver->shouldReceive('user')->andReturn($mockSocialiteUser);

    Socialite::shouldReceive('driver')
        ->with('google')
        ->andReturn($mockDriver);

    // Mock Auth to throw exception during login
    Auth::shouldReceive('login')
        ->andThrow(new \Exception('Login failed'));

    $controller = new LoginController();
    $response = $controller->handleGoogleCallback();

    expect($response->getTargetUrl())->toBe('http://localhost/login');
});
