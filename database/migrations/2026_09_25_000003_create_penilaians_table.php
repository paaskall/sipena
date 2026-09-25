<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('penilaians', function (Blueprint $table) {
            $table->id();
            $table->foreignId('peserta_id')->constrained('pesertas')->cascadeOnDelete();
            $table->foreignId('penguji_id')->constrained('login_pengujis')->cascadeOnDelete();

            $table->enum('tipe', ['wawancara', 'tertulis']);

            $table->unsignedSmallInteger('elemen_index')->default(0);
            $table->string('judul_unit')->nullable();
            $table->string('jenis_kompetensi')->nullable();
            $table->string('elemen_kompetensi');

            $table->decimal('nilai', 5, 2)->nullable();
            $table->text('catatan')->nullable();

            // status
            $table->boolean('is_final')->default(false);
            $table->timestamp('submitted_at')->nullable();

            $table->foreignId('edited_by_admin_id')->nullable()->constrained('login_pengujis')->nullOnDelete();
            $table->timestamp('edited_by_admin_at')->nullable();

            $table->timestamps();

            $table->index(['peserta_id', 'penguji_id', 'tipe']);
            $table->unique(['peserta_id', 'penguji_id', 'tipe', 'elemen_index'], 'unique_penilaian');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('penilaians');
    }
};