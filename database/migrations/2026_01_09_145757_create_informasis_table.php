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
        Schema::create('informasis', function (Blueprint $table) {
            $table->id();
            $table->year('tahun');
            $table->string('judul_informasi');
            $table->text('ringkasan');
            $table->longText('penjelasan')->nullable();
            $table->string('waktu_tampat');
            $table->string('pj_penerbit_informasi');
            $table->string('pejabat_pj');
            $table->string('format_informasi');//bisa jadi di sini enum tergantung nanti mau di tambhakan apa lagi 
            $table->string('waktu_penyimpanan');
            $table->string('media')->nullable();
            $table->string('slug')->unique();
            $table->string('tanggal_publikasi')->nullable();
            $table->unsignedBigInteger('views_count')->default(0);
            $table->unsignedBigInteger('downloads_count')->default(0); // Tambahkan kolom tanggal publikasi jika diperlukan
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('informasis');
    }
};
