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
        Schema::create('periode_magangs', function (Blueprint $table) {
            $table->id();
            $table->string('tahun_ajaran'); // e.g. "2025/2026"
            $table->enum('semester', ['Ganjil', 'Genap']);
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            $table->timestamps();
        });

        Schema::table('penempatan_magangs', function (Blueprint $table) {
            $table->foreignId('periode_magang_id')->nullable()->after('industri_id')->constrained('periode_magangs')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('penempatan_magangs', function (Blueprint $table) {
            $table->dropForeign(['periode_magang_id']);
            $table->dropColumn('periode_magang_id');
        });

        Schema::dropIfExists('periode_magangs');
    }
};
