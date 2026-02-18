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
        Schema::create('cv_entries', function (Blueprint $table) {
            $table->id();
            $table->string('type'); // 'experience', 'education', 'skill'
            $table->string('title'); // Jabatan / Nama Jurusan / Nama Skill
            $table->string('subtitle')->nullable(); // Nama PT / Nama Kampus / Tingkat Skill
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable(); // Jika null berarti "Present"
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cv_entries');
    }
};
