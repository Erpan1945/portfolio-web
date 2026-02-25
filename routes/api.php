<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CvController;
use App\Http\Controllers\CertificateController;
use App\Http\Controllers\SettingController; // <-- Jangan lupa tambahkan ini
use App\Models\Contact;

// --- ROUTE PUBLIK (Bisa diakses tanpa login) ---

Route::get('/cv', [CvController::class, 'index']); 
Route::post('/login', [AuthController::class, 'login']);
Route::get('/projects', [ProjectController::class, 'index']);
Route::get('/certificates', [CertificateController::class, 'index']);
Route::post('/contact', function (Request $request) {
    // Validasi data yang masuk
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|max:255',
        'message' => 'required|string',
    ]);

    // Simpan ke database
    Contact::create($validated);

    return response()->json(['message' => 'Pesan berhasil dikirim!']);
});

// Endpoint untuk mengambil URL foto profil/landing page
Route::get('/settings/photo', [SettingController::class, 'getPhoto']);


// --- ROUTE RAHASIA (Harus Login) ---
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    
    Route::post('/cv', [CvController::class, 'store']);
    Route::delete('/cv/{id}', [CvController::class, 'destroy']);
    
    // Upload Project (jika ada)
    Route::post('/projects', [ProjectController::class, 'store']);
    Route::post('/projects/{id}', [ProjectController::class, 'update']);
    Route::delete('/projects/{id}', [ProjectController::class, 'destroy']);
    
    // Endpoint untuk MENGUBAH foto (Hanya Admin)
    Route::post('/settings/photo', [SettingController::class, 'updatePhoto']);
});


// Route User Default
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');