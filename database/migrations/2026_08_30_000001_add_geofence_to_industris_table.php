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
        Schema::table('industris', function (Blueprint $table) {
            $table->decimal('latitude', 10, 8)->nullable()->after('website');
            $table->decimal('longitude', 11, 8)->nullable()->after('latitude');
            $table->unsignedInteger('radius_toleransi_meter')->default(300)->after('longitude');
            $table->time('jam_masuk')->default('08:00:00')->after('radius_toleransi_meter');
            $table->time('jam_pulang')->default('16:00:00')->after('jam_masuk');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('industris', function (Blueprint $table) {
            $table->dropColumn([
                'latitude',
                'longitude',
                'radius_toleransi_meter',
                'jam_masuk',
                'jam_pulang',
            ]);
        });
    }
};
