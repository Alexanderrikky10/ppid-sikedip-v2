<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Daftar Informasi</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .header h2,
        .header h3 {
            margin: 5px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
            vertical-align: top;
        }

        th {
            background-color: #f2f2f2;
            text-align: center;
        }

        .filter-info {
            margin-bottom: 15px;
        }

        .signature {
            float: right;
            text-align: center;
            width: 250px;
            margin-top: 30px;
        }

        /* Agar saat diprint tampilan lebih rapi */
        @media print {
            .no-print {
                display: none;
            }

            @page {
                size: landscape;
                /* Orientasi Landscape */
                margin: 1cm;
            }
        }
    </style>
</head>

<body onload="window.print()">

    <div class="header">
        <h2>LAPORAN DAFTAR INFORMASI PUBLIK</h2>
        <h3>
            @if($perangkat_daerah)
                {{ strtoupper($perangkat_daerah->nama_perangkat_daerah) }}
            @else
                SEMUA PERANGKAT DAERAH
            @endif
        </h3>
        <p>Tahun: {{ $tahun_awal }} - {{ $tahun_akhir }}</p>
    </div>

    <div class="filter-info">
        @if($kategori_informasi)
            <strong>Kategori:</strong> {{ $kategori_informasi->nama_kategori }} <br>
        @endif
        @if($klasifikasi_informasi)
            <strong>Klasifikasi:</strong> {{ $klasifikasi_informasi->nama_klasifikasi }} <br>
        @endif
    </div>

    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th>Tahun</th>
                <th>Judul Informasi</th>
                <th>Perangkat Daerah</th>
                <th>Kategori</th>
                <th>Klasifikasi</th>
                <th>Tgl Publikasi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($data as $index => $item)
                <tr>
                    <td style="text-align: center;">{{ $index + 1 }}</td>
                    <td style="text-align: center;">{{ $item->tahun }}</td>
                    <td>{{ $item->judul_informasi }}</td>
                    <td>{{ $item->perangkatDaerah->nama_perangkat_daerah ?? '-' }}</td>
                    <td>{{ $item->kategoriInformasi->nama_kategori ?? '-' }}</td>
                    <td>{{ $item->klasifikasiInformasi->nama_klasifikasi ?? '-' }}</td>
                    <td style="text-align: center;">
                        {{ $item->tanggal_publikasi ? \Carbon\Carbon::parse($item->tanggal_publikasi)->format('d/m/Y') : '-' }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align: center;">Tidak ada data ditemukan.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="signature">
        <p>{{ $tempat ?? 'Pontianak' }}, {{ \Carbon\Carbon::parse($tanggal ?? now())->translatedFormat('d F Y') }}</p>
        <br><br><br>
        <p><strong>Pejabat Pengelola Informasi</strong></p>
    </div>

</body>

</html>