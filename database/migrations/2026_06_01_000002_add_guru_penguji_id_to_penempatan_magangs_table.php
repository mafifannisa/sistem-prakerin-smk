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
        Schema::table('penempatan_magangs', function (Blueprint $table) {
            $table->foreignId('guru_penguji_id')->nullable()->after('guru_pembimbing_id')->constrained('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('penempatan_magangs', function (Blueprint $table) {
            $table->dropForeign(['guru_penguji_id']);
            $table->dropColumn('guru_penguji_id');
        });
    }
};
