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
        Schema::table('absensis', function (Blueprint $table) {
            $table->decimal('latitude', 10, 8)->nullable()->after('tanggal');
            $table->decimal('longitude', 11, 8)->nullable()->after('latitude');
            $table->decimal('gps_accuracy', 6, 2)->nullable()->after('longitude');
            $table->decimal('jarak_meter', 8, 2)->nullable()->after('gps_accuracy');
            $table->string('foto_pulang')->nullable()->after('bukti_foto');
            $table->boolean('is_mock_location')->default(false)->after('foto_pulang');
            $table->string('device_id')->nullable()->after('is_mock_location');
            $table->decimal('liveness_score', 4, 3)->nullable()->after('device_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('absensis', function (Blueprint $table) {
            $table->dropColumn([
                'latitude',
                'longitude',
                'gps_accuracy',
                'jarak_meter',
                'foto_pulang',
                'is_mock_location',
                'device_id',
                'liveness_score',
            ]);
        });
    }
};
