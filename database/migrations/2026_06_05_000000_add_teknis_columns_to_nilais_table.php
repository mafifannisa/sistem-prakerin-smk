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
        Schema::table('nilais', function (Blueprint $table) {
            $table->decimal('nilai_disposisi_excel', 5, 2)->nullable()->after('nilai_pengetahuan');
            $table->decimal('nilai_foto_word', 5, 2)->nullable()->after('nilai_disposisi_excel');
            $table->decimal('nilai_background_foto', 5, 2)->nullable()->after('nilai_foto_word');
            $table->string('foto_nilai', 255)->nullable()->after('nilai_background_foto');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('nilais', function (Blueprint $table) {
            $table->dropColumn(['nilai_disposisi_excel', 'nilai_foto_word', 'nilai_background_foto', 'foto_nilai']);
        });
    }
};
