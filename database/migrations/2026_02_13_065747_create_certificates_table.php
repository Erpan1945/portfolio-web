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
        Schema::create('certificates', function (Blueprint $table) {
            $table->id();
            $table->string('name');         // Nama Sertifikat
            $table->string('issuer');       // Penerbit (misal: Dicoding, Coursera)
            $table->date('issued_date');    // Tanggal terbit
            $table->string('credential_url')->nullable(); // Link ke sertifikat asli
            $table->string('image')->nullable(); // Foto sertifikat
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('certificates');
    }
};
