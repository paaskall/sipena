<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('peserta_penguji', function (Blueprint $table) {
            $table->id();
            $table->foreignId('peserta_id')->constrained('pesertas')->cascadeOnDelete();
            $table->foreignId('penguji_id')->constrained('login_pengujis')->cascadeOnDelete();
            $table->enum('peran', ['wawancara_1', 'wawancara_2', 'tertulis']);
            $table->timestamps();

            $table->unique(['peserta_id', 'peran']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('peserta_penguji');
    }
};