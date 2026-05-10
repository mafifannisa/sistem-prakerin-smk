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
    Schema::create('laporan_pkls', function (Blueprint $table) {
        $table->id();
        $table->foreignId('siswa_id')->constrained('siswas')->onDelete('cascade');
        $table->foreignId('penempatan_magang_id')->constrained('penempatan_magangs')->onDelete('cascade');
        $table->string('judul_laporan');
        $table->text('abstrak')->nullable();
        $table->enum('jenis', ['draft', 'submit', 'revisi', 'approved'])->default('draft');
        $table->string('file_path')->nullable(); // File PDF laporan
        $table->date('tanggal_submit')->nullable();
        $table->text('catatan_pembimbing')->nullable();
        $table->enum('status', ['pending', 'disetujui', 'perlu_revisi'])->default('pending');
        $table->foreignId('disetujui_oleh')->nullable()->constrained('users')->onDelete('set null');
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laporan_pkls');
    }
};
