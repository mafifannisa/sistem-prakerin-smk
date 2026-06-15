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
        // 1. Create 'kelas' table
        Schema::create('kelas', function (Blueprint $table) {
            $table->id();
            $table->string('nama_kelas');
            $table->timestamps();
        });

        // 2. Modify 'siswas' table
        Schema::table('siswas', function (Blueprint $table) {
            // Drop old string column 'kelas' if exists
            if (Schema::hasColumn('siswas', 'kelas')) {
                $table->dropColumn('kelas');
            }
            // Add 'kelas_id' foreign key
            $table->foreignId('kelas_id')->nullable()->after('jurusan_id')->constrained('kelas')->onDelete('set null');
        });

        // 3. Modify 'gurus' table
        Schema::table('gurus', function (Blueprint $table) {
            // Add 'kelas_id' foreign key
            $table->foreignId('kelas_id')->nullable()->after('jurusan_id')->constrained('kelas')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('gurus', function (Blueprint $table) {
            $table->dropForeign(['kelas_id']);
            $table->dropColumn('kelas_id');
        });

        Schema::table('siswas', function (Blueprint $table) {
            $table->dropForeign(['kelas_id']);
            $table->dropColumn('kelas_id');
            // Re-add old 'kelas' column
            $table->string('kelas', 50)->nullable()->after('jurusan_id');
        });

        Schema::dropIfExists('kelas');
    }
};
