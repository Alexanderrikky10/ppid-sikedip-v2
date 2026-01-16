<?php

namespace Database\Seeders;

use Illuminate\Support\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PejabatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        DB::table('pejabats')->insert([
            [
                'nama_kepala' => 'Budi Santoso',
                'nip' => '19781231 200501 1 001',
                'pangkat_kepala' => 'Pembina IV/a',
                'jabatan_id' => 1, // pastikan id jabatan ini ada di tabel jabatans
                'perangkat_daerah_id' => 1, // pastikan id perangkat_daerah ini ada di tabel perangkat_daerahs
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'nama_kepala' => 'Siti Aminah',
                'nip' => '19850615 201001 2 002',
                'pangkat_kepala' => 'Penata Tk I III/d',
                'jabatan_id' => 2,
                'perangkat_daerah_id' => 2,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
}
