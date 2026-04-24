<?php

namespace App\Console\Commands;

use App\Models\KeberatanInformasi;
use Illuminate\Console\Command;

class AutoRejectKeberatanInformasi extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'keberatan:auto-reject';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Otomasi menolak keberatan informasi yang sudah lebih dari 7 hari diproses';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        //
        $batasWaktu = now()->subDays(7);
        $keberatan = KeberatanInformasi::where('status', 'diproses')
            ->where('updated_at', '<=', $batasWaktu)
            ->get();

            if ($keberatan->isEmpty()) {
                $this->info('Tidak ada keberatan informasi yang perlu ditolak.');
                return;
            }

            $keberatan->each(function ($item) {
                $item->update([
                    'status' => 'ditolak',
                ]);

                $this->info("✅ Keberatan #{$item->no_registrasi} - {$item->nama_pemohon} berhasil ditolak.");
            });

            $this->info("Total {$keberatan->count()} keberatan informasi berhasil diperbarui.");
    }
}
