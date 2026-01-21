<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Laporan Daftar Informasi</title>
    <style>
        /* Reset dasar */
        body {
            font-family: Arial, sans-serif;
            /* Font standar aman untuk PDF */
            font-size: 11px;
            /* Ukuran font sedikit dikecilkan agar muat banyak */
            margin: 0;
            padding: 0;
        }

        /* Header Laporan */
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #000;
            /* Garis bawah kop */
            padding-bottom: 10px;
        }

        .header h2 {
            margin: 0;
            font-size: 16px;
            text-transform: uppercase;
        }

        .header p {
            margin: 2px 0;
        }

        /* Informasi Filter */
        .meta-info {
            margin-bottom: 15px;
            width: 100%;
        }

        .meta-info table {
            border: none;
            width: 100%;
        }

        .meta-info td {
            border: none;
            padding: 2px;
            vertical-align: top;
        }

        /* Tabel Data Utama */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .data-table th,
        .data-table td {
            border: 1px solid #000;
            padding: 6px;
            text-align: left;
            vertical-align: top;
        }

        .data-table th {
            background-color: #e0e0e0;
            /* Warna abu-abu untuk header */
            text-align: center;
            font-weight: bold;
        }

        /* Mencegah baris tabel terpotong saat pindah halaman */
        tr {
            page-break-inside: avoid;
        }

        /* Tanda Tangan */
        .signature-container {
            width: 100%;
            margin-top: 30px;
        }

        /* Trik float untuk tanda tangan di kanan bawah */
        .signature-box {
            float: right;
            width: 250px;
            text-align: center;
        }

        /* Helper untuk membersihkan float */
        .clearfix::after {
            content: "";
            clear: both;
            display: table;
        }
    </style>
</head>

<body>

    <div class="header">
        <h2>LAPORAN DAFTAR INFORMASI PUBLIK</h2>
        <p>
            @if($perangkat_daerah)
                <strong>{{ strtoupper($perangkat_daerah->nama_perangkat_daerah) }}</strong>
            @else
                <strong>PEMERINTAH KABUPATEN KUBU RAYA</strong>
            @endif
        </p>
        <p>Periode Tahun: {{ $tahun_awal }} s/d {{ $tahun_akhir }}</p>
    </div>

    <div class="meta-info">
        <table>
            @if($kategori_informasi)
                <tr>
                    <td width="120"><strong>Kategori Informasi</strong></td>
                    <td width="10">:</td>
                    <td>{{ $kategori_informasi->nama_kategori }}</td>
                </tr>
            @endif
            @if($klasifikasi_informasi)
                <tr>
                    <td><strong>Klasifikasi</strong></td>
                    <td>:</td>
                    <td>{{ $klasifikasi_informasi->nama_klasifikasi }}</td>
                </tr>
            @endif
            @if($kategori_jenis_informasi)
                <tr>
                    <td><strong>Jenis Informasi</strong></td>
                    <td>:</td>
                    <td>{{ $kategori_jenis_informasi->nama_kategori }}</td>
                </tr>
            @endif
        </table>
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="8%">Tahun</th>
                <th width="30%">Judul Informasi</th>
                <th width="20%">Perangkat Daerah</th>
                <th width="15%">Kategori</th>
                <th width="12%">Klasifikasi</th>
                <th width="10%">Tgl Publikasi</th>
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
                    <td colspan="7" style="text-align: center; padding: 20px;">
                        <em>Tidak ada data informasi yang ditemukan untuk filter ini.</em>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="signature-container clearfix">
        <div class="signature-box">
            <p>{{ $tempat ?? 'Pontianak' }}, {{ \Carbon\Carbon::parse($tanggal ?? now())->translatedFormat('d F Y') }}
            </p>
            <p>Pejabat Pengelola Informasi,</p>
            <br><br><br><br>
            <p><strong>__________________________</strong></p>
            <p>NIP. ........................................</p>
        </div>
    </div>

</body>

</html>