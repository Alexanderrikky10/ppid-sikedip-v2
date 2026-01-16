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
        Schema::create('keberatan_informasis', function (Blueprint $table) {
            $table->id();
            // NIK dan Permohonan Informasi
            $table->string('nik_pemohon', 20);
            $table->foreignId('permohonan_informasi_id')->constrained('permohonan_informasis')->onDelete('cascade');
            $table->text('tujuan_penggunaan_informasi')->nullable();

            // Identitas Pemohon
            $table->string('nama_pemohon');
            $table->string('pekerjaan')->nullable();
            $table->string('alamat_pemohon')->nullable();
            $table->string('telepon_pemohon')->nullable();

            // Identitas Kuasa Pemohon
            $table->string('nama_kuasa')->nullable();
            $table->string('alamat_kuasa')->nullable();
            $table->string('telepon_kuasa')->nullable();
            $table->string('surat_kuasa')->nullable(); // file upload path

            // Alasan Pengajuan Keberatan (checkbox bisa disimpan sebagai JSON atau string dipisah koma)
            $table->json('alasan_keberatan')->nullable();
            $table->enum('status', ['diproses', 'selesai', 'ditolak'])->default('diproses');
            $table->text('catatan')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('keberatan_informasis');
    }
};
