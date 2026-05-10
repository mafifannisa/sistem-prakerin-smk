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
        Schema::create('sertifikats', function (Blueprint $table) {
        $table->id();
        $table->foreignId('penempatan_magang_id')->constrained('penempatan_magangs')->onDelete('cascade');
        $table->foreignId('nilai_id')->nullable()->constrained('nilais')->onDelete('cascade');
        $table->string('nomor_sertifikat')->unique()->nullable();
        $table->string('file_path')->nullable(); // Path file PNG/PDF
        $table->date('tanggal_terbit')->nullable();
        $table->enum('status', ['draft', 'generated', 'issued'])->default('draft');
        $table->text('catatan')->nullable();
        $table->foreignId('generated_by')->constrained('users')->onDelete('cascade');
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sertifikats');
    }
};
