<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    // Mengambil foto untuk publik
    public function getPhoto()
    {
        $heroImage = Setting::where('key', 'hero_image')->first();
        $photoUrl = $heroImage ? Storage::disk('s3')->url($heroImage->value) : null;

        return response()->json([
            'photo_url' => $photoUrl
        ]);
    }

    // Mengubah foto (Admin)
    public function updatePhoto(Request $request)
    {
        $request->validate([
            'photo' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            // Hapus foto lama di Supabase S3 jika ada (opsional tapi disarankan)
            $oldImage = Setting::where('key', 'hero_image')->first();
            if ($oldImage && $oldImage->value) {
                Storage::disk('s3')->delete($oldImage->value);
            }

            // Upload foto baru
            $path = $request->file('photo')->store('portfolio', 's3');

            // Simpan path ke database
            Setting::updateOrCreate(
                ['key' => 'hero_image'],
                ['value' => $path]
            );

            return response()->json([
                'message' => 'Foto berhasil diupdate!',
                'photo_url' => Storage::disk('s3')->url($path)
            ]);
        }

        return response()->json(['message' => 'Tidak ada file yang diupload.'], 400);
    }
}