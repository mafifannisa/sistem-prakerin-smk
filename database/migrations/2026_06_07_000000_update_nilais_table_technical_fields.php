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
            $table->dropColumn(['nilai_disposisi_excel', 'nilai_foto_word', 'nilai_background_foto']);
            
            $table->string('kegiatan_1', 255)->nullable()->after('nilai_pengetahuan');
            $table->decimal('nilai_1', 5, 2)->nullable()->after('kegiatan_1');
            $table->string('kegiatan_2', 255)->nullable()->after('nilai_1');
            $table->decimal('nilai_2', 5, 2)->nullable()->after('kegiatan_2');
            $table->string('kegiatan_3', 255)->nullable()->after('nilai_2');
            $table->decimal('nilai_3', 5, 2)->nullable()->after('kegiatan_3');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('nilais', function (Blueprint $table) {
            $table->dropColumn([
                'kegiatan_1', 'nilai_1',
                'kegiatan_2', 'nilai_2',
                'kegiatan_3', 'nilai_3'
            ]);
            
            $table->decimal('nilai_disposisi_excel', 5, 2)->nullable()->after('nilai_pengetahuan');
            $table->decimal('nilai_foto_word', 5, 2)->nullable()->after('nilai_disposisi_excel');
            $table->decimal('nilai_background_foto', 5, 2)->nullable()->after('nilai_foto_word');
        });
    }
};
