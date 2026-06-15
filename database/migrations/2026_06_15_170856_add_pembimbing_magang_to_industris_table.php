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
            $table->string('pembimbing_magang')->nullable()->after('no_wa_hr');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('industris', function (Blueprint $table) {
            $table->dropColumn('pembimbing_magang');
        });
    }
};
