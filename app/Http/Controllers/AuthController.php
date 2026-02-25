<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        try {
            // Parse JSON body jika ada
            $data = $request->all();
            if (empty($data)) {
                $data = json_decode(file_get_contents('php://input'), true) ?? [];
            }

            // Validasi input dengan manual validator
            $validator = \Illuminate\Support\Facades\Validator::make($data, [
                'email' => ['required', 'email'],
                'password' => ['required'],
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal',
                    'errors' => $validator->errors()
                ], 422);
            }

            $credentials = $validator->validated();

            // Coba login
            if (Auth::attempt($credentials)) {
                
                /** @var \App\Models\User $user */
                $user = Auth::user(); 

                // Buat token rahasia untuk sesi login
                $token = $user->createToken('admin-token')->plainTextToken;

                return response()->json([
                    'success' => true,
                    'message' => 'Login berhasil!',
                    'token' => $token,
                    'user' => $user
                ], 200);
            }

            return response()->json([
                'success' => false,
                'message' => 'Email atau password salah.'
            ], 401);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function logout(Request $request)
    {
        // Hapus token yang sedang dipakai saat ini
        $request->user()->currentAccessToken()->delete();
        
        return response()->json(['message' => 'Logout berhasil']);
    }
}