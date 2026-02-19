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
            Schema::create('respondens', function (Blueprint $table) {
                $table->id();
                $table->string('nama_responden');
                $table->string('usia_responden');
                $table->enum('pendidikan_responden', ['SD/MI sederajat', 'SMP/MTs sederajat', 'SMA/SMK/MA sederajat', 'D1/D3', 'D4/S1', 'S2/S3', 'lainya']);

                $table->string('no_telp_responden');
                $table->enum('jenis_kelamin_responden', ['Laki-laki', 'Perempuan']);
                $table->enum('pekerjaan_responden', ['Pelajar/Mahasiswa', 'Pegawai Negeri Sipil', 'TNI/POLRI', 'Karyawan Swasta', 'Wirausaha', 'Lainnya']);
                $table->timestamps();
            });
        }

        /**
         * Reverse the migrations.
         */
        public function down(): void
        {
            Schema::dropIfExists('respondens');
        }
    };
