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

Route::post('/send-message', function (Request $request) {
    try {
        // 1. Parse request body
        $data = $request->all();
        
        if (empty($data)) {
            $data = json_decode(file_get_contents('php://input'), true) ?? [];
        }

        // 2. Gunakan Validator manual
        $validator = Validator::make($data, [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'message' => 'required|string',
        ]);

        // 3. Jika validasi gagal, respond dengan JSON
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal', 
                'errors' => $validator->errors()
            ], 422);
        }

        // 4. Simpan contact message
        Contact::create($validator->validated());

        return response()->json([
            'success' => true,
            'message' => 'Pesan berhasil dikirim!'
        ], 201);
        
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Terjadi kesalahan: ' . $e->getMessage()
        ], 500);
    }
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