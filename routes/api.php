<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CvController;
use App\Http\Controllers\CertificateController;
use App\Http\Controllers\SettingController; 
use App\Models\Contact;
use Illuminate\Support\Facades\Validator; // <-- PASTIKAN BARIS INI ADA

// --- ROUTE PUBLIK ---
Route::get('/cv', [CvController::class, 'index']); 
Route::post('/login', [AuthController::class, 'login']);
Route::get('/projects', [ProjectController::class, 'index']);
Route::get('/certificates', [CertificateController::class, 'index']);

// GANTI BAGIAN INI:
Route::post('/send-message', function (Request $request) {
    // 1. Gunakan Validator manual, JANGAN gunakan $request->validate()
    $validator = Validator::make($request->all(), [
        'name' => 'required|string|max:255',
        'email' => 'required|email|max:255',
        'message' => 'required|string',
    ]);

    // 2. Jika validasi gagal, paksa Laravel merespons dengan JSON (mencegah redirect 405)
    if ($validator->fails()) {
        return response()->json([
            'message' => 'Validasi gagal', 
            'errors' => $validator->errors()
        ], 422);
    }

    // 3. Simpan jika berhasil
    Contact::create($validator->validated());

    return response()->json(['message' => 'Pesan berhasil dikirim!']);
});

Route::get('/settings/photo', [SettingController::class, 'getPhoto']);

// --- ROUTE RAHASIA ---
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/cv', [CvController::class, 'store']);
    Route::delete('/cv/{id}', [CvController::class, 'destroy']);
    Route::post('/projects', [ProjectController::class, 'store']);
    Route::post('/projects/{id}', [ProjectController::class, 'update']);
    Route::delete('/projects/{id}', [ProjectController::class, 'destroy']);
    Route::post('/settings/photo', [SettingController::class, 'updatePhoto']);
});

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');