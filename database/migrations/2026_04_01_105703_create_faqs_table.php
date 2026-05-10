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
        Schema::create('faqs', function (Blueprint $table) {
        $table->id();
        $table->string('question'); // Pertanyaan FAQ
        $table->text('answer'); // Jawaban fixed
        $table->string('category')->nullable(); // surat, sertifikat, status, dll
        $table->string('keywords')->nullable(); // Kata kunci untuk matching
        $table->boolean('is_active')->default(true);
        $table->integer('priority')->default(0); // Prioritas tampilan
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('faqs');
    }
};
