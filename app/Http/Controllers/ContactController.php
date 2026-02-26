<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contact;

class ContactController extends Controller
{
    /**
     * Menyimpan pesan kontak dari form
     */
    public function sendMessage(Request $request)
    {
        try {
            // Validasi input
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'message' => 'required|string|min:1|max:1000'
            ]);

            // Simpan ke database
            Contact::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Pesan Anda telah dikirim dengan berhasil! Terima kasih.'
            ], 201);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get semua pesan (untuk admin)
     */
    public function index()
    {
        $contacts = Contact::latest()->get();
        return response()->json($contacts);
    }
}
