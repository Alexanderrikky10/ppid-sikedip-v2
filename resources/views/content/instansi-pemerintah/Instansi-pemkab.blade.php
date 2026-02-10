@extends('layout-app.app.app')

@section('content')

    {{-- 1. PAGE HEADER --}}
    <div class="bg-gradient-to-r from-green-900 to-green-800 pt-32 pb-12 text-white relative overflow-hidden">
        {{-- Pattern Background --}}
        <div class="absolute inset-0 opacity-10"
            style="background-image: radial-gradient(#dcfce7 1px, transparent 1px); background-size: 24px 24px;"></div>

        <div class="container mx-auto px-6 relative z-10">
            <div class="flex flex-col items-center justify-center text-center">

                {{-- LOGO HEADER (Logo Kalbar Lokal) --}}
                <div
                    class="relative p-3 bg-white/10 rounded-2xl backdrop-blur-sm border border-white/20 shadow-xl mb-6 hover:scale-105 transition-transform duration-300">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo Kalimantan Barat" class="w-20 h-auto drop-shadow-lg"
                        onerror="this.style.display='none'">
                </div>

                <h1 class="text-3xl md:text-4xl font-bold mb-3 drop-shadow-md">Perangkat Daerah</h1>
                <p class="text-green-100 opacity-90 text-sm md:text-base max-w-2xl mx-auto font-light leading-relaxed">
                    Daftar Pemerintah Daerah Tingkat II (Kabupaten/Kota) di wilayah Provinsi Kalimantan Barat.
                </p>
            </div>
        </div>
    </div>

    {{-- 2. MAIN CONTENT --}}
    <section class="py-12 bg-gray-50 min-h-screen" x-data="{ search: '{{ request('search') }}', activeTab: 'utama' }">

        <div class="container mx-auto px-6">

            {{-- A. SEARCH BAR (DI ATAS) --}}
            <div class="max-w-xl mx-auto mb-6 relative group z-20">
                <form action="{{ url()->current() }}" method="GET">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <i
                            class="fa-solid fa-magnifying-glass text-gray-400 group-focus-within:text-green-500 transition-colors"></i>
                    </div>
                    <input type="text" name="search" x-model="search" value="{{ request('search') }}"
                        placeholder="Cari nama kabupaten atau kota..."
                        class="w-full pl-11 pr-4 py-3.5 rounded-full border-gray-200 shadow-sm focus:border-green-500 focus:ring-green-500 transition-all text-sm placeholder-gray-400 outline-none">

                    {{-- Tombol Reset --}}
                    <button type="button" x-show="search.length > 0"
                        @click="search = ''; window.location.href='{{ url()->current() }}'"
                        class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-red-500 cursor-pointer"
                        style="display: none;">
                        <i class="fa-solid fa-times"></i>
                    </button>
                </form>
            </div>

            {{-- B. TABS FILTER (DI BAWAH SEARCH) --}}
            <div class="flex justify-center mb-10">
                <div class="bg-white p-1.5 rounded-full shadow-sm border border-gray-200 inline-flex">
                    {{-- Tab Utama (Induk) --}}
                    <button @click="activeTab = 'utama'"
                        :class="activeTab === 'utama' ? 'bg-green-600 text-white shadow-md' : 'text-gray-500 hover:bg-gray-50'"
                        class="px-6 py-2 rounded-full text-sm font-semibold transition-all duration-300">
                        Utama (Induk)
                    </button>

                    {{-- Tab Semua --}}
                    <button @click="activeTab = 'all'"
                        :class="activeTab === 'all' ? 'bg-green-600 text-white shadow-md' : 'text-gray-500 hover:bg-gray-50'"
                        class="px-6 py-2 rounded-full text-sm font-semibold transition-all duration-300">
                        Tampilkan Semua
                    </button>
                </div>
            </div>

            {{-- GRID WRAPPER --}}
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-6">

                @forelse($PemkabUtama as $opd)
                    {{-- SIAPKAN DATA UNTUK ALPINE & PHP LOGIC GAMBAR --}}
                    @php
                        // 1. Logic Filter Tab
                        $isUtama = is_null($opd->parent_id) ? 'true' : 'false';

                        // 2. Logic Gambar MinIO
                        $logoUrl = asset('images/logo.png'); // Default Fallback
                        if (!empty($opd->images)) {
                            try {
                                // Generate URL MinIO (valid 60 menit)
                                $logoUrl = \Illuminate\Support\Facades\Storage::disk('minio')->temporaryUrl(
                                    $opd->images,
                                    now()->addMinutes(60)
                                );
                            } catch (\Exception $e) {
                                // Fallback jika gagal generate URL
                                $logoUrl = asset('images/logo.png');
                            }
                        }
                    @endphp

                    {{-- CARD OPD --}}
                    <a href="{{ route('daftar-informasi-pemkab.list', ['slug' => $opd->slug]) }}"
                        class="group bg-white rounded-2xl p-6 shadow-sm hover:shadow-xl border border-gray-100 hover:border-green-400 transition-all duration-300 transform hover:-translate-y-1 flex flex-col items-center text-center h-full relative overflow-hidden"
                        {{-- Logic Filter AlpineJS --}}
                        x-show="(search === '' || '{{ strtolower($opd->nama_perangkat_daerah) }}'.includes(search.toLowerCase())) && (activeTab === 'all' || (activeTab === 'utama' && {{ $isUtama }}))"
                        x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-90"
                        x-transition:enter-end="opacity-100 scale-100">

                        <div
                            class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-green-400 to-green-600 transform scale-x-0 group-hover:scale-x-100 transition-transform duration-300">
                        </div>

                        {{-- LOGO CONTAINER (GAMBAR DIKEMBALIKAN) --}}
                        <div class="relative z-10 w-24 h-24 mb-5 flex items-center justify-center p-2">
                            <div
                                class="w-full h-full bg-white rounded-full shadow-sm border border-gray-100 flex items-center justify-center p-3 group-hover:shadow-md transition-shadow">

                                {{-- Menampilkan Gambar --}}
                                <img src="{{ $logoUrl }}" alt="Logo {{ $opd->nama_perangkat_daerah }}"
                                    class="w-full h-full object-contain transform group-hover:scale-110 transition-transform duration-300"
                                    {{-- Jika gambar rusak, kembali ke default logo --}}
                                    onerror="this.onerror=null; this.src='{{ asset('images/logo.png') }}';">
                            </div>
                        </div>

                        {{-- NAMA PEMKAB/PEMKOT --}}
                        <div class="relative z-10 flex-grow flex items-center justify-center w-full">
                            <h3
                                class="text-sm font-bold text-gray-700 group-hover:text-green-700 transition-colors leading-snug line-clamp-3">
                                {{ $opd->nama_perangkat_daerah }}
                            </h3>
                        </div>

                        {{-- BUTTON VIEW --}}
                        <div
                            class="mt-4 opacity-0 group-hover:opacity-100 transition-all duration-300 transform translate-y-2 group-hover:translate-y-0">
                            <span
                                class="text-xs font-semibold text-green-600 bg-green-50 px-3 py-1 rounded-full border border-green-200">
                                Buka informasi
                            </span>
                        </div>
                    </a>

                @empty
                    {{-- EMPTY STATE (Server Side) --}}
                    <div class="col-span-full text-center py-16">
                        <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-green-50 mb-4">
                            <img src="{{ asset('images/logo.png') }}" class="w-12 h-auto drop-shadow-sm" alt="Empty">
                        </div>
                        <h3 class="text-lg font-bold text-gray-700">Data Tidak Ditemukan</h3>
                        <p class="text-gray-500 mt-1 text-sm">Belum ada data Pemerintah Kabupaten/Kota yang tersedia.</p>
                    </div>
                @endforelse

            </div>

            {{-- PESAN PENCARIAN KOSONG (Client Side) --}}
            <div class="hidden text-center py-16" :class="{ '!block': 
                                    (search !== '' || activeTab !== '') && 
                                    $el.previousElementSibling.querySelectorAll('a[style*=\'display: none\']').length === $el.previousElementSibling.children.length 
                                }">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 mb-4">
                    <i class="fa-solid fa-filter text-gray-400 text-2xl"></i>
                </div>
                <p class="text-gray-600 font-medium">Tidak ditemukan data yang sesuai dengan filter/pencarian.</p>
                <div class="mt-4 flex justify-center gap-3">
                    <button @click="activeTab = 'all'"
                        class="text-sm text-green-600 hover:underline font-semibold">Tampilkan Semua</button>
                    <span class="text-gray-300">|</span>
                    <button @click="search = ''; window.location.href='{{ url()->current() }}'"
                        class="text-sm text-green-600 hover:underline font-semibold">Reset Pencarian</button>
                </div>
            </div>

        </div>
    </section>

@endsection