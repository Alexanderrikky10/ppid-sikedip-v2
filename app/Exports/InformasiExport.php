<?php

namespace App\Exports;

use App\Models\Informasi;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class InformasiExport implements
    FromCollection,
    WithHeadings,
    WithMapping,
    WithStyles,
    WithTitle,
    ShouldAutoSize
{
    protected $filters;

    public function __construct(array $filters)
    {
        $this->filters = $filters;
    }

    public function collection()
    {
        $query = Informasi::query()
            ->with([
                'perangkatDaerah',
                'kategoriInformasi',
                'klasifikasiInformasi',
                'kategoriJenisInformasi',
            ]);

        // Apply filters
        if (!empty($this->filters['tahun_awal'])) {
            $query->where('tahun', '>=', $this->filters['tahun_awal']);
        }

        if (!empty($this->filters['tahun_akhir'])) {
            $query->where('tahun', '<=', $this->filters['tahun_akhir']);
        }

        if (!empty($this->filters['perangkat_daerah_id'])) {
            $query->where('perangkat_daerah_id', $this->filters['perangkat_daerah_id']);
        }

        if (!empty($this->filters['kategori_informasi_id'])) {
            $query->where('kategori_informasi_id', $this->filters['kategori_informasi_id']);
        }

        if (!empty($this->filters['klasifikasi_informasi_id'])) {
            $query->where('klasifikasi_informasi_id', $this->filters['klasifikasi_informasi_id']);
        }

        if (!empty($this->filters['kategori_jenis_informasi_id'])) {
            $query->where('kategori_jenis_informasi_id', $this->filters['kategori_jenis_informasi_id']);
        }

        return $query->orderBy('tahun', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'Tahun',
            'Judul Informasi',
            'Perangkat Daerah',
            'Kategori Informasi',
            'Klasifikasi',
            'Kategori Jenis',
            'Ringkasan',
            'Penjelasan',
            'Format',
            'PJ Penerbit',
            'Waktu & Tempat',
            'Masa Penyimpanan',
            'Tanggal Publikasi',
            'Tanggal Input',
        ];
    }

    public function map($informasi): array
    {
        static $no = 0;
        $no++;

        return [
            $no,
            $informasi->tahun ?? '-',
            $informasi->judul_informasi ?? '-',
            $informasi->perangkatDaerah->nama_perangkat_daerah ?? '-',
            $informasi->kategoriInformasi->nama_kategori ?? '-',
            $informasi->klasifikasiInformasi->nama_klasifikasi ?? '-',
            $informasi->kategoriJenisInformasi->nama_kategori ?? '-',
            $informasi->ringkasan ?? '-',
            $informasi->penjelasan ?? '-',
            $informasi->format_informasi ?? '-',
            $informasi->pj_penerbit_informasi ?? '-',
            $informasi->waktu_tempat ?? '-',
            $informasi->waktu_penyimpanan ?? '-',
            $informasi->tanggal_publikasi ? \Carbon\Carbon::parse($informasi->tanggal_publikasi)->format('d/m/Y') : '-',
            $informasi->created_at ? $informasi->created_at->format('d/m/Y H:i') : '-',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Style untuk header row
            1 => [
                'font' => [
                    'bold' => true,
                    'size' => 11,
                    'color' => ['rgb' => 'FFFFFF']
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '4472C4']
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['rgb' => '000000']
                    ]
                ]
            ],
        ];
    }

    public function title(): string
    {
        return 'Laporan Informasi';
    }
}