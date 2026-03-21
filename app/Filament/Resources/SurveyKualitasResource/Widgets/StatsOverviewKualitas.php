<?php

namespace App\Filament\Resources\SurveyKualitasResource\Widgets;

use App\Models\JawabanSurvey;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\DB;

class StatsOverviewKualitas extends BaseWidget
{
    // Stats di kiri: 2 kolom dari 3
    protected int|string|array $columnSpan = [
        'md' => 2,
        'xl' => 2,
        'sm' => 'full',
    ];

    protected static ?int $sort = 1;

    protected static ?string $pollingInterval = null;

    // Property untuk menyimpan filter
    public ?string $tanggalMulai = null;
    public ?string $tanggalSelesai = null;


    #[On('filterApplied')]
    public function handleFilterApplied($tanggal_mulai, $tanggal_selesai): void
    {
        $this->tanggalMulai = $tanggal_mulai;
        $this->tanggalSelesai = $tanggal_selesai;

        // Log untuk debugging (hapus di production)
        \Log::info('Stats Filter Applied', [
            'mulai' => $tanggal_mulai,
            'selesai' => $tanggal_selesai
        ]);
    }

    #[On('filterReset')]
    public function handleFilterReset(): void
    {
        $this->tanggalMulai = null;
        $this->tanggalSelesai = null;

        // Log untuk debugging (hapus di production)
        \Log::info('Stats Filter Reset');
    }


    protected function getStats(): array
    {
        // Base query untuk jawaban survey
        $query = JawabanSurvey::query();

        // Apply filter tanggal jika ada
        if ($this->tanggalMulai && $this->tanggalSelesai) {
            $query->whereBetween('created_at', [
                $this->tanggalMulai . ' 00:00:00',
                $this->tanggalSelesai . ' 23:59:59'
            ]);
        }

        $totalResponden = $query->distinct('responden_id')->count('responden_id');

        $rataRataKepuasan = $query->avg('jawaban') ?? 0;
        $rataRataKepuasan = round($rataRataKepuasan, 2);


        $queryJenisKelamin = clone $query;

        $jenisKelaminData = $queryJenisKelamin
            ->join('respondens', 'jawaban_surveys.responden_id', '=', 'respondens.id')
            ->select('respondens.jenis_kelamin_responden as jenis_kelamin', DB::raw('COUNT(DISTINCT respondens.id) as total'))
            ->groupBy('respondens.jenis_kelamin_responden')
            ->get();

        $totalLakiLaki = $jenisKelaminData->where('jenis_kelamin', 'Laki-laki')->first()->total ?? 0;

        $totalPerempuan = $jenisKelaminData->where('jenis_kelamin', 'Perempuan')->first()->total ?? 0;
        $persenKepuasan = $rataRataKepuasan > 0
            ? round(($rataRataKepuasan / 5) * 100, 1)
            : 0;

        $responseRate = 100;

        $chartResponden = $this->getChartData($query, 'responden');
        $chartKepuasan = $this->getChartData($query, 'kepuasan');

        return [
            Stat::make('Total Responden', number_format($totalResponden))
                ->description($responseRate . '% response rate')
                ->descriptionIcon('heroicon-m-users')
                ->color('success')
                ->chart($chartResponden),

            Stat::make('Rata-rata Kepuasan', number_format($rataRataKepuasan, 2))
                ->description($persenKepuasan . '% tingkat kepuasan')
                ->descriptionIcon('heroicon-m-star')
                ->color($this->getKepuasanColor($rataRataKepuasan))
                ->chart($chartKepuasan),

            Stat::make('Total Responden Laki-laki', number_format($totalLakiLaki))
                ->description($totalResponden > 0
                    ? round(($totalLakiLaki / $totalResponden) * 100, 1) . '% dari total'
                    : '0% dari total')
                ->descriptionIcon('heroicon-m-user')
                ->color('info'),

            Stat::make('Total Responden Perempuan', number_format($totalPerempuan))
                ->description($totalResponden > 0
                    ? round(($totalPerempuan / $totalResponden) * 100, 1) . '% dari total'
                    : '0% dari total')
                ->descriptionIcon('heroicon-m-user')
                ->color('danger'),
        ];
    }


    /**
     * Generate chart data untuk mini chart di stats card
     * 
     * @param string $type 'responden' atau 'kepuasan'
     * @return array Array berisi 7 data point
     */
    protected function getChartData($query, $type = 'responden'): array
    {
        $data = [];

        // Ambil data 7 hari terakhir
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);

            // Clone query untuk setiap hari
            $dayQuery = (clone $query)->whereDate('created_at', $date->format('Y-m-d'));

            if ($type === 'responden') {
                // Hitung jumlah responden unik per hari
                $data[] = $dayQuery->distinct('responden_id')->count('responden_id');
            } else {
                // Hitung rata-rata kepuasan per hari
                $avg = $dayQuery->avg('jawaban') ?? 0;
                $data[] = round($avg, 1);
            }
        }

        return $data;
    }

    /**
     * Tentukan warna berdasarkan nilai kepuasan
     * 
     * @param float $nilai Nilai kepuasan (1-5)
     * @return string Warna Filament (success, info, warning, danger)
     */
    protected function getKepuasanColor(float $nilai): string
    {
        return match (true) {
            $nilai >= 4.5 => 'success',  // Hijau - Sangat Puas
            $nilai >= 3.5 => 'info',     // Biru - Puas
            $nilai >= 2.5 => 'warning',  // Kuning - Cukup
            default => 'danger',         // Merah - Tidak Puas
        };
    }

    protected function getColumns(): int
    {
        return 2;
    }
}