<?php

use App\Http\Controllers\Api\QrScannerController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

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

// Database download endpoint
Route::get('/download-database', function () {
    $disk = Storage::disk('local');
    $filePath = 'database.sqlite';

    if (!$disk->exists($filePath)) {
        return response()->json(['error' => 'Database file not found'], 404);
    }

    return response()->download($disk->path($filePath), 'database.sqlite', [
        'Content-Type' => 'application/x-sqlite3',
    ]);
});

// Route::post('test', [\App\Http\Controllers\Api\QrScannerController::class, 'processScan']);
