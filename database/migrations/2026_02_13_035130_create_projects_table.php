<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('title');                        // Judul Project
            $table->string('slug')->unique();               // URL ramah SEO (contoh: my-website)
            $table->text('description');                    // Deskripsi lengkap
            $table->string('image')->nullable();            // Foto project (opsional dulu)
            $table->string('link')->nullable();             // Link ke project asli (opsional)
            $table->boolean('is_active')->default(true);    // Untuk sembunyikan project jika perlu
            $table->timestamps();                           // Mencatat waktu dibuat & diupdate
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
