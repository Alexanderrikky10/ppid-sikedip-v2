<?php

namespace App\Filament\Resources\SurveyKualitasResource\Widgets;

use Filament\Widgets\Widget;
use Livewire\Attributes\On;
use Carbon\Carbon;

class HeaderPeriode extends Widget
{
    protected static string $view = 'filament.resources.survey-kualitas-resource.widgets.header-periode';

    protected int|string|array $columnSpan = 'full';
    protected static ?int $sort = 0;

    public ?string $periodeText = null;
    public ?string $tanggalMulai = null;
    public ?string $tanggalSelesai = null;
    public ?string $tanggalMulaiFormatted = null;
    public ?string $tanggalSelesaiFormatted = null;

    public function mount(): void
    {
        $this->updatePeriode();
    }

    // PERBAIKAN: Terima parameter langsung, bukan array
    #[On('filterApplied')]
    public function handleFilterApplied($tanggal_mulai, $tanggal_selesai): void
    {
        $this->tanggalMulai = $tanggal_mulai;
        $this->tanggalSelesai = $tanggal_selesai;
        $this->updatePeriode();
    }

    #[On('filterReset')]
    public function handleFilterReset(): void
    {
        $this->tanggalMulai = null;
        $this->tanggalSelesai = null;
        $this->updatePeriode();
    }

    public function updatePeriode(): void
    {
        if (!$this->tanggalMulai || !$this->tanggalSelesai) {
            $this->periodeText = 'Semua Periode';
            $this->tanggalMulaiFormatted = null;
            $this->tanggalSelesaiFormatted = null;
            return;
        }

        try {
            $mulai = Carbon::parse($this->tanggalMulai);
            $selesai = Carbon::parse($this->tanggalSelesai);

            // Format tanggal untuk ditampilkan
            $this->tanggalMulaiFormatted = $mulai->format('d/m/Y');
            $this->tanggalSelesaiFormatted = $selesai->format('d/m/Y');

            // Hitung selisih hari (inklusif)
            $selisihHari = $mulai->diffInDays($selesai) + 1;

            // Deteksi periode berdasarkan selisih hari
            if ($selisihHari <= 1) {
                // Satu hari
                $this->periodeText = "Periode " . $mulai->translatedFormat('d F Y');
            } elseif ($selisihHari <= 7) {
                // Mingguan
                $mingguKe = ceil($mulai->day / 7);
                $this->periodeText = "Periode Minggu {$mingguKe} Bulan " . $mulai->translatedFormat('F Y');
            } elseif ($selisihHari <= 31 && $mulai->month == $selesai->month) {
                // Bulanan
                $this->periodeText = "Periode " . $mulai->translatedFormat('F Y');
            } elseif ($selisihHari <= 93) {
                // Triwulan
                $triwulan = ceil($mulai->month / 3);
                $this->periodeText = "Periode Triwulan {$triwulan} Tahun " . $mulai->year;
            } elseif ($selisihHari <= 186) {
                // Semester
                $semester = $mulai->month <= 6 ? 1 : 2;
                $this->periodeText = "Periode Semester {$semester} Tahun " . $mulai->year;
            } elseif ($mulai->year == $selesai->year) {
                // Tahunan
                $this->periodeText = "Periode Tahun " . $mulai->year;
            } else {
                // Multi tahun atau custom
                $this->periodeText = "Periode Custom";
            }
        } catch (\Exception $e) {
            $this->periodeText = 'Periode Tidak Valid';
            $this->tanggalMulaiFormatted = null;
            $this->tanggalSelesaiFormatted = null;
        }
    }
}