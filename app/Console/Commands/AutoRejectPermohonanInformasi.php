<?php

namespace App\Console\Commands;

use App\Models\PermohonanInformasi;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class AutoRejectPermohonanInformasi extends Command
{
    protected $signature = 'permohonan:auto-reject';
    protected $description = 'Otomatis menolak permohonan informasi yang sudah lebih dari 7 hari diproses';

    public function handle(): void
    {
        $batasWaktu = Carbon::now()->subDays(7);

        $permohonan = PermohonanInformasi::where('status', 'diproses')
            ->where('updated_at', '<=', $batasWaktu)
            ->get();

        if ($permohonan->isEmpty()) {
            $this->info('Tidak ada permohonan yang perlu ditolak.');
            return;
        }

        $permohonan->each(function ($item) {
            $item->update([
                'status' => 'ditolak',
                'tindak_lanjut' => 'Ditolak otomatis karena melebihi batas waktu 7 hari proses.',
            ]);

            $this->info("✅ Permohonan #{$item->no_registrasi} - {$item->nama_pemohon} berhasil ditolak.");
        });

        $this->info("Total {$permohonan->count()} permohonan berhasil diperbarui.");
    }
}