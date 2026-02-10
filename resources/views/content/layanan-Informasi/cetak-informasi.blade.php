@extends('layout-app.app.app')

@section('content')
    {{-- PAGE HEADER --}}
    <div class="bg-gradient-to-r from-green-900 to-green-800 pt-32 pb-10 text-white relative overflow-hidden">
        <div class="absolute inset-0 opacity-10"
            style="background-image: radial-gradient(#dcfce7 1px, transparent 1px); background-size: 24px 24px;"></div>

        <div class="container mx-auto px-6 relative z-10 text-center">
            <h1 class="text-3xl font-bold mb-2">Laporan Daftar Informasi</h1>
            <p class="text-green-100 opacity-90 text-sm max-w-2xl mx-auto">
                Filter dan cetak laporan daftar informasi publik sesuai kebutuhan Anda.
            </p>
        </div>
    </div>

    {{-- MAIN CONTENT --}}
    <section class="py-10 bg-gray-50 min-h-screen">
        <div class="container mx-auto px-6">

            {{-- Form Wrapper --}}
            {{-- Action mengarah ke route cetak PDF --}}
            <form action="{{ route('cetak.informasi.pdf') }}" method="GET" target="_blank">
                @csrf

                <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">

                    {{-- LEFT COLUMN: DATA FILTERS (Span 8) --}}
                    <div class="lg:col-span-8 space-y-6">

                        {{-- Card 1: Filter Utama --}}
                        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                            <div class="bg-green-50/50 px-6 py-4 border-b border-green-100 flex items-center">
                                <div
                                    class="w-8 h-8 rounded-lg bg-green-100 text-green-600 flex items-center justify-center mr-3">
                                    <i class="fa-solid fa-filter"></i>
                                </div>
                                <h3 class="font-bold text-gray-800">Filter Utama Laporan</h3>
                            </div>

                            <div class="p-6 space-y-6">
                                {{-- Row 1: Tahun --}}
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    {{-- Dari Tahun --}}
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 mb-2">Dari Tahun</label>
                                        <div class="relative">
                                            <div
                                                class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                                                <i class="fa-regular fa-calendar"></i>
                                            </div>
                                            {{-- Name disesuaikan dengan Controller: tahun_awal --}}
                                            <select name="tahun_awal"
                                                class="w-full pl-10 pr-4 py-2.5 rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500 shadow-sm transition-colors cursor-pointer bg-white">
                                                <option value="" disabled selected>Pilih Tahun...</option>
                                                @foreach($tahunList as $thn)
                                                    <option value="{{ $thn }}">{{ $thn }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    {{-- Sampai Tahun --}}
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 mb-2">Sampai Tahun <span
                                                class="text-red-500">*</span></label>
                                        <div class="relative">
                                            <div
                                                class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                                                <i class="fa-regular fa-calendar-check"></i>
                                            </div>
                                            {{-- Name disesuaikan dengan Controller: tahun_akhir --}}
                                            <select name="tahun_akhir" required
                                                class="w-full pl-10 pr-4 py-2.5 rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500 shadow-sm transition-colors cursor-pointer bg-white">
                                                <option value="" disabled selected>Pilih Tahun...</option>
                                                @foreach($tahunList as $thn)
                                                    <option value="{{ $thn }}">{{ $thn }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                {{-- Row 2: Perangkat Daerah --}}
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Perangkat Daerah
                                        Penerbit</label>
                                    <div class="relative">
                                        <div
                                            class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                                            <i class="fa-solid fa-building-columns"></i>
                                        </div>
                                        {{-- Name disesuaikan: perangkat_daerah_id --}}
                                        <select name="perangkat_daerah_id"
                                            class="w-full pl-10 pr-4 py-2.5 rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500 shadow-sm transition-colors cursor-pointer bg-white">
                                            <option value="">Semua Perangkat Daerah</option>

                                            {{-- Loop Hierarchy OPD --}}
                                            @foreach($opdList as $kategori)
                                                {{-- 1. Tampilkan Nama Kategori --}}
                                                <option disabled class="font-extrabold text-black bg-gray-100">
                                                    ⭐⭐ {{ strtoupper($kategori->nama_kategori) }}
                                                </option>

                                                @foreach($kategori->perangkatDaerahs as $parentOpd)
                                                    {{-- 2. Tampilkan Parent (Induk) --}}
                                                    <option value="{{ $parentOpd->id }}" class="font-bold text-gray-800">
                                                        &nbsp;&nbsp;★ {{ $parentOpd->nama_perangkat_daerah }}
                                                    </option>

                                                    {{-- 3. Tampilkan Children (Anak) --}}
                                                    {{-- Perubahan: Menghapus pengecualian kategori ID 2, sekarang cukup cek apakah
                                                    punya anak --}}
                                                    @if($parentOpd->children->isNotEmpty())
                                                        @foreach($parentOpd->children as $childOpd)
                                                            <option value="{{ $childOpd->id }}" class="text-gray-600">
                                                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;├─
                                                                {{ $childOpd->nama_perangkat_daerah }}
                                                            </option>
                                                        @endforeach
                                                    @endif
                                                @endforeach
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Card 2: Kategori & Klasifikasi --}}
                        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                            <div class="bg-green-50/50 px-6 py-4 border-b border-green-100 flex items-center">
                                <div
                                    class="w-8 h-8 rounded-lg bg-green-100 text-green-600 flex items-center justify-center mr-3">
                                    <i class="fa-solid fa-layer-group"></i>
                                </div>
                                <h3 class="font-bold text-gray-800">Kategori & Klasifikasi</h3>
                            </div>

                            <div class="p-6">
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                    {{-- Kategori Informasi --}}
                                    <div>
                                        <label
                                            class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-2">Kategori
                                            Informasi</label>
                                        {{-- Name: kategori_informasi_id --}}
                                        <select name="kategori_informasi_id"
                                            class="w-full px-4 py-2.5 rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500 shadow-sm bg-white text-sm">
                                            <option value="">Semua Kategori</option>
                                            {{-- Asumsi Anda mengirim $kategoriInformasiList dari controller --}}
                                            {{-- Jika belum ada, bisa hardcode atau ambil dari DB --}}
                                            <option value="1">Pemerintah Provinsi</option>
                                            <option value="2">Pemerintah Kabupaten/Kota</option>
                                            <option value="3">BUMD</option>
                                        </select>
                                    </div>

                                    {{-- Klasifikasi --}}
                                    <div>
                                        <label
                                            class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-2">Klasifikasi
                                            Informasi</label>
                                        {{-- Name: klasifikasi_informasi_id --}}
                                        <select name="klasifikasi_informasi_id"
                                            class="w-full px-4 py-2.5 rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500 shadow-sm bg-white text-sm">
                                            <option value="">Semua Klasifikasi</option>
                                            @foreach($klasifikasilist as $klasifikasi)
                                                <option value="{{ $klasifikasi->id }}">{{ $klasifikasi->nama_klasifikasi }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    {{-- Jenis Informasi --}}
                                    <div>
                                        <label
                                            class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-2">Jenis
                                            Informasi</label>
                                        {{-- Name: kategori_jenis_informasi_id --}}
                                        <select name="kategori_jenis_informasi_id"
                                            class="w-full px-4 py-2.5 rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500 shadow-sm bg-white text-sm">
                                            <option value="">Semua Jenis</option>
                                            @foreach($kategoriList as $kat)
                                                <option value="{{ $kat->id }}">
                                                    {{ $kat->nama_jenis_informasi ?? $kat->nama_kategori }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                    {{-- RIGHT COLUMN: PRINT DETAILS & ACTION (Span 4) --}}
                    <div class="lg:col-span-4 space-y-6">

                        <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden sticky top-28">
                            <div
                                class="bg-gray-900 px-6 py-4 border-b border-gray-800 text-white flex items-center justify-between">
                                <h3 class="font-bold flex items-center">
                                    <i class="fa-solid fa-print mr-2 text-green-400"></i> Detail Cetak
                                </h3>
                            </div>

                            <div class="p-6 space-y-5">
                                {{-- Tempat Cetak --}}
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Tempat Cetak <span
                                            class="text-red-500">*</span></label>
                                    <div class="relative">
                                        <div
                                            class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                                            <i class="fa-solid fa-map-pin"></i>
                                        </div>
                                        {{-- Name: tempat --}}
                                        <input type="text" name="tempat" value="Pontianak" placeholder="Cth: Pontianak"
                                            class="w-full pl-10 pr-4 py-2.5 rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500 shadow-sm"
                                            required>
                                    </div>
                                </div>

                                {{-- Tanggal Cetak --}}
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Tanggal Cetak</label>
                                    <div class="relative">
                                        <div
                                            class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                                            <i class="fa-regular fa-calendar-days"></i>
                                        </div>
                                        {{-- Name: tanggal --}}
                                        <input type="date" name="tanggal" value="{{ date('Y-m-d') }}"
                                            class="w-full pl-10 pr-4 py-2.5 rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500 shadow-sm text-gray-600">
                                    </div>
                                </div>

                                <hr class="border-gray-100 my-2">

                                {{-- Action Buttons --}}
                                <div class="space-y-3 pt-2">
                                    {{-- Tombol Cetak PDF --}}
                                    <button type="submit" formaction="{{ route('cetak.informasi.pdf') }}"
                                        class="w-full flex items-center justify-center bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-4 rounded-lg shadow-md transition-all transform hover:scale-[1.02] duration-200 group">
                                        <i class="fa-solid fa-file-pdf mr-2 group-hover:animate-bounce"></i>
                                        Download PDF
                                    </button>

                                    {{-- Tombol Print View (Opsional, jika ingin lihat di browser dulu) --}}
                                    <button type="submit" formaction="{{ route('cetak.informasi.laporan') }}"
                                        formtarget="_blank"
                                        class="w-full flex items-center justify-center bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-lg shadow-md transition-all transform hover:scale-[1.02] duration-200">
                                        <i class="fa-solid fa-print mr-2"></i>
                                        Lihat / Print
                                    </button>

                                    <button type="reset"
                                        class="w-full flex items-center justify-center bg-white border border-gray-300 text-gray-600 hover:bg-gray-50 font-semibold py-3 px-4 rounded-lg transition-colors duration-200">
                                        <i class="fa-solid fa-rotate-right mr-2"></i>
                                        Reset Filter
                                    </button>
                                </div>
                            </div>

                            {{-- Info Footer --}}
                            <div class="bg-gray-50 px-6 py-3 border-t border-gray-100">
                                <p class="text-xs text-gray-500 text-center">
                                    Pastikan data filter sudah sesuai sebelum mencetak.
                                </p>
                            </div>
                        </div>

                    </div>

                </div>
            </form>

        </div>
    </section>
@endsection