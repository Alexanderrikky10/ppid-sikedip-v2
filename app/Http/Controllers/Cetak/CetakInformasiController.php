<?php

namespace App\Http\Controllers\Cetak;

use App\Http\Controllers\Controller;
use App\Models\Informasi;
use App\Models\KlasifikasiInformasi;
use App\Models\PerangkatDaerah;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\InformasiExport;

class CetakInformasiController extends Controller
{
    // Fungsi Private untuk Grouping Data (Tetap digunakan untuk logika tabel)
    private function getGroupedData(Request $request)
    {
        $klasifikasiQuery = KlasifikasiInformasi::query();

        if ($request->klasifikasi_informasi_id) {
            $klasifikasiQuery->where('id', $request->klasifikasi_informasi_id);
        }

        // Urutkan ID agar urutannya: Berkala (1), Serta Merta (2), Setiap Saat (3)
        $listKlasifikasi = $klasifikasiQuery->orderBy('id', 'asc')->get();

        $reportData = [];

        foreach ($listKlasifikasi as $klasifikasi) {
            $query = Informasi::query()
                ->with(['perangkatDaerah', 'kategoriJenisInformasi', 'kategoriInformasi'])
                ->where('klasifikasi_informasi_id', $klasifikasi->id);

            // Filter
            if ($request->tahun_awal)
                $query->where('tahun', '>=', $request->tahun_awal);
            if ($request->tahun_akhir)
                $query->where('tahun', '<=', $request->tahun_akhir);
            if ($request->perangkat_daerah_id)
                $query->where('perangkat_daerah_id', $request->perangkat_daerah_id);
            if ($request->kategori_jenis_informasi_id)
                $query->where('kategori_jenis_informasi_id', $request->kategori_jenis_informasi_id);
            if ($request->kategori_informasi_id)
                $query->where('kategori_informasi_id', $request->kategori_informasi_id);

            $dataInformasi = $query->orderBy('tahun', 'desc')->orderBy('created_at', 'desc')->get();

            // Masukkan ke array report
            $reportData[] = [
                'klasifikasi' => $klasifikasi,
                'data' => $dataInformasi
            ];
        }

        return $reportData;
    }

    public function print(Request $request)
    {
        $groupedData = $this->getGroupedData($request);

        // Ambil Data Objek untuk Header Laporan
        $perangkat_daerah = $request->perangkat_daerah_id ? PerangkatDaerah::find($request->perangkat_daerah_id) : null;
        $klasifikasi_selected = $request->klasifikasi_informasi_id ? KlasifikasiInformasi::find($request->klasifikasi_informasi_id) : null;

        return view('cetak.informasi-print', [
            'groupedData' => $groupedData,
            'tahun_awal' => $request->tahun_awal,
            'tahun_akhir' => $request->tahun_akhir,
            'perangkat_daerah' => $perangkat_daerah,
            'klasifikasi_selected' => $klasifikasi_selected, // Kirim ke view untuk header
            'tempat' => $request->tempat,
            'tanggal' => $request->tanggal,
        ]);
    }

    public function downloadPdf(Request $request)
    {
        // 1. Ambil Data Grouping
        $groupedData = $this->getGroupedData($request);

        // 2. Ambil Data Header
        $perangkat_daerah = $request->perangkat_daerah_id ? PerangkatDaerah::find($request->perangkat_daerah_id) : null;

        // Ambil Klasifikasi
        $klasifikasi_obj = $request->klasifikasi_informasi_id ? KlasifikasiInformasi::find($request->klasifikasi_informasi_id) : null;

        $pdf = Pdf::loadView('cetak.informasi-pdf', [
            'groupedData' => $groupedData,
            'tahun_awal' => $request->tahun_awal,
            'tahun_akhir' => $request->tahun_akhir,
            'perangkat_daerah' => $perangkat_daerah,

            // --- PERBAIKAN DI SINI ---
            // Ubah key 'klasifikasi_selected' menjadi 'klasifikasi_informasi'
            'klasifikasi_informasi' => $klasifikasi_obj,

            'tempat' => $request->tempat,
            'tanggal' => $request->tanggal,
        ]);

        $pdf->setPaper('a4', 'landscape');
        $filename = 'Laporan_DIP_' . date('YmdHis') . '.pdf';

        return $pdf->download($filename);
    }

    public function downloadExcel(Request $request)
    {
        // Note: Untuk Excel biasanya struktur grouping sulit dilakukan.
        // Jadi kita export data mentah (flat) saja menggunakan query biasa.
        // Jika ingin grouping di excel, logic di InformasiExport harus diubah drastis.

        $filename = 'Laporan_Informasi_' . date('YmdHis') . '.xlsx';

        return Excel::download(
            new InformasiExport($request->all()),
            $filename
        );
    }
}