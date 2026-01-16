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
        Schema::table('Informasis', function (Blueprint $table) {
            $table->foreignId('perangkat_daerah_id')->constrained('perangkat_daerahs')->onDelete('cascade')->after('tahun');
            $table->foreignId('klasifikasi_informasi_id')->constrained('klasifikasi_informasis')->onDelete('cascade')->after('perangkat_daerah_id');
            $table->foreignId('kategori_jenis_informasi_id')->constrained('kategori_jenis_informasis')->onDelete('cascade')->after('klasifikasi_informasi_id');
            $table->foreignId('kategori_informasi_id')->constrained('kategori_informasis')->onDelete('cascade')->after('kategori_jenis_informasi_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('Informasis', function (Blueprint $table) {
            //
        });
    }
};
