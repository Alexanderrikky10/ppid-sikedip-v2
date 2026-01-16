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
        Schema::create('perangkat_daerahs', function (Blueprint $table) {
            $table->id();
            $table->string('nama_perangkat_daerah', 255);
            $table->string('labele_perangkat_daerah', 100)->unique();
            $table->foreignId('kategori_informasi_id')->constrained('kategori_informasis')->onDelete('cascade');
            $table->string('slug', 255)->unique();
            $table->foreignId('parent_id')->nullable();
            $table->string('images', 255)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('perangkat_daerahs');
    }
};
