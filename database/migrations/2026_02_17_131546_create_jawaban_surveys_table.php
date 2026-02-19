<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('jawaban_surveys', function (Blueprint $table) {
            $table->id();
            $table->foreignId('responden_id')->constrained('respondens')->onDelete('cascade');
            $table->foreignId('survey_id')->constrained('survey_kualitas')->onDelete('cascade');
            $table->enum('jawaban', ['Sangat Setuju', 'Setuju', 'Cukup', 'Tidak Setuju', 'Sangat Tidak Setuju']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jawaban_surveys');
    }
};
