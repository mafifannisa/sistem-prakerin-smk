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
        Schema::create('siswas', function (Blueprint $table) {
        $table->id();
        $table->string('nisn')->unique();
        $table->string('nama');
        $table->string('tempat_lahir');
        $table->date('tanggal_lahir');
        $table->foreignId('jurusan_id')->constrained('jurusans')->onDelete('cascade');
        $table->string('no_wa');
        $table->string('email')->nullable();
        $table->text('alamat')->nullable();
        $table->string('nama_wali')->nullable();
        $table->string('no_wa_wali')->nullable();
        $table->string('password')->nullable(); // Untuk login siswa
        $table->boolean('is_active')->default(true);
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('siswas');
    }
};
