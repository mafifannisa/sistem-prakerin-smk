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
    Schema::create('jurnal_harians', function (Blueprint $table) {
        $table->id();
        $table->foreignId('siswa_id')->constrained('siswas')->onDelete('cascade');
        $table->foreignId('penempatan_magang_id')->constrained('penempatan_magangs')->onDelete('cascade');
        $table->date('tanggal');
        $table->integer('minggu_ke');
        $table->text('kegiatan'); // Deskripsi kegiatan hari ini
        $table->integer('durasi_jam')->default(8); // Durasi kerja dalam jam
        $table->text('catatan_pembimbing')->nullable();
        $table->enum('status', ['pending', 'disetujui', 'ditolak'])->default('pending');
        $table->foreignId('disetujui_oleh')->nullable()->constrained('users')->onDelete('set null');
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jurnal_harians');
    }
};
