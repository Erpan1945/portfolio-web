<?php

namespace App\Http\Controllers;

use App\Models\CvEntry;
use Illuminate\Http\Request;

class CvController extends Controller
{
    // Ambil semua data CV untuk ditampilkan di depan
    public function index()
    {
        return response()->json(CvEntry::orderBy('start_date', 'desc')->get());
    }

    // Simpan data baru (Admin)
    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|in:experience,education,skill',
            'title' => 'required',
            'subtitle' => 'nullable',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'description' => 'nullable'
        ]);

        $entry = CvEntry::create($validated);
        return response()->json($entry);
    }

    // Hapus data (Admin)
    public function destroy($id)
    {
        CvEntry::destroy($id);
        return response()->json(['message' => 'Deleted']);
    }
}