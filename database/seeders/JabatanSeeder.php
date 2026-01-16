<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class JabatanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        DB::table('jabatans')->insert([
            [
                'nama_jabatan' => 'Kepala Dinas',
                'kode_jabatan' => 'KD001',
                'slug' => 'kepala-dinas',
            ],
            [
                'nama_jabatan' => 'Sekretaris Dinas',
                'kode_jabatan' => 'SD002',
                'slug' => 'sekretaris-dinas',
            ],
            [
                'nama_jabatan' => 'Kepala Bidang',
                'kode_jabatan' => 'KB003',
                'slug' => 'kepala-bidang',
            ],
        ]);
    }
}
