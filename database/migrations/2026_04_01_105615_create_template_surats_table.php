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
        Schema::create('template_surats', function (Blueprint $table) {
        $table->id();
        $table->foreignId('jurusan_id')->constrained('jurusans')->onDelete('cascade');
        $table->string('nama_template'); // Surat Pengantar TKJ, dll
        $table->enum('jenis_surat', ['pengantar', 'permohonan', 'balasan'])->default('pengantar');
        $table->string('file_path')->nullable(); // File DOCX/PDF template
        $table->text('konten_template')->nullable(); // HTML/Text untuk DomPDF
        $table->boolean('is_active')->default(true);
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('template_surats');
    }
};
