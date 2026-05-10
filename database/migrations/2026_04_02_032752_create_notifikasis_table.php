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
        Schema::create('notifikasis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_id')->constrained('siswas')->onDelete('cascade');
            $table->string('judul');
            $table->text('pesan');
            $table->enum('jenis', ['info', 'warning', 'success', 'error'])->default('info');
            $table->enum('tipe', ['login', 'absensi', 'jurnal', 'laporan', 'surat', 'umum'])->default('umum');
            $table->boolean('is_read')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifikasis');
    }
};
