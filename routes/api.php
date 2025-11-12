<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DokuController;
use App\Http\Controllers\Api\ProductController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/doku/test-notification', function(Request $request) {
    Log::info('TEST NOTIF BODY:', $request->all());
    return response()->json(['success' => true]);
});


Route::get('/products', [ProductController::class, 'index'])->name('api.products.index');

Route::post('/doku/notification', [DokuController::class, 'handleNotification'])
     ->name('doku.notification');