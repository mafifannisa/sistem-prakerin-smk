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
        Schema::create('surat_masuks', function (Blueprint $table) {
        $table->id();
        $table->string('nomor_surat');
        $table->string('pengirim'); // Dari industri
        $table->date('tanggal_terima');
        $table->string('perihal');
        $table->string('file_path')->nullable(); // Scan surat
        $table->enum('status', ['baru', 'diproses', 'selesai'])->default('baru');
        $table->text('catatan')->nullable();
        $table->foreignId('penempatan_magang_id')->nullable()->constrained('penempatan_magangs')->onDelete('set null');
        $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('surat_masuks');
    }
};
