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
        Schema::create('industris', function (Blueprint $table) {
        $table->id();
        $table->string('nama_industri');
        $table->string('nib')->nullable(); // Nomor Induk Berusaha
        $table->text('alamat');
        $table->string('kelurahan')->nullable();
        $table->string('kecamatan')->nullable();
        $table->string('kota');
        $table->string('provinsi')->default('Jawa Timur');
        $table->string('kode_pos')->nullable();
        $table->string('no_telp');
        $table->string('email')->nullable();
        $table->string('website')->nullable();
        $table->string('nama_hr')->nullable(); // Kontak HRD
        $table->string('no_wa_hr')->nullable();
        $table->string('kategori')->nullable(); // Manufaktur, IT, dll
        $table->integer('kapasitas_magang')->default(0);
        $table->boolean('is_active')->default(true);
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('industris');
    }
};
