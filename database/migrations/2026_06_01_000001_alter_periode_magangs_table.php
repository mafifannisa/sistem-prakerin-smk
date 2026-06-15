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
        Schema::table('periode_magangs', function (Blueprint $table) {
            $table->string('nama')->after('id')->nullable();
            $table->dropColumn('semester');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('periode_magangs', function (Blueprint $table) {
            $table->enum('semester', ['Ganjil', 'Genap'])->after('tahun_ajaran')->default('Ganjil');
            $table->dropColumn('nama');
        });
    }
};
