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
        Schema::create('surat_keluars', function (Blueprint $table) {
        $table->id();
        $table->string('nomor_surat')->unique();
        $table->foreignId('penempatan_magang_id')->constrained('penempatan_magangs')->onDelete('cascade');
        $table->foreignId('template_surat_id')->nullable()->constrained('template_surats')->onDelete('set null');
        $table->enum('jenis_surat', ['pengantar', 'permohonan', 'balasan', 'lainnya'])->default('pengantar');
        $table->string('file_path')->nullable(); // Path file PDF
        $table->enum('status', ['draft', 'approved', 'rejected', 'sent'])->default('draft');
        $table->date('tanggal_kirim')->nullable();
        $table->text('catatan')->nullable();
        $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('surat_keluars');
    }
};
