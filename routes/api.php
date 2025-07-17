<?php

use App\Http\Controllers\Api\QrScannerController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// QR Scanner API routes
Route::middleware([])->prefix('qr-scanner')->group(function () {
    Route::post('/process-scan', [QrScannerController::class, 'processScan'])->name('api.qr-scanner.process-scan');
    Route::get('/test', function () {
        return response()->json([
            'message' => 'QR Scanner API is working',
            'user' => \Illuminate\Support\Facades\Auth::user()?->name
        ]);
    })->name('api.qr-scanner.test');
});
