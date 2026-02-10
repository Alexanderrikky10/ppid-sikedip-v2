<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Laporan Daftar Informasi Publik</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 10pt;
            /* Ukuran font standar PDF */
            margin: 0;
        }

        /* Layout Header Menggunakan Table agar Rapi di PDF */
        .header-table {
            width: 100%;
            border: none;
            margin-bottom: 20px;
        }

        .header-table td {
            border: none;
            padding: 2px;
            vertical-align: top;
        }

        .header-title {
            text-align: center;
            font-weight: bold;
            font-size: 12pt;
            text-transform: uppercase;
            margin-bottom: 20px;
        }

        /* Judul Per Klasifikasi */
        .section-title {
            font-weight: bold;
            font-size: 10pt;
            margin-top: 15px;
            margin-bottom: 5px;
            text-transform: uppercase;
        }

        /* Tabel Data */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            page-break-inside: auto;
        }

        .data-table th,
        .data-table td {
            border: 1px solid #000;
            padding: 5px;
            font-size: 9pt;
            vertical-align: top;
            word-wrap: break-word;
        }

        .data-table th {
            background-color: #f0f0f0;
            text-align: center;
            font-weight: bold;
        }

        /* Baris Nomor Kolom */
        .col-number th {
            font-size: 8pt;
            padding: 2px;
            background-color: #e0e0e0;
        }

        /* Utilitas Teks */
        .text-center {
            text-align: center;
        }

        .text-bold {
            font-weight: bold;
        }

        /* Tanda Tangan (Layout Table) */
        .signature-table {
            width: 100%;
            border: none;
            margin-top: 30px;
            page-break-inside: avoid;
        }

        .signature-table td {
            border: none;
            text-align: center;
            vertical-align: top;
        }

        /* Helper Page Break */
        tr {
            page-break-inside: avoid;
            page-break-after: auto;
        }
    </style>
</head>

<body>

    <div class="header-title">
        DAFTAR INFORMASI PUBLIK<br>
        PEMERINTAH PROVINSI KALIMANTAN BARAT<br>
        TAHUN {{ $tahun_awal }} @if($tahun_awal != $tahun_akhir) - {{ $tahun_akhir }} @endif
    </div>

    <table class="header-table">
        <tr>
            <td width="25%">Berdasarkan OPD Penerbit</td>
            <td width="2%">:</td>
            <td>
                @if($perangkat_daerah)
                    {{ $perangkat_daerah->nama_perangkat_daerah }}
                @else
                    SEMUA PERANGKAT DAERAH
                @endif
            </td>
        </tr>
        <tr>
            <td>Berdasarkan Klasifikasi</td>
            <td>:</td>
            <td>
                @if($klasifikasi_informasi)
                    {{ $klasifikasi_informasi->nama_klasifikasi }}
                @else
                    SEMUA KLASIFIKASI
                @endif
            </td>
        </tr>
        <tr>
            <td>Berdasarkan Tahun</td>
            <td>:</td>
            <td>
                {{ $tahun_awal }} @if($tahun_awal != $tahun_akhir) s/d {{ $tahun_akhir }} @endif
            </td>
        </tr>
    </table>

    @php
        $romawi = ['I', 'II', 'III', 'IV', 'V'];
    @endphp

    @foreach($groupedData as $index => $group)
        <div style="page-break-inside: avoid;">
            <div class="section-title">
                {{ $romawi[$index] ?? ($index + 1) }}. INFORMASI YANG WAJIB DISEDIAKAN DAN DIUMUMKAN SECARA
                {{ strtoupper($group['klasifikasi']->nama_klasifikasi) }}
            </div>

            <table class="data-table">
                <thead>
                    <tr>
                        <th width="5%">NO</th>
                        <th width="25%">NAMA INFORMASI</th>
                        <th width="15%">PEJABAT YANG MENGUASAI / PENANGGUNG JAWAB</th>
                        <th width="15%">PENANGGUNG JAWAB PEMBUATAN / PENERBIT</th>
                        <th width="12%">WAKTU DAN TEMPAT</th>
                        <th width="13%">FORMAT TERSEDIA</th>
                        <th width="15%">JANGKA WAKTU SIMPAN</th>
                    </tr>
                    <tr class="col-number">
                        <th>1</th>
                        <th>2</th>
                        <th>3</th>
                        <th>4</th>
                        <th>5</th>
                        <th>6</th>
                        <th>7</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($group['data'] as $idx => $item)
                        <tr>
                            <td class="text-center">{{ $idx + 1 }}</td>
                            <td>
                                <strong>{{ $item->judul_informasi }}</strong>
                                @if($item->ringkasan)
                                    <br><span
                                        style="font-size: 8pt; font-style: italic; color: #555;">({{ $item->ringkasan }})</span>
                                @endif
                            </td>
                            <td>{{ $item->pejabat_pj ?? '-' }}</td>
                            <td>{{ $item->pj_penerbit_informasi ?? ($item->perangkatDaerah->nama_perangkat_daerah ?? '-') }}
                            </td>
                            <td class="text-center">
                                {{ $item->waktu_tempat ?? 'Pontianak' }}<br>
                                {{ $item->tahun }}
                            </td>
                            <td class="text-center">
                                @if(is_array($item->format_informasi))
                                    {{ implode(', ', $item->format_informasi) }}
                                @else
                                    {{ $item->format_informasi ?? '-' }}
                                @endif
                            </td>
                            <td class="text-center">{{ $item->waktu_penyimpanan ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center" style="padding: 15px; font-style: italic;">
                                Tidak ada data informasi untuk klasifikasi ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    @endforeach

    <table class="signature-table">
        <tr>
            <td width="60%"></td>
            <td width="40%">
                <p>
                    {{ $tempat ?? 'Pontianak' }},
                    {{ \Carbon\Carbon::parse($tanggal ?? now())->translatedFormat('d F Y') }}<br>
                    <strong>Pejabat Pengelola Informasi dan Dokumentasi (PPID)</strong>
                </p>
                <br><br><br><br>
                <p>
                    <strong>( ..................................................... )</strong><br>
                    NIP. ........................................
                </p>
            </td>
        </tr>
    </table>

</body>

</html>