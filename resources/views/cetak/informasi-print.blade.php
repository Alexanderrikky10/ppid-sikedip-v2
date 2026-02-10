<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Daftar Informasi Publik</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            margin: 20px 40px;
        }

        /* Header Judul Utama */
        .header-title {
            text-align: center;
            margin-bottom: 20px;
            font-weight: bold;
            text-transform: uppercase;
            line-height: 1.4;
        }

        /* Header Detail (Berdasarkan...) */
        .header-details {
            width: 100%;
            margin-bottom: 20px;
            font-size: 12px;
        }
        .header-details td {
            padding: 2px 0;
            border: none; /* Hilangkan border tabel detail */
            vertical-align: top;
        }
        .header-label {
            width: 250px; /* Lebar label kiri */
        }
        .header-colon {
            width: 20px;
            text-align: center;
        }

        /* Judul Per Klasifikasi (Romawi) */
        .section-title {
            font-weight: bold;
            margin-top: 20px;
            margin-bottom: 10px;
            text-transform: uppercase;
            font-size: 12px;
        }

        /* Tabel Data Utama */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }

        .data-table th, 
        .data-table td {
            border: 1px solid #000;
            padding: 6px 8px;
            vertical-align: top;
        }

        .data-table th {
            background-color: #ffffff; /* Biasanya putih di laporan dinas */
            text-align: center;
            font-weight: bold;
            vertical-align: middle;
            text-transform: uppercase;
        }

        /* Baris Nomor Kolom (1,2,3...) */
        .col-number th {
            font-size: 9px;
            padding: 2px;
            background-color: #f9f9f9;
        }

        .text-center { text-align: center; }
        
        /* Tanda Tangan */
        .signature-wrapper {
            margin-top: 40px;
            width: 100%;
            display: table; /* Hack layouting float */
        }
        .signature {
            display: table-cell;
            text-align: center;
            vertical-align: top;
            width: 35%; /* Area tanda tangan di kanan */
            margin-left: auto; /* Dorong ke kanan */
        }
        /* Spacer kiri */
        .signature-spacer { 
            display: table-cell; 
            width: 65%; 
        }

        @media print {
            @page {
                size: landscape; /* Orientasi Landscape */
                margin: 1cm;
            }
            .no-print { display: none; }
            /* Mencegah tabel terpotong jelek */
            .table-container { page-break-inside: avoid; }
        }
    </style>
</head>

<body onload="window.print()">

    <div class="header-title">
        DAFTAR INFORMASI PUBLIK<br>
        PEMERINTAH PROVINSI KALIMANTAN BARAT<br>
        TAHUN {{ $tahun_awal }} @if($tahun_awal != $tahun_akhir) - {{ $tahun_akhir }} @endif
    </div>

    <table class="header-details">
        <tr>
            <td class="header-label">Berdasarkan OPD Penerbit</td>
            <td class="header-colon">:</td>
            <td>
                @if($perangkat_daerah)
                    {{ $perangkat_daerah->nama_perangkat_daerah }}
                @else
                    SEMUA PERANGKAT DAERAH
                @endif
            </td>
        </tr>
        <tr>
            <td class="header-label">Berdasarkan Klasifikasi Informasi Publik</td>
            <td class="header-colon">:</td>
            <td>
                @if($klasifikasi_selected)
                    {{ $klasifikasi_selected->nama_klasifikasi }}
                @else
                    SEMUA KLASIFIKASI
                @endif
            </td>
        </tr>
        <tr>
            <td class="header-label">Berdasarkan Tahun</td>
            <td class="header-colon">:</td>
            <td>
                {{ $tahun_awal }} @if($tahun_awal != $tahun_akhir) s/d {{ $tahun_akhir }} @endif
            </td>
        </tr>
    </table>

    @php
        $romawi = ['I', 'II', 'III', 'IV', 'V'];
    @endphp

    @foreach($groupedData as $index => $group)
        <div class="table-container">
            <div class="section-title">
                {{ $romawi[$index] ?? ($index+1) }}. INFORMASI YANG WAJIB DISEDIAKAN DAN DIUMUMKAN SECARA {{ strtoupper($group['klasifikasi']->nama_klasifikasi) }}
            </div>

            <table class="data-table">
                <thead>
                    <tr>
                        <th width="4%">NO</th>
                        <th width="25%">NAMA INFORMASI</th>
                        <th width="15%">PEJABAT YANG MENGUASAI / PENANGGUNG JAWAB</th>
                        <th width="15%">PENANGGUNG JAWAB PEMBUATAN ATAU PENERBITAN INFORMASI</th>
                        <th width="13%">WAKTU DAN TEMPAT PEMBUATAN INFORMASI</th>
                        <th width="13%">FORMAT INFORMASI YANG TERSEDIA</th>
                        <th width="15%">JANGKA WAKTU PENYIMPANAN</th>
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
                                {{ $item->judul_informasi }}
                            </td>
                            <td>{{ $item->pejabat_pj ?? '-' }}</td>
                            
                            {{-- PENANGGUNG JAWAB PEMBUATAN / PENERBIT --}}
                            <td>{{ $item->pj_penerbit_informasi ?? ($item->perangkatDaerah->nama_perangkat_daerah ?? '-') }}</td>
                            
                            <td class="text-center">
                                {{ $item->waktu_tempat ?? 'Pontianak' }}
                                <br>
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
                            <td colspan="7" class="text-center" style="padding: 15px; font-style: italic; color: #666;">
                                Tidak ada data informasi.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    @endforeach

    <div class="signature-wrapper">
        <div class="signature-spacer"></div>
        <div class="signature">
            <p>{{ $tempat ?? 'Pontianak' }}, {{ \Carbon\Carbon::parse($tanggal ?? now())->translatedFormat('d F Y') }}</p>
            <p><strong>Pejabat Pengelola Informasi dan Dokumentasi (PPID)</strong></p>
            <br><br><br><br>
            <p style="text-decoration: underline; font-weight: bold;">( ..................................................... )</p>
            <p>NIP. ........................................</p>
        </div>
    </div>

</body>
</html>