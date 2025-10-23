<?php

namespace Tests\Unit;

use App\Http\Controllers\ProfileController;
use App\Http\Requests\ProfileUpdateRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse; // DIIMPOR
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Mockery;
use Tests\TestCase;

/**
 * Class ProfileControllerTest
 * Menguji logika unit dari ProfileController dengan mem-mock semua dependency framework.
 */
class ProfileControllerTest extends TestCase
{
    /**
     * Membersihkan Mockery setelah setiap test.
     */
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    // --- UJI METODE EDIT ---

    /**
     * Memastikan metode edit mengembalikan view yang benar dengan objek user.
     */
    public function test_edit_returns_correct_view_with_user_object()
    {
        // 1. Persiapan Mock
        $mockUser = (object)['id' => 1, 'name' => 'John Doe'];
        
        $mockRequest = Mockery::mock(Request::class);
        $mockRequest->shouldReceive('user')->once()->andReturn($mockUser);

        // 2. Eksekusi
        $controller = new ProfileController();
        $response = $controller->edit($mockRequest);

        // 3. Verifikasi
        $this->assertEquals('profile.edit', $response->name());
        $this->assertEquals($mockUser, $response->getData()['user']);
    }

    // --- UJI METODE UPDATE ---

    /**
     * Memastikan informasi profil pengguna diperbarui tanpa mengubah email.
     */
    public function test_update_saves_user_data_without_changing_email()
    {
        // 1. Persiapan Mock
        $originalEmail = 'test@old.com';
        $mockUser = Mockery::mock(User::class);
        
        // Data yang divalidasi dari ProfileUpdateRequest (sama seperti yang lama)
        $validatedData = ['name' => 'New Name', 'email' => $originalEmail]; 

        $mockRequest = Mockery::mock(ProfileUpdateRequest::class);
        $mockRequest->shouldReceive('validated')->once()->andReturn($validatedData);
        $mockRequest->shouldReceive('user')->andReturn($mockUser);

        // Ekspektasi pada User Model
        $mockUser->shouldReceive('fill')->with($validatedData)->once();
        $mockUser->shouldReceive('isDirty')->with('email')->once()->andReturn(false); // Email tidak berubah
        $mockUser->shouldReceive('save')->once()->andReturn(true);

        // Mock Redirect Facade agar mengembalikan objek RedirectResponse tiruan
        $mockRedirectResponse = Mockery::mock(RedirectResponse::class); // MOCK OBJECT AS RedirectResponse
        $mockRedirect = Mockery::mock();
        $mockRedirect->shouldReceive('route')->with('profile.edit')->andReturn($mockRedirect);
        $mockRedirect->shouldReceive('with')->with('status', 'profile-updated')->andReturn($mockRedirectResponse); // KEMBALIKAN OBJECT
        Redirect::swap($mockRedirect);

        // 2. Eksekusi
        $controller = new ProfileController();
        $response = $controller->update($mockRequest);

        // 3. Verifikasi
        $this->assertInstanceOf(RedirectResponse::class, $response); // Verifikasi tipe
    }

    /**
     * Memastikan email_verified_at disetel ke null ketika email pengguna diubah.
     */
    public function test_update_resets_email_verification_when_email_is_changed()
    {
        // 1. Persiapan Mock
        $mockUser = Mockery::mock(User::class);
        
        // Data yang divalidasi (email berubah)
        $validatedData = ['name' => 'New Name', 'email' => 'new@email.com']; 

        $mockRequest = Mockery::mock(ProfileUpdateRequest::class);
        $mockRequest->shouldReceive('validated')->once()->andReturn($validatedData);
        $mockRequest->shouldReceive('user')->andReturn($mockUser);

        // Ekspektasi pada User Model
        $mockUser->shouldReceive('fill')->with($validatedData)->once();
        $mockUser->shouldReceive('isDirty')->with('email')->once()->andReturn(true); // Email berubah!
        
        // PERBAIKAN: Mock panggilan setAttribute yang dilakukan oleh magic __set
        $mockUser->shouldReceive('setAttribute')
                 ->with('email_verified_at', null)
                 ->once(); 

        $mockUser->shouldReceive('save')->once()->andReturn(true);
        
        // Mock Redirect Facade agar mengembalikan objek RedirectResponse tiruan
        $mockRedirectResponse = Mockery::mock(RedirectResponse::class); // MOCK OBJECT AS RedirectResponse
        $mockRedirect = Mockery::mock();
        $mockRedirect->shouldReceive('route')->andReturn($mockRedirect);
        $mockRedirect->shouldReceive('with')->andReturn($mockRedirectResponse); // KEMBALIKAN OBJECT
        Redirect::swap($mockRedirect);

        // 2. Eksekusi
        $controller = new ProfileController();
        $response = $controller->update($mockRequest);

        // 3. Verifikasi
        $this->assertInstanceOf(RedirectResponse::class, $response); // Verifikasi tipe
    }

    // --- UJI METODE DESTROY ---

    /**
     * Memastikan akun pengguna dihapus dengan benar.
     */
    public function test_destroy_deletes_user_and_redirects()
    {
        // 1. Persiapan Mock
        $mockUser = Mockery::mock(User::class);

        $mockRequest = Mockery::mock(Request::class);
        $mockRequest->shouldReceive('user')->andReturn($mockUser);
        
        // 1a. Mocking validasi password
        $mockRequest->shouldReceive('validateWithBag')
                    ->with('userDeletion', ['password' => ['required', 'current_password']])
                    ->once()
                    ->andReturn(null);

        // 1b. Mock Logout, Session, dan Redirect
        Auth::shouldReceive('logout')->once();
        
        $mockSession = Mockery::mock();
        $mockSession->shouldReceive('invalidate')->once();
        $mockSession->shouldReceive('regenerateToken')->once();
        $mockRequest->shouldReceive('session')->andReturn($mockSession);
        
        // Mock Redirect Facade agar mengembalikan objek RedirectResponse tiruan
        $mockRedirectResponse = Mockery::mock(RedirectResponse::class); // MOCK OBJECT AS RedirectResponse
        $mockRedirect = Mockery::mock();
        $mockRedirect->shouldReceive('to')->with('/')->once()->andReturn($mockRedirectResponse); // KEMBALIKAN OBJECT
        Redirect::swap($mockRedirect);

        // 1c. Ekspektasi pada User Model
        $mockUser->shouldReceive('delete')->once();

        // 2. Eksekusi
        $controller = new ProfileController();
        $response = $controller->destroy($mockRequest);

        // 3. Verifikasi
        $this->assertInstanceOf(RedirectResponse::class, $response); // Verifikasi tipe
    }
}