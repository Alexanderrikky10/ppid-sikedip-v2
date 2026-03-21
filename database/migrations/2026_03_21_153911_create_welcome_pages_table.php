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
        Schema::create('welcome_pages', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('sub_title');
            $table->text('description')->nullable();
            $table->json('media')->nullable();
            $table->json('media_slides')->nullable();
            $table->boolean('is_active')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('welcome_pages');
    }
};
