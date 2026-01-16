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
        Schema::create('permohonan_informasis', function (Blueprint $table) {
            $table->id();
            $table->string('no_registrasi', 100)->unique();

            // OPD Tujuan (relasi ke tabel OPD jika ada)
            $table->foreignId('perangkat_daerah_id')->nullable()->constrained('perangkat_daerahs')->nullOnDelete();
            // media
            $table->string('dokumen_tambahan_path')->nullable();
            // Data pemohon
            $table->string('nama_pemohon', 255);
            $table->enum('jenis_permohonan', ['perorangan', 'badan_hukum', 'kelompok']);
            $table->date('tanggal_lahir')->nullable();
            $table->enum('jenis_kelamin', ['Laki-laki', 'Perempuan'])->nullable();

            // Identitas
            $table->string('no_identitas', 50)->nullable();
            $table->string('scan_identitas')->nullable(); // path file KTP

            // Kontak & alamat
            $table->text('alamat_lengkap')->nullable();
            $table->string('nomor_fax', 50)->nullable();
            $table->string('nomor_whatsapp', 20)->nullable();
            $table->string('alamat_email', 150)->nullable();

            // Permintaan informasi
            $table->text('informasi_diminta')->nullable();
            $table->text('alasan_permintaan')->nullable();
            $table->string('cara_penyampaian_informasi', 100)->nullable();
            $table->enum('tindak_lanjut', ['Email', 'WhatsApp', 'whatsapp/email']);

            $table->enum('status', ['diproses', 'selesai', 'ditolak'])->default('diproses')->nullable();

            $table->string('dokumen_informasi')->nullable();
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
        Schema::dropIfExists('permohonan_informasis');
    }
};
