@extends('layout-app.app.app')

@section('content')

@php
    use Illuminate\Support\Facades\Storage;

    $isSpecificOpd = isset($lockedOpd);
    $judulUtama = 'Daftar Informasi Publik (DIP)';
    $deskripsi = 'Temukan seluruh daftar informasi publik yang dikelola oleh Pemerintah Provinsi Kalimantan Barat secara transparan, akuntabel, dan mudah diakses.';
    $badgeText = 'PPID Utama Provinsi Kalimantan Barat';
    $logoUrl = asset('images/logo.png');

    if ($isSpecificOpd) {
        $judulUtama = $lockedOpd->nama_perangkat_daerah;
        $deskripsi = 'Daftar Informasi Publik yang dikelola secara resmi oleh ' . $lockedOpd->nama_perangkat_daerah . '.';
        $badgeText = 'PPID Pelaksana';

        if (!empty($lockedOpd->images)) {
            if (filter_var($lockedOpd->images, FILTER_VALIDATE_URL)) {
                $logoUrl = $lockedOpd->images;
            } else {
                try {
                    $logoUrl = Storage::disk('minio')->temporaryUrl($lockedOpd->images, now()->addMinutes(60));
                } catch (\Exception $e) {
                    $logoUrl = asset('images/logo.png');
                }
            }
        }
    }
@endphp

{{-- 1. HEADER --}}
<div class="bg-gradient-to-r from-green-900 to-green-800 pt-28 pb-12 text-white relative overflow-hidden">
    <div class="absolute inset-0 opacity-10" style="background-image: radial-gradient(#dcfce7 1px, transparent 1px); background-size: 24px 24px;"></div>
    <div class="container mx-auto px-6 relative z-10 text-center">
        <div class="relative group inline-block mb-5">
            <div class="absolute -inset-4 bg-green-400/20 rounded-full blur-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
            <div class="relative p-4 bg-white/10 rounded-2xl backdrop-blur-sm border border-white/20 w-28 h-28 md:w-32 md:h-32 flex items-center justify-center mx-auto transition-transform hover:scale-105">
                <img src="{{ $logoUrl }}" class="w-full h-full object-contain drop-shadow-lg" onerror="this.src='{{ asset('images/logo.png') }}'">
            </div>
        </div>
        <div class="max-w-3xl mx-auto">
            <span class="inline-block py-1 px-3 rounded-full bg-green-500/30 border border-green-400/30 text-green-50 text-[10px] md:text-xs font-semibold mb-3 uppercase tracking-wide">{{ $badgeText }}</span>
            <h1 class="text-2xl md:text-4xl font-extrabold mb-3 uppercase tracking-tight">{{ $judulUtama }}</h1>
            <p class="text-green-100 opacity-90 text-sm md:text-base font-light">{{ $deskripsi }}</p>
        </div>
    </div>
</div>

<section class="py-10 bg-gray-50 min-h-screen">
    <div class="container mx-auto px-6">
        
        {{-- 2. TABEL --}}
        @include('content.daftar-informasi.tabel-informasi')

        {{-- 3. DASHBOARD --}}
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 mb-16">
            {{-- Statistik --}}
            <div class="lg:col-span-4">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 h-full">
                    <h3 class="font-bold text-gray-800 text-lg mb-6 border-b pb-2"><i class="fa-solid fa-chart-pie mr-2 text-green-600"></i> Statistik</h3>
                    <div class="space-y-4 mb-8">
                        @foreach ($chartData['labels'] as $index => $label)
                            @php 
                                $val = $chartData['series'][$index]; 
                                $total = array_sum($chartData['series']);
                                $percent = $total > 0 ? ($val / $total) * 100 : 0;
                            @endphp
                            <div>
                                <div class="flex justify-between text-sm mb-1">
                                    <span class="text-gray-600">{{ $label }}</span>
                                    <span class="font-bold text-green-700">{{ $val }}</span>
                                </div>
                                <div class="w-full bg-gray-100 rounded-full h-1.5">
                                    <div class="bg-green-600 h-1.5 rounded-full" style="width: {{ $percent }}%"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div id="klasifikasiChart"></div>
                </div>
            </div>

            {{-- Info Terbaru --}}
            <div class="lg:col-span-8">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 h-full">
                    <h3 class="font-bold text-gray-800 text-lg mb-6 border-b pb-2"><i class="fa-solid fa-bolt mr-2 text-orange-500"></i> Terbaru</h3>
                    <div class="space-y-1">
                        @forelse($informasiTerbaru as $info)
                            <div onclick="showDetail({{ $info->id }})" class="relative pl-8 py-4 border-l-2 border-green-100 hover:bg-green-50 transition-colors cursor-pointer group rounded-r-lg">
                                <div class="absolute -left-[9px] top-6 w-4 h-4 bg-white border-4 border-green-500 rounded-full group-hover:scale-110 transition-transform"></div>
                                <div class="text-[10px] text-gray-400 mb-1">
                                    <span class="bg-green-100 text-green-800 font-bold px-2 py-0.5 rounded mr-2">{{ $info->tahun }}</span>
                                    <i class="fa-regular fa-clock mr-1"></i> {{ \Carbon\Carbon::parse($info->created_at)->translatedFormat('d F Y') }}
                                </div>
                                <h4 class="font-bold text-gray-800 group-hover:text-green-700 line-clamp-1">{{ $info->judul_informasi }}</h4>
                            </div>
                        @empty
                            <p class="text-center text-gray-400 py-10">Data tidak tersedia.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        {{-- 4. GRID INSTANSI (HANYA ICON) --}}
        @if(isset($perangkatDaerahPemprov))
            <div class="border-t border-gray-200 pt-10" x-data="{ searchOpd: '' }">
                <h2 class="text-2xl font-bold text-gray-800 mb-6 text-center">Jelajahi Instansi Lainnya</h2>
                
                <div class="max-w-md mx-auto mb-10 relative">
                    <i class="fa-solid fa-magnifying-glass absolute left-4 top-3.5 text-gray-400"></i>
                    <input type="text" x-model="searchOpd" placeholder="Cari nama instansi..." 
                           class="w-full pl-11 pr-4 py-3 rounded-full border border-gray-300 focus:ring-2 focus:ring-green-500 outline-none">
                </div>

                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-6">
                    @foreach($perangkatDaerahPemprov as $item)
                        <a href="{{ route('daftar-informasi-pemprov.list', ['slug' => $item->slug]) }}" 
                           x-show="searchOpd === '' || '{{ strtolower($item->nama_perangkat_daerah) }}'.includes(searchOpd.toLowerCase())"
                           class="group bg-white rounded-2xl p-5 shadow-sm border border-gray-100 hover:border-green-400 hover:shadow-md transition-all text-center flex flex-col items-center">
                            
                            {{-- Hanya Ikon --}}
                            <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mb-4 group-hover:bg-green-50 transition-colors">
                                <i class="fa-solid fa-building-columns text-2xl text-gray-400 group-hover:text-green-600 transition-colors"></i>
                            </div>

                            <h4 class="text-xs md:text-sm font-bold text-gray-700 group-hover:text-green-700 line-clamp-2 leading-snug">
                                {{ $item->nama_perangkat_daerah }}
                            </h4>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif

    </div>
</section>

@include('content.daftar-informasi.popup-informasi')

<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        @if(isset($chartData) && count($chartData['series']) > 0)
            var options = {
                series: @json($chartData['series']),
                labels: @json($chartData['labels']),
                chart: { type: 'donut', height: 280 },
                colors: ['#16a34a', '#0d9488', '#ea580c', '#2563eb', '#6366f1'],
                dataLabels: { enabled: false },
                legend: { position: 'bottom' }
            };
            new ApexCharts(document.querySelector("#klasifikasiChart"), options).render();
        @endif
    });

    function showDetail(id) {
        const modal = document.getElementById('infoModal');
        modal.classList.remove('hidden');
        document.getElementById('modal-judul').innerText = 'Memuat...';

        fetch(`/detail-informasi/${id}`)
            .then(res => res.json())
            .then(data => {
                document.getElementById('modal-judul').innerText = data.judul_informasi;
                document.getElementById('modal-nomor').innerText = data.nomor_dokumen || '-';
                document.getElementById('modal-tanggal').innerText = data.tanggal_publikasi;
                document.getElementById('modal-jenis').innerText = data.jenis_informasi;
                document.getElementById('modal-klasifikasi').innerText = data.klasifikasi_informasi;
                document.getElementById('modal-tipe').innerText = data.tipe_dokumen;
                document.getElementById('modal-ukuran').innerText = data.ukuran_berkas;
                document.getElementById('modal-penerbit').innerText = data.penerbit;
                document.getElementById('modal-download-btn').href = data.file_url;
                document.getElementById('modal-detail-btn').href = `/detail-informasi/baca/${data.slug}`;
            });
    }
    function closeModal() { document.getElementById('infoModal').classList.add('hidden'); }
</script>
@endsection