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

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'description' => 'required',
            'link' => 'nullable|url',
            'tech_stack' => 'nullable',
        ]);

        // 1. Handle image upload jika ada
        $imageUrl = null;
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('projects', 'public');
            $imageUrl = '/storage/' . $path;
        }

        // 2. Convert tech_stack string to JSON array
        $techStackJson = null;
        if ($request->tech_stack) {
            $arrayStack = array_map('trim', explode(',', $request->tech_stack));
            $techStackJson = json_encode($arrayStack);
        }

        // 3. Create new project
        $project = Project::create([
            'title' => $request->title,
            'slug' => \Illuminate\Support\Str::slug($request->title),
            'description' => $request->description,
            'link' => $request->link,
            'image' => $imageUrl,
            'tech_stack' => $techStackJson,
            'is_active' => true,
        ]);

        return response()->json($project, 201);
    }

    public function update(Request $request, $id)
    {
        $project = Project::find($id);

        if (!$project) {
            return response()->json(['message' => 'Project not found'], 404);
        }

        $request->validate([
            'title' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
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
            $arrayStack = array_map('trim', explode(',', $request->tech_stack));
            $project->tech_stack = json_encode($arrayStack);
        }

        // 2. Cek apakah ada upload gambar baru?
        if ($request->hasFile('image')) {
            // Hapus gambar lama jika ada
            if ($project->image && file_exists(public_path($project->image))) {
                unlink(public_path($project->image));
            }

            // Upload gambar baru
            $path = $request->file('image')->store('projects', 'public');
            $project->image = '/storage/' . $path;
        }

        $project->save();

        return response()->json($project);
    }

    public function destroy($id)
    {
        $project = Project::find($id);

        if (!$project) {
            return response()->json(['message' => 'Project not found'], 404);
        }

        // Hapus image dari local storage jika ada
        if ($project->image && file_exists(public_path($project->image))) {
            unlink(public_path($project->image));
        }

        $project->delete();

        return response()->json(['message' => 'Project deleted successfully']);
    }
}