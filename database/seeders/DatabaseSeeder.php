<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
    \App\Models\Project::create([
        'title' => 'Website Portofolio Keren',
        'slug' => 'website-portofolio-keren',
        'description' => 'Ini adalah website portofolio pertama saya menggunakan Laravel dan Vue.',
        'image' => 'https://placehold.co/600x400', // Gambar placeholder sementara
        'link' => 'https://github.com/username/repo',
        'is_active' => true,
    ]);

    \App\Models\Project::create([
        'title' => 'Aplikasi Kasir',
        'slug' => 'aplikasi-kasir',
        'description' => 'Sistem manajemen kasir untuk UMKM.',
        'image' => 'https://placehold.co/600x400',
        'link' => 'https://google.com',
        'is_active' => true,
    ]);

    \App\Models\User::factory()->create([
        'name' => 'Erpannya Ayaya',
        'email' => 'atmint@me.com', // Email login kamu
        'password' => \Illuminate\Support\Facades\Hash::make('160825delta'), // Password kamu
    ]);

    }
}
