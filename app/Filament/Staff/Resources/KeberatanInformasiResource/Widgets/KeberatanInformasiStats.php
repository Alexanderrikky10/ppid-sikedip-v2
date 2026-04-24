<?php

namespace App\Filament\Staff\Resources\KeberatanInformasiResource\Widgets;

use App\Models\KeberatanInformasi;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class KeberatanInformasiStats extends BaseWidget
{
    protected int|string|array $columnSpan = 'full';

    // =========================================================
    // Helper: ambil user yang sedang login
    // =========================================================
    private function currentUser(): ?\App\Models\User
    {
        return Auth::user();
    }

    // =========================================================
    // Helper: validasi apakah user adalah staff
    // dengan perangkat daerah yang valid
    // =========================================================
    private function isValidStaff(): bool
    {
        $user = $this->currentUser();

        if (!$user)
            return false;
        if ($user->role !== 'staff')
            return false;
        if (!$user->perangkat_daerah_id)
            return false;
        if (!$user->is_active)
            return false;

        return true;
    }

    // =========================================================
    // Helper: base query yang sudah difilter OPD staff
    // Melalui relasi permohonanInformasi -> perangkat_daerah_id
    // Ini adalah satu-satunya query yang boleh digunakan
    // =========================================================
    private function baseQuery(): Builder
    {
        $user = $this->currentUser();

        // Jika bukan staff valid, kembalikan query kosong
        if (!$this->isValidStaff()) {
            return KeberatanInformasi::whereRaw('1 = 0');
        }

        // Filter WAJIB: hanya keberatan yang terkait
        // dengan permohonan OPD milik staff ini
        return KeberatanInformasi::whereHas(
            'permohonanInformasi',
            function (Builder $q) use ($user) {
                $q->where('perangkat_daerah_id', $user->perangkat_daerah_id);
            }
        );
    }

    // =========================================================
    // STATS
    // =========================================================
    protected function getStats(): array
    {
        // Jika bukan staff valid, kembalikan stats kosong
        if (!$this->isValidStaff()) {
            return [];
        }

        $user = $this->currentUser();
        $namaOPD = $user?->perangkatDaerah?->nama_perangkat_daerah ?? '-';

        // ── Hitung Data Waktu ─────────────────────────────────────
        $hariIni = (clone $this->baseQuery())
            ->whereDate('created_at', Carbon::today())
            ->count();

        $kemarin = (clone $this->baseQuery())
            ->whereDate('created_at', Carbon::yesterday())
            ->count();

        $mingguIni = (clone $this->baseQuery())
            ->whereBetween('created_at', [
                Carbon::now()->startOfWeek(),
                Carbon::now()->endOfWeek(),
            ])->count();

        $mingguLalu = (clone $this->baseQuery())
            ->whereBetween('created_at', [
                Carbon::now()->subWeek()->startOfWeek(),
                Carbon::now()->subWeek()->endOfWeek(),
            ])->count();

        $bulanIni = (clone $this->baseQuery())
            ->whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->count();

        $bulanLalu = (clone $this->baseQuery())
            ->whereMonth('created_at', Carbon::now()->subMonth()->month)
            ->whereYear('created_at', Carbon::now()->subMonth()->year)
            ->count();

        $totalKeseluruhan = (clone $this->baseQuery())->count();

        // ── Hitung Data Status ────────────────────────────────────
        $pending = (clone $this->baseQuery())
            ->where('status', 'pending')
            ->count();

        $diproses = (clone $this->baseQuery())
            ->where('status', 'diproses')
            ->count();

        $selesai = (clone $this->baseQuery())
            ->where('status', 'selesai')
            ->count();

        $ditolak = (clone $this->baseQuery())
            ->where('status', 'ditolak')
            ->count();

        // ── Hitung Keberatan Menggunakan Kuasa ────────────────────
        $denganKuasa = (clone $this->baseQuery())
            ->whereNotNull('nama_kuasa')
            ->count();

        // ── Hitung Tren ───────────────────────────────────────────
        $trendHarian = $this->hitungTrend($hariIni, $kemarin);
        $trendMingguan = $this->hitungTrend($mingguIni, $mingguLalu);
        $trendBulanan = $this->hitungTrend($bulanIni, $bulanLalu);

        return [
            // ── Stat 1: Hari Ini ──────────────────────────────────
            Stat::make('Keberatan Hari Ini', $this->formatNumber($hariIni))
                ->description(
                    $trendHarian['label'] .
                    ' dibanding kemarin (' . $kemarin . ') | OPD: ' . $namaOPD
                )
                ->descriptionIcon($trendHarian['icon'])
                ->chart($this->chartHarian())
                ->color($trendHarian['color']),

            // ── Stat 2: Minggu Ini ────────────────────────────────
            Stat::make('Keberatan Minggu Ini', $this->formatNumber($mingguIni))
                ->description(
                    $trendMingguan['label'] .
                    ' dibanding minggu lalu (' . $mingguLalu . ') | OPD: ' . $namaOPD
                )
                ->descriptionIcon($trendMingguan['icon'])
                ->chart($this->chartMingguan())
                ->color($trendMingguan['color']),

            // ── Stat 3: Bulan Ini ─────────────────────────────────
            Stat::make('Keberatan Bulan Ini', $this->formatNumber($bulanIni))
                ->description(
                    $trendBulanan['label'] .
                    ' dibanding bulan lalu (' . $bulanLalu . ') | OPD: ' . $namaOPD
                )
                ->descriptionIcon($trendBulanan['icon'])
                ->chart($this->chartBulanan())
                ->color($trendBulanan['color']),

            // ── Stat 4: Total Keseluruhan ─────────────────────────
            Stat::make('Total Keseluruhan', $this->formatNumber($totalKeseluruhan))
                ->description('Semua keberatan informasi OPD: ' . $namaOPD)
                ->descriptionIcon('heroicon-m-archive-box')
                ->chart($this->chartTotal())
                ->color('primary'),

            // ── Stat 5: Pending ───────────────────────────────────
            Stat::make('⏳ Pending', $this->formatNumber($pending))
                ->description('Keberatan menunggu tindak lanjut')
                ->descriptionIcon('heroicon-m-clock')
                ->chart($this->getChartData('pending'))
                ->color('warning'),

            // ── Stat 6: Sedang Diproses ───────────────────────────
            Stat::make('🔄 Sedang Diproses', $this->formatNumber($diproses))
                ->description('Keberatan sedang dalam proses')
                ->descriptionIcon('heroicon-m-arrow-path')
                ->chart($this->getChartData('diproses'))
                ->color('info'),

            // ── Stat 7: Selesai ───────────────────────────────────
            Stat::make('✅ Selesai', $this->formatNumber($selesai))
                ->description('Keberatan telah selesai diproses')
                ->descriptionIcon('heroicon-m-check-circle')
                ->chart($this->getChartData('selesai'))
                ->color('success'),

            // ── Stat 8: Ditolak ───────────────────────────────────
            Stat::make('❌ Ditolak', $this->formatNumber($ditolak))
                ->description('Keberatan tidak dapat diproses')
                ->descriptionIcon('heroicon-m-x-circle')
                ->chart($this->getChartData('ditolak'))
                ->color('danger'),

            // ── Stat 9: Menggunakan Kuasa ─────────────────────────
            Stat::make('📋 Menggunakan Kuasa', $this->formatNumber($denganKuasa))
                ->description('Keberatan yang dikuasakan kepada pihak lain')
                ->descriptionIcon('heroicon-m-user-group')
                ->chart($this->chartDenganKuasa())
                ->color('gray'),
        ];
    }

    // =========================================================
    // Helper: Format Angka
    // =========================================================
    private function formatNumber(int $number): string
    {
        return match (true) {
            $number >= 1_000_000 => number_format($number / 1_000_000, 1) . 'M',
            $number >= 1_000 => number_format($number / 1_000, 1) . 'K',
            default => (string) $number,
        };
    }

    // =========================================================
    // Helper: Hitung Tren
    // =========================================================
    private function hitungTrend(int $sekarang, int $sebelumnya): array
    {
        if ($sekarang > $sebelumnya) {
            $selisih = $sekarang - $sebelumnya;
            return [
                'label' => "Naik {$selisih}",
                'icon' => 'heroicon-m-arrow-trending-up',
                'color' => 'success',
            ];
        }

        if ($sekarang < $sebelumnya) {
            $selisih = $sebelumnya - $sekarang;
            return [
                'label' => "Turun {$selisih}",
                'icon' => 'heroicon-m-arrow-trending-down',
                'color' => 'danger',
            ];
        }

        return [
            'label' => 'Sama',
            'icon' => 'heroicon-m-minus',
            'color' => 'warning',
        ];
    }

    // =========================================================
    // Chart: 7 Hari Terakhir (filter OPD)
    // =========================================================
    private function chartHarian(): array
    {
        return collect(range(6, 0))->map(
            fn($i) => (clone $this->baseQuery())
                ->whereDate('created_at', Carbon::today()->subDays($i))
                ->count()
        )->toArray();
    }

    // =========================================================
    // Chart: 7 Minggu Terakhir (filter OPD)
    // =========================================================
    private function chartMingguan(): array
    {
        return collect(range(6, 0))->map(
            fn($i) => (clone $this->baseQuery())
                ->whereBetween('created_at', [
                    Carbon::now()->subWeeks($i)->startOfWeek(),
                    Carbon::now()->subWeeks($i)->endOfWeek(),
                ])->count()
        )->toArray();
    }

    // =========================================================
    // Chart: 7 Bulan Terakhir (filter OPD)
    // =========================================================
    private function chartBulanan(): array
    {
        return collect(range(6, 0))->map(
            fn($i) => (clone $this->baseQuery())
                ->whereMonth('created_at', Carbon::now()->subMonths($i)->month)
                ->whereYear('created_at', Carbon::now()->subMonths($i)->year)
                ->count()
        )->toArray();
    }

    // =========================================================
    // Chart: Akumulasi Total per Bulan (filter OPD)
    // =========================================================
    private function chartTotal(): array
    {
        return collect(range(6, 0))->map(
            fn($i) => (clone $this->baseQuery())
                ->where(
                    'created_at',
                    '<=',
                    Carbon::now()->subMonths($i)->endOfMonth()
                )->count()
        )->toArray();
    }

    // =========================================================
    // Chart: Data per Status 7 Hari Terakhir (filter OPD)
    // =========================================================
    private function getChartData(string $status): array
    {
        return collect(range(6, 0))->map(
            fn($i) => (clone $this->baseQuery())
                ->where('status', $status)
                ->whereDate('created_at', Carbon::today()->subDays($i))
                ->count()
        )->toArray();
    }

    // =========================================================
    // Chart: Keberatan Dengan Kuasa 7 Hari Terakhir (filter OPD)
    // =========================================================
    private function chartDenganKuasa(): array
    {
        return collect(range(6, 0))->map(
            fn($i) => (clone $this->baseQuery())
                ->whereNotNull('nama_kuasa')
                ->whereDate('created_at', Carbon::today()->subDays($i))
                ->count()
        )->toArray();
    }
}   