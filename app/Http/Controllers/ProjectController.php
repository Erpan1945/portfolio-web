<?php

namespace App\Http\Controllers;

use App\Models\Project; // Jangan lupa import Model ini!
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function index()
    {
        // Ambil semua project yang aktif, urutkan dari yang terbaru
        $projects = Project::where('is_active', true)
                            ->orderBy('created_at', 'desc')
                            ->get();

        // Kembalikan dalam format JSON
        return response()->json($projects);
    }

    public function update(Request $request, $id)
    {
        $project = Project::find($id);

        if (!$project) {
            return response()->json(['message' => 'Project not found'], 404);
        }

        $request->validate([
            'title' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Image jadi nullable (opsional) saat edit
            'description' => 'required',
            'link' => 'nullable|url',
            'tech_stack' => 'nullable',
        ]);

        // 1. Update Text Data
        $project->title = $request->title;
        $project->description = $request->description;
        $project->link = $request->link;
        
        // Convert tech_stack string to JSON array
        if($request->tech_stack) {
             // Misal input: "Laravel, Vue, Tailwind" -> jadi ["Laravel", "Vue", "Tailwind"]
             $arrayStack = array_map('trim', explode(',', $request->tech_stack));
             $project->tech_stack = json_encode($arrayStack);
        }

        // 2. Cek apakah ada upload gambar baru?
        if ($request->hasFile('image')) {
            // Hapus gambar lama dari Supabase/S3 agar tidak menumpuk sampah
            if ($project->image) {
                // Ambil path relatif dari URL lengkap
                $oldPath = str_replace(env('AWS_ENDPOINT').'/'.env('AWS_BUCKET').'/', '', $project->image);
                \Illuminate\Support\Facades\Storage::disk('s3')->delete($oldPath);
            }

            // Upload gambar baru
            $path = $request->file('image')->store('projects', 's3');
            $url = env('AWS_ENDPOINT') . '/' . env('AWS_BUCKET') . '/' . $path;
            $project->image = $url;
        }

        $project->save();

        return response()->json($project);
    }
}