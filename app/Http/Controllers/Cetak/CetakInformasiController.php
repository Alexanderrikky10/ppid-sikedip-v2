<?php

namespace App\Http\Controllers\Cetak;

use App\Http\Controllers\Controller;
use App\Models\Informasi;
use App\Models\KategoriInformasi;
use App\Models\KategoriJenisInformasi;
use App\Models\KlasifikasiInformasi;
use App\Models\PerangkatDaerah;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\InformasiExport;

class CetakInformasiController extends Controller
{
    protected function getFilteredData(Request $request)
    {
        $query = Informasi::query()
            ->with([
                'perangkatDaerah',
                'klasifikasiInformasi',
                'kategoriJenisInformasi',
                'kategoriInformasi'
            ]);

        if ($request->tahun_awal) {
            $query->where('tahun', '>=', $request->tahun_awal);
        }

        if ($request->tahun_akhir) {
            $query->where('tahun', '<=', $request->tahun_akhir);
        }

        if ($request->perangkat_daerah_id) {
            $query->where('perangkat_daerah_id', $request->perangkat_daerah_id);
        }

        if ($request->klasifikasi_informasi_id) {
            $query->where('klasifikasi_informasi_id', $request->klasifikasi_informasi_id);
        }

        if ($request->kategori_jenis_informasi_id) {
            $query->where('kategori_jenis_informasi_id', $request->kategori_jenis_informasi_id);
        }

        if ($request->kategori_informasi_id) {
            $query->where('kategori_informasi_id', $request->kategori_informasi_id);
        }

        return $query->orderBy('tahun', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function print(Request $request)
    {
        $data = $this->getFilteredData($request);

        $perangkat_daerah = null;
        if ($request->perangkat_daerah_id) {
            $perangkat_daerah = PerangkatDaerah::find($request->perangkat_daerah_id);
        }

        $kategori_informasi = null;
        if ($request->kategori_informasi_id) {
            $kategori_informasi = KategoriInformasi::find($request->kategori_informasi_id);
        }

        $klasifikasi_informasi = null;
        if ($request->klasifikasi_informasi_id) {
            $klasifikasi_informasi = KlasifikasiInformasi::find($request->klasifikasi_informasi_id);
        }

        $kategori_jenis_informasi = null;
        if ($request->kategori_jenis_informasi_id) {
            $kategori_jenis_informasi = KategoriJenisInformasi::find($request->kategori_jenis_informasi_id);
        }

        return view('cetak.informasi-print', [
            'data' => $data,
            'tahun_awal' => $request->tahun_awal,
            'tahun_akhir' => $request->tahun_akhir,
            'perangkat_daerah' => $perangkat_daerah,
            'kategori_informasi' => $kategori_informasi,
            'klasifikasi_informasi' => $klasifikasi_informasi,
            'kategori_jenis_informasi' => $kategori_jenis_informasi,
            'tempat' => $request->tempat,
            'tanggal' => $request->tanggal,
        ]);
    }

    public function downloadPdf(Request $request)
    {
        $data = $this->getFilteredData($request);

        $perangkat_daerah = null;
        if ($request->perangkat_daerah_id) {
            $perangkat_daerah = PerangkatDaerah::find($request->perangkat_daerah_id);
        }

        $kategori_informasi = null;
        if ($request->kategori_informasi_id) {
            $kategori_informasi = KategoriInformasi::find($request->kategori_informasi_id);
        }

        $klasifikasi_informasi = null;
        if ($request->klasifikasi_informasi_id) {
            $klasifikasi_informasi = KlasifikasiInformasi::find($request->klasifikasi_informasi_id);
        }

        $kategori_jenis_informasi = null;
        if ($request->kategori_jenis_informasi_id) {
            $kategori_jenis_informasi = KategoriJenisInformasi::find($request->kategori_jenis_informasi_id);
        }

        $pdf = Pdf::loadView('cetak.informasi-pdf', [
            'data' => $data,
            'tahun_awal' => $request->tahun_awal,
            'tahun_akhir' => $request->tahun_akhir,
            'perangkat_daerah' => $perangkat_daerah,
            'kategori_informasi' => $kategori_informasi,
            'klasifikasi_informasi' => $klasifikasi_informasi,
            'kategori_jenis_informasi' => $kategori_jenis_informasi,
            'tempat' => $request->tempat,
            'tanggal' => $request->tanggal,
        ]);

        $pdf->setPaper('a4', 'landscape');

        $filename = 'Laporan_Informasi_' . date('YmdHis') . '.pdf';

        return $pdf->download($filename);
    }

    public function downloadExcel(Request $request)
    {
        $filename = 'Laporan_Informasi_' . date('YmdHis') . '.xlsx';

        return Excel::download(
            new InformasiExport($request->all()),
            $filename
        );
    }
}