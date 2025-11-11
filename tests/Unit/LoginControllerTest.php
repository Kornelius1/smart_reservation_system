<?php

namespace Tests\Unit;

use App\Http\Controllers\LoginController;
use App\Models\User;
use Tests\TestCase;
use Mockery;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\InvalidStateException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Database\Eloquent\Builder;

/**
 * Unit Test untuk LoginController, berfokus pada integrasi Socialite dan Auth.
 */
class LoginControllerTest extends TestCase
{
    /**
     * Pastikan Mockery dibersihkan setelah setiap test.
     */
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    // Helper untuk membuat mock Google User
    private function createMockGoogleUser($email = 'test@example.com', $name = 'Test User')
    {
        $googleUser = Mockery::mock(\Laravel\Socialite\Two\User::class);
        $googleUser->shouldReceive('getEmail')->andReturn($email);
        $googleUser->shouldReceive('getName')->andReturn($name);
        return $googleUser;
    }

    // Helper untuk membuat mock Driver Socialite
    private function mockSocialiteDriver()
    {
        return Mockery::mock('Laravel\Socialite\Contracts\Provider');
    }

    // ===============================================
    // TEST: redirectToGoogle
    // ===============================================

    /**
     * Test untuk memastikan redirectToGoogle mengembalikan RedirectResponse
     * dengan parameter prompt=select_account.
     */
    public function test_redirect_to_google_redirects_correctly()
    {
        $driverMock = $this->mockSocialiteDriver();

        // 1. Mocking pemanggilan with(['prompt' => 'select_account'])
        $driverMock->shouldReceive('with')->with(['prompt' => 'select_account'])->andReturnSelf();
        
        // 2. Mocking pemanggilan redirect() yang mengembalikan Response tiruan
        $driverMock->shouldReceive('redirect')->andReturn(new RedirectResponse('mock-url-google-redirect'));

        // 3. Mocking Facade Socialite
        Socialite::shouldReceive('driver')->with('google')->once()->andReturn($driverMock);

        // ACT
        $controller = new LoginController();
        $response = $controller->redirectToGoogle();

        // ASSERT
        $this->assertInstanceOf(RedirectResponse::class, $response, 'Harus mengembalikan RedirectResponse.');
        $this->assertEquals('mock-url-google-redirect', $response->getTargetUrl());
    }

    // ===============================================
    // TEST: handleGoogleCallback (Success Scenarios)
    // ===============================================

    /**
     * Test case untuk login user yang sudah ada di database.
     */
    public function test_handle_google_callback_logs_in_existing_user()
    {
        // ARRANGE
        $googleUser = $this->createMockGoogleUser('exist@example.com', 'Existing User');
        $existingUser = new User(['email' => 'exist@example.com', 'name' => 'Existing User']);
        
        $driverMock = $this->mockSocialiteDriver();

        // 1. Mock Socialite (jalur stateful sukses)
        Socialite::shouldReceive('driver')->with('google')->andReturn($driverMock);
        $driverMock->shouldReceive('user')->once()->andReturn($googleUser);
        
        // FIX: Aktifkan alias mock untuk method statis pada Model User
        Mockery::mock('alias:'.User::class);

        // 2. Mock Eloquent (User ditemukan)
        $queryMock = Mockery::mock(Builder::class);
        User::shouldReceive('where')->with('email', 'exist@example.com')->andReturn($queryMock);
        $queryMock->shouldReceive('first')->once()->andReturn($existingUser);
        
        // 3. Mock Auth Facade (Penting: memastikan Auth::login dipanggil)
        Auth::shouldReceive('login')->with($existingUser)->once();

        // ACT
        $controller = new LoginController();
        $response = $controller->handleGoogleCallback();

        // ASSERT
        $this->assertInstanceOf(RedirectResponse::class, $response, 'Harus redirect ke dashboard.');
        $this->assertEquals(url('/dashboard'), $response->getTargetUrl());
    }

    /**
     * Test case untuk membuat dan login user baru (belum ada di database).
     */
    public function test_handle_google_callback_creates_and_logs_in_new_user()
    {
        // ARRANGE
        $newEmail = 'new@example.com';
        $googleUser = $this->createMockGoogleUser($newEmail, 'New User');
        $newUser = new User(['email' => $newEmail, 'name' => 'New User']);
        
        $driverMock = $this->mockSocialiteDriver();

        // 1. Mock Socialite
        Socialite::shouldReceive('driver')->with('google')->andReturn($driverMock);
        $driverMock->shouldReceive('user')->once()->andReturn($googleUser);

        // FIX: Aktifkan alias mock untuk method statis pada Model User
        Mockery::mock('alias:'.User::class);

        // 2. Mock Eloquent (User tidak ditemukan, lalu dipanggil create)
        $queryMock = Mockery::mock(Builder::class);
        User::shouldReceive('where')->with('email', $newEmail)->andReturn($queryMock);
        $queryMock->shouldReceive('first')->once()->andReturn(null); // User tidak ditemukan

        // Mocking User::create()
        User::shouldReceive('create')->once()->andReturnUsing(function ($data) use ($newUser) {
            $this->assertEquals('New User', $data['name']);
            $this->assertEquals('customer', $data['role']);
            return $newUser;
        });
        
        // 3. Mock Auth Facade (Penting: memastikan Auth::login dipanggil)
        Auth::shouldReceive('login')->with($newUser)->once();

        // ACT
        $controller = new LoginController();
        $response = $controller->handleGoogleCallback();

        // ASSERT
        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals(url('/dashboard'), $response->getTargetUrl());
    }

    // ===============================================
    // TEST: handleGoogleCallback (Exception Scenarios)
    // ===============================================

    /**
     * Test case untuk fallback ke mode stateless saat terjadi InvalidStateException.
     */
    public function test_handle_google_callback_handles_invalid_state_by_using_stateless_mode()
    {
        // ARRANGE
        $googleUser = $this->createMockGoogleUser();
        $existingUser = new User(['email' => 'test@example.com']);
        
        $statelessDriverMock = $this->mockSocialiteDriver();
        $statelessDriverMock->shouldReceive('user')->once()->andReturn($googleUser);

        $driverMock = $this->mockSocialiteDriver();

        // 1. Mock Socialite: Panggilan user() pertama gagal (InvalidStateException)
        $driverMock->shouldReceive('user')->once()->andThrow(new InvalidStateException());
        
        // 2. Mock Socialite: Panggilan stateless() kemudian user() berhasil
        $driverMock->shouldReceive('stateless')->once()->andReturn($statelessDriverMock);
        Socialite::shouldReceive('driver')->with('google')->andReturn($driverMock);

        // FIX: Aktifkan alias mock untuk method statis pada Model User
        Mockery::mock('alias:'.User::class);

        // 3. Mock Eloquent (agar login berhasil)
        $queryMock = Mockery::mock(Builder::class);
        User::shouldReceive('where')->andReturn($queryMock);
        $queryMock->shouldReceive('first')->andReturn($existingUser);
        
        // 4. Mock Auth
        Auth::shouldReceive('login')->once();

        // ACT
        $controller = new LoginController();
        $response = $controller->handleGoogleCallback();

        // ASSERT
        $this->assertInstanceOf(RedirectResponse::class, $response, 'Setelah fallback, harus berhasil redirect ke dashboard.');
        $this->assertEquals(url('/dashboard'), $response->getTargetUrl());
    }

    /**
     * Test case untuk kegagalan generik sebelum fetch user (misal: koneksi Socialite).
     */
    public function test_handle_google_callback_handles_failure_before_fetch_user()
    {
        // ARRANGE
        $driverMock = $this->mockSocialiteDriver();

        // 1. Mock Socialite: Panggilan user() gagal dengan exception generik (Throwable)
        Socialite::shouldReceive('driver')->with('google')->andReturn($driverMock);
        $driverMock->shouldReceive('user')->once()->andThrow(new \Exception('Mock Socialite Failure'));
        
        // 2. Mock Log Facade (Penting: memastikan error dilog)
        Log::shouldReceive('error')->once()->with(Mockery::on(function ($message) {
            return str_contains($message, 'Google login gagal (sebelum fetch user)');
        }));

        // 3. Pastikan Auth tidak dipanggil
        Auth::shouldReceive('login')->never();

        // ACT
        $controller = new LoginController();
        $response = $controller->handleGoogleCallback();

        // ASSERT
        $this->assertInstanceOf(RedirectResponse::class, $response, 'Harus redirect kembali ke login.');
        $this->assertEquals(url('/login'), $response->getTargetUrl());
        $this->assertArrayHasKey('error', $response->getSession()->all());
    }

    /**
     * Test case untuk kegagalan generik selama interaksi database (setelah fetch user).
     */
    public function test_handle_google_callback_handles_failure_during_user_creation_or_login()
    {
        // ARRANGE
        $googleUser = $this->createMockGoogleUser();
        $driverMock = $this->mockSocialiteDriver();

        // 1. Mock Socialite (sukses fetch user)
        Socialite::shouldReceive('driver')->with('google')->andReturn($driverMock);
        $driverMock->shouldReceive('user')->once()->andReturn($googleUser);

        // FIX: Aktifkan alias mock untuk method statis pada Model User
        Mockery::mock('alias:'.User::class);

        // 2. Mock Eloquent/Database (gagal saat find/create user)
        $queryMock = Mockery::mock(Builder::class);
        User::shouldReceive('where')->andReturn($queryMock);
        // Simulasikan kegagalan I/O database saat first() dipanggil
        $queryMock->shouldReceive('first')->once()->andThrow(new \Exception('Mock Database Failure'));
        
        // 3. Mock Log Facade (Penting: memastikan error dilog)
        Log::shouldReceive('error')->once()->with(Mockery::on(function ($message) {
            return str_contains($message, 'Google login gagal (setelah fetch user)');
        }));

        // 4. Pastikan Auth tidak dipanggil
        Auth::shouldReceive('login')->never();

        // ACT
        $controller = new LoginController();
        $response = $controller->handleGoogleCallback();

        // ASSERT
        $this->assertInstanceOf(RedirectResponse::class, $response, 'Harus redirect kembali ke login.');
        $this->assertEquals(url('/login'), $response->getTargetUrl());
        $this->assertArrayHasKey('error', $response->getSession()->all());
    }
}
