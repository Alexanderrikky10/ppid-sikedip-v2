@extends('layout-app.app.app')

@section('content')

    {{-- 1. PAGE HEADER DENGAN LOGO DI TENGAH (UKURAN LEBIH KECIL) --}}
            <div class="bg-gradient-to-r from-green-900 to-green-800 pt-28 pb-12 text-white relative overflow-hidden">
                {{-- Pattern Dots Background --}}
                <div class="absolute inset-0 opacity-10"
                    style="background-image: radial-gradient(#dcfce7 1px, transparent 1px); background-size: 24px 24px;"></div>

                <div class="container mx-auto px-6 relative z-10">
                    {{-- Flex Container: Gap dikurangi jadi gap-5 --}}
                    <div class="flex flex-col items-center justify-center text-center gap-5">

                        {{-- KOLOM 1: LOGO --}}
                        <div class="relative group">
                            {{-- Efek Glow --}}
                            <div
                                class="absolute -inset-4 bg-green-400/20 rounded-full blur-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-500">
                            </div>

                            {{-- Container Logo (Padding diperkecil jadi p-3) --}}
                            <div
                                class="relative p-3 bg-white/10 rounded-2xl backdrop-blur-sm border border-white/20 shadow-xl shadow-green-900/20 transition-transform duration-300 hover:scale-105">
                                {{-- Logo Image (Ukuran diperkecil: w-20 di HP, w-28 di Laptop) --}}
                                <img src="{{ asset('images/logo.png') }}" alt="Logo Kalimantan Barat"
                                    class="w-20 h-auto md:w-28 drop-shadow-lg" />
                            </div>
                        </div>

                        {{-- KOLOM 2: TEKS --}}
                        <div class="max-w-2xl mx-auto">
                            {{-- Badge Kecil --}}
                            <span
                                class="inline-block py-1 px-3 rounded-full bg-green-500/30 border border-green-400/30 text-green-50 text-[10px] md:text-xs font-semibold mb-3 backdrop-blur-md">
                                PPID Utama Provinsi Kalimantan Barat
                            </span>

                            {{-- Judul (Font diperkecil: text-2xl di HP, text-4xl di Laptop) --}}
                            <h1 class="text-2xl md:text-4xl font-extrabold mb-3 tracking-tight leading-tight drop-shadow-sm">
                                Daftar Informasi Publik (DIP)
                            </h1>

                            {{-- Deskripsi (Font diperkecil) --}}
                            <p class="text-green-100 opacity-90 text-sm md:text-base leading-relaxed font-light mx-auto">
                                Temukan seluruh daftar informasi publik yang dikelola oleh Pemerintah Provinsi Kalimantan Barat
                                secara transparan, akuntabel, dan mudah diakses.
                            </p>
                        </div>

                    </div>
                </div>
            </div>

                {{-- MAIN CONTENT --}}
                <section class="py-10 bg-gray-50 min-h-screen">
                    <div class="container mx-auto px-6">

                        @include('content.daftar-informasi.tabel-informasi')

                        {{-- 4. DASHBOARD SECTION (DI BAWAH) --}}
                        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 mb-10">
                            {{-- Statistik --}}
                            <div class="lg:col-span-4 space-y-6">
                                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 h-full">
                                    <h3 class="font-bold text-gray-800 text-lg mb-6 border-b border-gray-100 pb-2"><i class="fa-solid fa-chart-pie mr-2 text-green-600"></i> Statistik Klasifikasi</h3>
                                    <div class="space-y-5 mb-8">
                                        @foreach($chartData['labels'] as $index => $label)
                                            @php $val = $chartData['series'][$index];
        $total = array_sum($chartData['series']);
        $percent = $total > 0 ? ($val / $total) * 100 : 0; @endphp
                                            <div>
                                                <div class="flex justify-between text-sm mb-1"><span class="font-medium text-gray-700">{{ $label }}</span><span class="font-bold text-green-700">{{ $val }}</span></div>
                                                <div class="w-full bg-gray-100 rounded-full h-2"><div class="bg-green-600 h-2 rounded-full transition-all duration-1000" style="width: {{ $percent }}%"></div></div>
                                            </div>
                                        @endforeach
                                    </div>
                                    <div id="klasifikasiChart" class="flex justify-center"></div>
                                </div>
                            </div>

                            {{-- Informasi Terbaru --}}
                            <div class="lg:col-span-8">
                                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 h-full">
                                    <div class="flex justify-between items-center mb-6 border-b border-gray-100 pb-2">
                                        <h3 class="font-bold text-gray-800 text-lg"><i class="fa-solid fa-bolt mr-2 text-orange-500"></i> Informasi Terbaru</h3>
                                    </div>
                                    <div class="space-y-0">
                                        @foreach($informasiTerbaru as $info)
                                            <div onclick="showDetail({{ $info->id }})" class="relative pl-8 py-4 border-l-2 border-green-100 last:pb-0 hover:bg-green-50/30 transition-colors rounded-r-lg group cursor-pointer">
                                                <div class="absolute -left-[9px] top-5 w-4 h-4 bg-white border-4 border-green-500 rounded-full group-hover:scale-110 transition-transform"></div>
                                                <div class="flex items-center gap-2 mb-1">
                                                    <span class="bg-green-100 text-green-800 text-[10px] font-bold px-2 py-0.5 rounded border border-green-200">{{ \Carbon\Carbon::parse($info->created_at)->format('Y') }}</span>
                                                    <span class="text-xs text-gray-400"><i class="fa-regular fa-clock mr-1"></i> {{ \Carbon\Carbon::parse($info->created_at)->translatedFormat('d F Y, H:i') }} WIB</span>
                                                </div>
                                                <h4 class="text-base font-bold text-gray-800 mb-1 group-hover:text-green-700 transition-colors line-clamp-1">{{ $info->judul_informasi }}</h4>
                                                <div class="flex flex-wrap gap-y-1 gap-x-4 text-xs text-gray-500">
                                                    <span class="flex items-center"><i class="fa-solid fa-building-columns mr-1.5 text-gray-400"></i> {{ Str::limit($info->pj_penerbit_informasi ?? 'Tidak diketahui', 40) }}</span>
                                                    @if($info->klasifikasiInformasi)
                                                        <span class="flex items-center"><i class="fa-solid fa-tag mr-1.5 text-gray-400"></i> {{ $info->klasifikasiInformasi->nama_klasifikasi }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                        

                    </div>
                </section>

                {{-- INCLUDE POPUP --}}
                @include('content.daftar-informasi.popup-informasi')

                {{-- SCRIPTS --}}
                <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
                <script>
                document.addEventListener('DOMContentLoaded', function () {
                    var options = {
                        series: @json($chartData['series']), labels: @json($chartData['labels']),
                        chart: { type: 'donut', height: 280, fontFamily: 'Inter, sans-serif' },
                        colors: ['#16a34a', '#0d9488', '#ea580c', '#2563eb'],
                        plotOptions: { pie: { donut: { size: '65%', labels: { show: true, total: { show: true, label: 'Total', color: '#374151', formatter: function (w) { return w.globals.seriesTotals.reduce((a, b) => { return a + b }, 0) } } } } } },
                        dataLabels: { enabled: false },
                        legend: { position: 'bottom', fontSize: '12px', markers: { radius: 12 } },
                        tooltip: { y: { formatter: function(value) { return value + " Dokumen" } } }
                    };
                    var chart = new ApexCharts(document.querySelector("#klasifikasiChart"), options);
                    chart.render();
                });

                // AJAX POPUP
                const modal = document.getElementById('infoModal');
                function closeModal() { modal.classList.add('hidden'); }
                function showDetail(id) {
                    modal.classList.remove('hidden');
                    document.getElementById('modal-judul').innerText = 'Memuat Data...';
                    // Reset fields
                    ['nomor', 'tanggal', 'jenis', 'klasifikasi', 'tipe', 'ukuran', 'penerbit'].forEach(id => document.getElementById('modal-' + id).innerText = '...');

                    fetch(`/detail-informasi/${id}`)
                        .then(response => response.json())
                        .then(data => {
                            document.getElementById('modal-judul').innerText = data.judul_informasi;
                            document.getElementById('modal-nomor').innerText = data.nomor_dokumen;
                            document.getElementById('modal-tanggal').innerText = data.tanggal_publikasi;
                            document.getElementById('modal-jenis').innerText = data.jenis_informasi;
                            document.getElementById('modal-klasifikasi').innerText = data.klasifikasi_informasi;
                            document.getElementById('modal-tipe').innerText = data.tipe_dokumen;
                            document.getElementById('modal-ukuran').innerText = data.ukuran_berkas;
                            document.getElementById('modal-penerbit').innerText = data.penerbit;
                            document.getElementById('modal-download-btn').href = data.file_url;

                            // GUNAKAN SLUG UNTUK URL ← PERUBAHAN DI SINI
                            const detailUrl = `/detail-informasi/baca/${data.slug}`;
                            document.getElementById('modal-detail-btn').href = detailUrl;

                        })
                        .catch(error => { console.error('Error:', error); document.getElementById('modal-judul').innerText = 'Gagal memuat data'; });
                }
            </script>
@endsection