<?php

namespace App\Filament\Resources\SurveyKualitasResource\Widgets;

use App\Models\JawabanSurvey;
use Filament\Widgets\ChartWidget;
use Livewire\Attributes\On;
use Carbon\Carbon;

class IndexKualitas extends ChartWidget
{
    protected static ?string $heading = 'Grafik Index Kepuasan';
    protected static ?string $maxHeight = '300px';

    protected int|string|array $columnSpan = [
        'md' => 3,
        'xl' => 3,
        'sm' => 'full',
    ];

    protected static ?int $sort = 4;

    public ?string $tanggalMulai = null;
    public ?string $tanggalSelesai = null;

    // PERBAIKAN: Listen event filter
    #[On('filterApplied')]
    public function handleFilterApplied($tanggal_mulai, $tanggal_selesai): void
    {
        $this->tanggalMulai = $tanggal_mulai;
        $this->tanggalSelesai = $tanggal_selesai;
        $this->updateChartData();
    }

    #[On('filterReset')]
    public function handleFilterReset(): void
    {
        $this->tanggalMulai = null;
        $this->tanggalSelesai = null;
        $this->updateChartData();
    }

    protected function getData(): array
    {
        // PERBAIKAN: Query dari database
        $query = JawabanSurvey::query();

        // Tentukan rentang tanggal
        if ($this->tanggalMulai && $this->tanggalSelesai) {
            $startDate = Carbon::parse($this->tanggalMulai);
            $endDate = Carbon::parse($this->tanggalSelesai);
        } else {
            // Default: 12 bulan terakhir
            $startDate = Carbon::now()->subMonths(11)->startOfMonth();
            $endDate = Carbon::now()->endOfMonth();
        }

        // Hitung selisih hari untuk menentukan interval
        $diffDays = $startDate->diffInDays($endDate);

        $labels = [];
        $dataPoints = [];

        if ($diffDays <= 31) {
            // PERBAIKAN: Tampilkan per hari jika rentang <= 31 hari
            $periode = Carbon::parse($startDate);
            while ($periode->lte($endDate)) {
                $labels[] = $periode->format('d/m');

                $avg = (clone $query)
                    ->whereDate('created_at', $periode->format('Y-m-d'))
                    ->avg('jawaban');

                $dataPoints[] = $avg ? round($avg, 2) : 0;
                $periode->addDay();
            }
        } elseif ($diffDays <= 93) {
            // PERBAIKAN: Tampilkan per minggu jika rentang <= 93 hari
            $periode = Carbon::parse($startDate)->startOfWeek();
            while ($periode->lte($endDate)) {
                $weekEnd = (clone $periode)->endOfWeek();
                if ($weekEnd->gt($endDate)) {
                    $weekEnd = $endDate;
                }

                $labels[] = 'Minggu ' . $periode->weekOfYear;

                $avg = (clone $query)
                    ->whereBetween('created_at', [
                        $periode->format('Y-m-d 00:00:00'),
                        $weekEnd->format('Y-m-d 23:59:59')
                    ])
                    ->avg('jawaban');

                $dataPoints[] = $avg ? round($avg, 2) : 0;
                $periode->addWeek();
            }
        } else {
            // PERBAIKAN: Tampilkan per bulan jika rentang > 93 hari
            $periode = Carbon::parse($startDate)->startOfMonth();
            while ($periode->lte($endDate)) {
                $labels[] = $periode->translatedFormat('M Y');

                $avg = (clone $query)
                    ->whereYear('created_at', $periode->year)
                    ->whereMonth('created_at', $periode->month)
                    ->avg('jawaban');

                $dataPoints[] = $avg ? round($avg, 2) : 0;
                $periode->addMonth();
            }
        }

        // PERBAIKAN: Return data dari database, bukan dummy
        return [
            'datasets' => [
                [
                    'label' => 'Index Kepuasan (Skala 1-5)',
                    'data' => $dataPoints,
                    'fill' => 'start',
                    'backgroundColor' => 'rgba(59, 130, 246, 0.1)',
                    'borderColor' => 'rgb(59, 130, 246)',
                    'pointBackgroundColor' => 'rgb(59, 130, 246)',
                    'pointBorderColor' => '#fff',
                    'pointHoverBackgroundColor' => '#fff',
                    'pointHoverBorderColor' => 'rgb(59, 130, 246)',
                    'tension' => 0.4,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'display' => true,
                    'position' => 'top',
                ],
                'tooltip' => [
                    'enabled' => true,
                    'mode' => 'index',
                    'intersect' => false,
                ],
            ],
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'min' => 0,
                    'max' => 5,
                    'ticks' => [
                        'stepSize' => 0.5,
                    ],
                    'title' => [
                        'display' => true,
                        'text' => 'Nilai Kepuasan',
                    ],
                ],
                'x' => [
                    'title' => [
                        'display' => true,
                        'text' => 'Periode',
                    ],
                ],
            ],
            'interaction' => [
                'mode' => 'nearest',
                'axis' => 'x',
                'intersect' => false,
            ],
        ];
    }

    public function updateChartData(): void
    {
        // Trigger re-render chart
        $this->dispatch('updateChartData');
    }
}