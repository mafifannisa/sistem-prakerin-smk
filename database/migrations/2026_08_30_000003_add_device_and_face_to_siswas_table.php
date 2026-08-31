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
        Schema::table('siswas', function (Blueprint $table) {
            $table->string('device_id')->nullable()->after('password');
            $table->string('device_model', 100)->nullable()->after('device_id');
            $table->text('fcm_token')->nullable()->after('device_model');
            $table->boolean('is_face_enrolled')->default(false)->after('fcm_token');
            $table->string('foto_master_wajah')->nullable()->after('is_face_enrolled');
            $table->longText('face_embedding_json')->nullable()->after('foto_master_wajah');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('siswas', function (Blueprint $table) {
            $table->dropColumn([
                'device_id',
                'device_model',
                'fcm_token',
                'is_face_enrolled',
                'foto_master_wajah',
                'face_embedding_json',
            ]);
        });
    }
};
