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
        Schema::create('penempatan_magangs', function (Blueprint $table) {
        $table->id();
        $table->foreignId('siswa_id')->constrained('siswas')->onDelete('cascade');
        $table->foreignId('industri_id')->constrained('industris')->onDelete('cascade');
        $table->string('tahun_ajaran'); // 2025/2026
        $table->string('semester'); // Ganjil/Genap
        $table->date('tanggal_mulai');
        $table->date('tanggal_selesai');
        $table->enum('status', [
            'pending', 
            'approved', 
            'rejected', 
            'ongoing', 
            'completed', 
            'cancelled'
        ])->default('pending');
        $table->string('posisi_magang')->nullable();
        $table->text('catatan_industri')->nullable();
        $table->date('tanggal_approval')->nullable();
        $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penempatan_magangs');
    }
};
