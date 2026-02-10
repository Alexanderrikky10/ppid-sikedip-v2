@extends('layout-app.app.app')

@section('content')

        {{-- LOGIKA PHP: MENENTUKAN KONTEN HEADER & URL GAMBAR MINIO --}}
        @php
    use Illuminate\Support\Facades\Storage;

    // 1. Cek apakah variabel $lockedOpd dikirim dari Controller
    $isSpecificOpd = isset($lockedOpd);

    // 2. Setup Default (Tampilan Utama - Fallback)
    $judulUtama = 'Daftar Informasi Publik (DIP)';
    $deskripsi = 'Temukan seluruh daftar informasi publik yang dikelola oleh Badan Usaha Milik Daerah (BUMD) di Kalimantan Barat.';
    $badgeText = 'PPID Utama BUMD';
    $logoUrl = asset('images/logo.png'); // Default Logo Lokal

    // 3. Override jika sedang di halaman Instansi Spesifik
    if ($isSpecificOpd) {
        $judulUtama = $lockedOpd->nama_perangkat_daerah;
        $deskripsi = 'Daftar Informasi Publik yang dikelola secara resmi oleh ' . $lockedOpd->nama_perangkat_daerah . '.';
        $badgeText = 'PPID Pelaksana BUMD';

        // --- LOGIKA GAMBAR DENGAN MINIO TEMPORARY URL ---
        if (!empty($lockedOpd->images)) {
            if (filter_var($lockedOpd->images, FILTER_VALIDATE_URL)) {
                $logoUrl = $lockedOpd->images;
            } else {
                try {
                    $logoUrl = Storage::disk('minio')->temporaryUrl(
                        $lockedOpd->images,
                        now()->addMinutes(60)
                    );
                } catch (\Exception $e) {
                    try {
                        $logoUrl = Storage::url($lockedOpd->images);
                    } catch (\Exception $ex) {
                        $logoUrl = asset('images/logo.png');
                    }
                }
            }
        }
    }
        @endphp

        {{-- 1. PAGE HEADER DINAMIS --}}
        <div class="bg-gradient-to-r from-green-900 to-green-800 pt-28 pb-12 text-white relative overflow-hidden">
            {{-- Pattern Dots Background --}}
            <div class="absolute inset-0 opacity-10"
                style="background-image: radial-gradient(#dcfce7 1px, transparent 1px); background-size: 24px 24px;"></div>

            <div class="container mx-auto px-6 relative z-10">
                <div class="flex flex-col items-center justify-center text-center gap-5">

                    {{-- KOLOM 1: LOGO --}}
                    <div class="relative group">
                        {{-- Efek Glow --}}
                        <div
                            class="absolute -inset-4 bg-green-400/20 rounded-full blur-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-500">
                        </div>

                        {{-- Container Logo --}}
                        <div
                            class="relative p-4 bg-white/10 rounded-2xl backdrop-blur-sm border border-white/20 shadow-xl shadow-green-900/20 transition-transform duration-300 hover:scale-105 flex items-center justify-center w-28 h-28 md:w-32 md:h-32">
                            {{-- GAMBAR LOGO FINAL --}}
                            <img src="{{ $logoUrl }}" alt="Logo Instansi" class="w-full h-full object-contain drop-shadow-lg"
                                onerror="this.onerror=null; this.src='{{ asset('images/logo.png') }}';" />
                        </div>
                    </div>

                    {{-- KOLOM 2: TEKS --}}
                    <div class="max-w-3xl mx-auto">
                        {{-- Badge Kecil --}}
                        <span
                            class="inline-block py-1 px-3 rounded-full bg-green-500/30 border border-green-400/30 text-green-50 text-[10px] md:text-xs font-semibold mb-3 backdrop-blur-md uppercase tracking-wide">
                            {{ $badgeText }}
                        </span>

                        {{-- Judul Dinamis --}}
                        <h1
                            class="text-2xl md:text-4xl font-extrabold mb-3 tracking-tight leading-tight drop-shadow-sm uppercase">
                            {{ $judulUtama }}
                        </h1>

                        {{-- Deskripsi Dinamis --}}
                        <p class="text-green-100 opacity-90 text-sm md:text-base leading-relaxed font-light mx-auto px-2">
                            {{ $deskripsi }}
                        </p>
                    </div>

                </div>
            </div>
        </div>

        {{-- MAIN CONTENT --}}
        <section class="py-10 bg-gray-50 min-h-screen">
            <div class="container mx-auto px-6">

                {{-- 3. TABEL INFORMASI --}}
                @include('content.daftar-informasi.tabel-informasi')

                {{-- 4. DASHBOARD SECTION (Statistik & Info Terbaru) --}}
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 mb-16">
                    {{-- A. Statistik Chart --}}
                    <div class="lg:col-span-4 space-y-6">
                        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 h-full">
                            <h3 class="font-bold text-gray-800 text-lg mb-6 border-b border-gray-100 pb-2">
                                <i class="fa-solid fa-chart-pie mr-2 text-green-600"></i> Statistik Klasifikasi
                            </h3>
                            <div class="space-y-5 mb-8">
                                @foreach ($chartData['labels'] as $index => $label)
                                    @php
        $val = $chartData['series'][$index];
        $total = array_sum($chartData['series']);
        $percent = $total > 0 ? ($val / $total) * 100 : 0;
                                    @endphp
                                    <div>
                                        <div class="flex justify-between text-sm mb-1">
                                            <span class="font-medium text-gray-700">{{ $label }}</span>
                                            <span class="font-bold text-green-700">{{ $val }}</span>
                                        </div>
                                        <div class="w-full bg-gray-100 rounded-full h-2">
                                            <div class="bg-green-600 h-2 rounded-full transition-all duration-1000"
                                                style="width: {{ $percent }}%"></div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <div id="klasifikasiChart" class="flex justify-center"></div>
                        </div>
                    </div>

                    {{-- B. Informasi Terbaru --}}
                    <div class="lg:col-span-8">
                        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 h-full">
                            <div class="flex justify-between items-center mb-6 border-b border-gray-100 pb-2">
                                <h3 class="font-bold text-gray-800 text-lg">
                                    <i class="fa-solid fa-bolt mr-2 text-orange-500"></i> Informasi Terbaru
                                </h3>
                            </div>
                            <div class="space-y-0">
                                @forelse($informasiTerbaru as $info)
                                    <div onclick="showDetail({{ $info->id }})"
                                        class="relative pl-8 py-4 border-l-2 border-green-100 last:pb-0 hover:bg-green-50/30 transition-colors rounded-r-lg group cursor-pointer">
                                        <div
                                            class="absolute -left-[9px] top-5 w-4 h-4 bg-white border-4 border-green-500 rounded-full group-hover:scale-110 transition-transform">
                                        </div>
                                        <div class="flex items-center gap-2 mb-1">
                                            <span
                                                class="bg-green-100 text-green-800 text-[10px] font-bold px-2 py-0.5 rounded border border-green-200">
                                                {{ \Carbon\Carbon::parse($info->created_at)->format('Y') }}
                                            </span>
                                            <span class="text-xs text-gray-400">
                                                <i class="fa-regular fa-clock mr-1"></i>
                                                {{ \Carbon\Carbon::parse($info->created_at)->translatedFormat('d F Y, H:i') }} WIB
                                            </span>
                                        </div>
                                        <h4
                                            class="text-base font-bold text-gray-800 mb-1 group-hover:text-green-700 transition-colors line-clamp-1">
                                            {{ $info->judul_informasi }}
                                        </h4>
                                        <div class="flex flex-wrap gap-y-1 gap-x-4 text-xs text-gray-500">
                                            <span class="flex items-center">
                                                <i class="fa-solid fa-building-columns mr-1.5 text-gray-400"></i>
                                                {{ Str::limit($info->pj_penerbit_informasi ?? 'Tidak diketahui', 40) }}
                                            </span>
                                            @if ($info->klasifikasiInformasi)
                                                <span class="flex items-center">
                                                    <i class="fa-solid fa-tag mr-1.5 text-gray-400"></i>
                                                    {{ $info->klasifikasiInformasi->nama_klasifikasi }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                @empty
                                    <div class="text-center py-10 text-gray-400">
                                        <i class="fa-regular fa-folder-open text-4xl mb-3"></i>
                                        <p>Belum ada informasi terbaru.</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>

                {{-- 5. GRID BUMD LAINNYA (DI BAWAH) --}}
                @if(isset($BumdUtama))
                    <div class="border-t border-gray-200 pt-10 mt-10" x-data="{ searchBumd: '', activeTab: 'all' }">
                        <h2 class="text-2xl font-bold text-gray-800 mb-6 text-center">Jelajahi BUMD Lainnya</h2>

                        {{-- Search Bar untuk Grid Bawah --}}
                        <div class="max-w-md mx-auto mb-8 relative">
                            <i class="fa-solid fa-magnifying-glass absolute left-3 top-3 text-gray-400"></i>
                            <input type="text" x-model="searchBumd" placeholder="Cari BUMD..."
                                class="w-full pl-10 pr-4 py-2 rounded-full border border-gray-300 focus:border-green-500 focus:ring-green-500 outline-none">
                        </div>

                        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-6">
                            @foreach($BumdUtama as $bumd)
                                    {{-- LOGIKA GENERATE URL GAMBAR UNTUK TIAP ITEM --}}
                                    @php
                                        $itemLogoUrl = asset('images/logo.png'); // Default
                                        if (!empty($bumd->images)) {
                                            if (filter_var($bumd->images, FILTER_VALIDATE_URL)) {
                                                $itemLogoUrl = $bumd->images;
                                            } else {
                                                try {
                                                    $itemLogoUrl = Storage::disk('minio')->temporaryUrl(
                                                        $bumd->images,
                                                        now()->addMinutes(60)
                                                    );
                                                } catch (\Exception $e) {
                                                    try {
                                                        $itemLogoUrl = Storage::url($bumd->images);
                                                    } catch (\Exception $ex) {
                                                        $itemLogoUrl = asset('images/logo.png');
                                                    }
                                                }
                                            }
                                        }
                                    @endphp

                                <a href="{{ route('daftar-informasi-bumd.list', ['slug' => $bumd->slug]) }}"
                                        class="group bg-white rounded-xl p-4 shadow-sm hover:shadow-md border border-gray-100 hover:border-green-400 transition-all text-center flex flex-col items-center h-full"
                                        x-show="searchBumd === '' || '{{ strtolower($bumd->nama_perangkat_daerah) }}'.includes(searchBumd.toLowerCase())">

                                        {{-- Image Container (Menggantikan Icon) --}}
                                        <div
                                            class="w-20 h-20 bg-gray-50 rounded-xl flex items-center justify-center mb-3 group-hover:bg-green-50 transition-colors overflow-hidden p-2">
                                            <img src="{{ $itemLogoUrl }}" alt="Logo {{ $bumd->nama_perangkat_daerah }}"
                                                class="w-full h-full object-contain transition-transform duration-300 group-hover:scale-110"
                                                onerror="this.onerror=null; this.src='{{ asset('images/logo.png') }}';">
                                        </div>

                                        {{-- Nama --}}
                                        <h4 class="text-sm font-semibold text-gray-700 group-hover:text-green-700 line-clamp-2">
                                            {{ $bumd->nama_perangkat_daerah }}
                                        </h4>
                                    </a>
                            @endforeach
                        </div>
                    </div>
                @endif

            </div>
        </section>

        {{-- INCLUDE POPUP --}}
        @include('content.daftar-informasi.popup-informasi')

        {{-- SCRIPTS --}}
        <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                // Chart Logic
                @if(isset($chartData) && count($chartData['series']) > 0)
                    var options = {
                        series: @json($chartData['series']),
                        labels: @json($chartData['labels']),
                        chart: { type: 'donut', height: 280, fontFamily: 'Inter, sans-serif' },
                        colors: ['#16a34a', '#0d9488', '#ea580c', '#2563eb', '#6366f1'],
                        plotOptions: { pie: { donut: { size: '65%', labels: { show: true, total: { show: true, label: 'Total', color: '#374151', formatter: function (w) { return w.globals.seriesTotals.reduce((a, b) => { return a + b }, 0) } } } } } },
                        dataLabels: { enabled: false },
                        legend: { position: 'bottom', fontSize: '12px', markers: { radius: 12 } },
                        tooltip: { y: { formatter: function (value) { return value + " Dokumen" } } }
                    };
                    var chart = new ApexCharts(document.querySelector("#klasifikasiChart"), options);
                    chart.render();
                @else
                    document.querySelector("#klasifikasiChart").innerHTML = "<p class='text-center text-sm text-gray-400 py-10'>Data statistik belum tersedia</p>";
                @endif
                        });

            // AJAX POPUP Logic
            const modal = document.getElementById('infoModal');
            function closeModal() { modal.classList.add('hidden'); }
            function showDetail(id) {
                modal.classList.remove('hidden');
                document.getElementById('modal-judul').innerText = 'Memuat Data...';
                // Reset fields...
                ['nomor', 'tanggal', 'jenis', 'klasifikasi', 'tipe', 'ukuran', 'penerbit'].forEach(id => {
                    const el = document.getElementById('modal-' + id);
                    if (el) el.innerText = '...';
                });

                fetch(`/detail-informasi/${id}`)
                    .then(response => response.json())
                    .then(data => {
                        document.getElementById('modal-judul').innerText = data.judul_informasi;
                        document.getElementById('modal-nomor').innerText = data.nomor_dokumen || '-';
                        document.getElementById('modal-tanggal').innerText = data.tanggal_publikasi;
                        document.getElementById('modal-jenis').innerText = data.jenis_informasi;
                        document.getElementById('modal-klasifikasi').innerText = data.klasifikasi_informasi;
                        document.getElementById('modal-tipe').innerText = data.tipe_dokumen;
                        document.getElementById('modal-ukuran').innerText = data.ukuran_berkas;
                        document.getElementById('modal-penerbit').innerText = data.penerbit;

                        const dlBtn = document.getElementById('modal-download-btn');
                        if (dlBtn) dlBtn.href = data.file_url;

                        const detailBtn = document.getElementById('modal-detail-btn');
                        if (detailBtn) detailBtn.href = `/detail-informasi/baca/${data.slug}`;
                    })
                    .catch(error => { console.error('Error:', error); document.getElementById('modal-judul').innerText = 'Gagal memuat data'; });
            }
        </script>
@endsection