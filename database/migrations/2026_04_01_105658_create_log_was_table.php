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
        Schema::create('log_was', function (Blueprint $table) {
        $table->id();
        $table->foreignId('siswa_id')->nullable()->constrained('siswas')->onDelete('set null');
        $table->string('no_wa_tujuan');
        $table->text('pesan');
        $table->enum('jenis', ['blast', 'individual', 'chatbot_reply'])->default('individual');
        $table->enum('status', ['pending', 'sent', 'failed', 'delivered'])->default('pending');
        $table->string('message_id')->nullable(); // ID dari WA API
        $table->text('response')->nullable(); // Response dari API
        $table->timestamp('sent_at')->nullable();
        $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('log_was');
    }
};
