<?php

use App\Http\Controllers\Api\StoreApiController;
use App\Http\Controllers\Api\TransferApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->group(function () {

    // User info
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // ===== Store API Routes =====
    Route::prefix('stores')->name('api.stores.')->group(function () {

        // List and filter stores
        Route::get('/', [StoreApiController::class, 'index'])->name('index');

        // Active stores only
        Route::get('/active', [StoreApiController::class, 'active'])->name('active');

        // User's stores
        Route::get('/user', [StoreApiController::class, 'userStores'])->name('user');

        // Show specific store
        Route::get('/{id}', [StoreApiController::class, 'show'])->name('show');

        // Create store
        Route::post('/', [StoreApiController::class, 'store'])->name('store');

        // Update store
        Route::put('/{id}', [StoreApiController::class, 'update'])->name('update');
        Route::patch('/{id}', [StoreApiController::class, 'update'])->name('patch');

        // Delete store
        Route::delete('/{id}', [StoreApiController::class, 'destroy'])->name('destroy');

        // Store actions
        Route::post('/{id}/assign-user', [StoreApiController::class, 'assignUser'])->name('assign-user');
        Route::delete('/{storeId}/remove-user/{userId}', [StoreApiController::class, 'removeUser'])->name('remove-user');
        Route::post('/{id}/switch', [StoreApiController::class, 'switchStore'])->name('switch');

        // Store stock
        Route::get('/{id}/stock', [StoreApiController::class, 'stock'])->name('stock');
    });

    // ===== Transfer API Routes =====
    Route::prefix('transfers')->name('api.transfers.')->group(function () {

        // List and filter transfers
        Route::get('/', [TransferApiController::class, 'index'])->name('index');

        // Show specific transfer
        Route::get('/{id}', [TransferApiController::class, 'show'])->name('show');

        // Create transfer
        Route::post('/', [TransferApiController::class, 'store'])->name('store');

        // Transfer actions
        Route::post('/{id}/approve', [TransferApiController::class, 'approve'])->name('approve');
        Route::post('/{id}/receive', [TransferApiController::class, 'receive'])->name('receive');
        Route::post('/{id}/cancel', [TransferApiController::class, 'cancel'])->name('cancel');
    });
});
