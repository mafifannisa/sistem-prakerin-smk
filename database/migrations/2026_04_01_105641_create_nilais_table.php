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
        Schema::create('nilais', function (Blueprint $table) {
        $table->id();
        $table->foreignId('penempatan_magang_id')->constrained('penempatan_magangs')->onDelete('cascade');
        $table->decimal('nilai_sikap', 5, 2)->nullable(); // 0-100
        $table->decimal('nilai_keterampilan', 5, 2)->nullable();
        $table->decimal('nilai_pengetahuan', 5, 2)->nullable();
        $table->decimal('nilai_akhir', 5, 2)->nullable(); // Average
        $table->enum('predikat', ['A', 'B', 'C', 'D', 'E'])->nullable();
        $table->text('catatan_penguji')->nullable();
        $table->date('tanggal_input')->nullable();
        $table->foreignId('input_by')->nullable()->constrained('users')->onDelete('cascade');
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nilais');
    }
};
