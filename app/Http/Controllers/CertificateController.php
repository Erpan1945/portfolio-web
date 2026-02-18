<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Storage;

class CertificateController extends Controller
{
    public function index()
    {
        // Urutkan dari yang terbaru (berdasarkan issued_date)
        return response()->json(Certificate::orderBy('issued_date', 'desc')->get());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'issuer' => 'required|string',
            'issued_date' => 'required|date',
            'credential_url' => 'nullable|url',
            'image' => 'nullable|image|max:2048' // Validasi gambar
        ]);

        if ($request->hasFile('image')) {
            // Upload ke S3 atau Local Storage
            $path = $request->file('image')->store('certificates', 's3'); // Ubah 's3' ke 'public' jika pakai local
            $validated['image'] = env('AWS_ENDPOINT') . '/' . env('AWS_BUCKET') . '/' . $path;
        }

        Certificate::create($validated);
        return response()->json(['message' => 'Certificate created successfully']);
    }

    public function update(Request $request, $id)
    {
        $certificate = Certificate::findOrFail($id);
        
        $validated = $request->validate([
            'name' => 'required|string',
            'issuer' => 'required|string',
            'issued_date' => 'required|date',
            'credential_url' => 'nullable|url',
            'image' => 'nullable|image|max:2048'
        ]);

        if ($request->hasFile('image')) {
            // Hapus gambar lama jika ada
            if ($certificate->image) {
                // Logika hapus gambar lama (opsional)
            }
            $path = $request->file('image')->store('certificates', 's3');
            $validated['image'] = env('AWS_ENDPOINT') . '/' . env('AWS_BUCKET') . '/' . $path;
        }

        $certificate->update($validated);
        return response()->json(['message' => 'Certificate updated successfully']);
    }

    public function destroy($id)
    {
        $certificate = Certificate::findOrFail($id);
        // Hapus file gambar jika perlu
        $certificate->delete();
        return response()->json(['message' => 'Certificate deleted']);
    }
}