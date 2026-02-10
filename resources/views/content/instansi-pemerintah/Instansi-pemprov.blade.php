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
                    Daftar Satuan Kerja Perangkat Daerah (SKPD) di lingkungan Pemerintah Provinsi Kalimantan Barat.
                </p>
            </div>
        </div>
    </div>

    {{-- 2. MAIN CONTENT (GRID OPD) --}}
    <section class="py-12 bg-gray-50 min-h-screen" x-data="{ search: '{{ request('search') }}' }">
        <div class="container mx-auto px-6">

            {{-- SEARCH BAR WRAPPER --}}
            <div class="max-w-xl mx-auto mb-10 relative">
                <form action="{{ url()->current() }}" method="GET">
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i
                                class="fa-solid fa-magnifying-glass text-gray-400 group-focus-within:text-green-500 transition-colors"></i>
                        </div>

                        {{-- INPUT SEARCH --}}
                        <input type="text" name="search" x-model="search" value="{{ request('search') }}"
                            placeholder="Cari nama dinas, badan, atau biro..."
                            class="w-full pl-11 pr-4 py-3.5 rounded-full border-gray-200 shadow-sm focus:border-green-500 focus:ring-green-500 transition-all text-sm placeholder-gray-400 outline-none">

                        {{-- TOMBOL RESET --}}
                        <button type="button" x-show="search.length > 0"
                            @click="search = ''; window.location.href='{{ url()->current() }}'"
                            class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-red-500 transition-colors cursor-pointer"
                            style="display: none;">
                            <i class="fa-solid fa-times"></i>
                        </button>
                    </div>
                </form>
            </div>

            {{-- GRID WRAPPER --}}
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-6">

                @forelse($perangkatDaerahPemprov as $opd)

                    {{-- CARD OPD --}}
                    <a href="{{ route('daftar-informasi-pemprov.list', ['slug' => $opd->slug]) }}"
                        class="group bg-white rounded-2xl p-6 shadow-sm hover:shadow-xl border border-gray-100 hover:border-green-400 transition-all duration-300 transform hover:-translate-y-1 flex flex-col items-center text-center h-full relative overflow-hidden"
                        x-show="search === '' || '{{ strtolower($opd->nama_perangkat_daerah) }}'.includes(search.toLowerCase())"
                        x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-90"
                        x-transition:enter-end="opacity-100 scale-100">

                        {{-- Decorative Hover Effect --}}
                        <div
                            class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-green-400 to-green-600 transform scale-x-0 group-hover:scale-x-100 transition-transform duration-300">
                        </div>

                        {{-- LOGO / ICON CONTAINER --}}
                        <div class="relative z-10 w-24 h-24 mb-5 flex items-center justify-center p-2">

                            {{-- Lingkaran Container --}}
                            <div
                                class="w-full h-full bg-white rounded-full shadow-sm border border-gray-100 flex items-center justify-center p-3 group-hover:shadow-md transition-shadow overflow-hidden">

                                <i
                                    class="fa-solid fa-building-columns text-4xl text-gray-300 group-hover:text-green-500 transition-colors"></i>

                            </div>
                        </div>

                        {{-- NAMA OPD --}}
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
                                Buka Informasi
                            </span>
                        </div>
                    </a>

                @empty
                    {{-- EMPTY STATE --}}
                    <div class="col-span-full text-center py-16">
                        <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-green-50 mb-4">
                            <i class="fa-regular fa-folder-open text-4xl text-green-400/80"></i>
                        </div>
                        <h3 class="text-lg font-bold text-gray-700">Data Tidak Ditemukan</h3>
                        <p class="text-gray-500 mt-1 text-sm">Belum ada data perangkat daerah yang tersedia saat ini.</p>
                    </div>
                @endforelse

            </div>

            {{-- PESAN PENCARIAN KOSONG (Client Side / AlpineJS) --}}
            <div class="hidden text-center py-16"
                :class="{ '!block': search !== '' && $el.previousElementSibling.querySelectorAll('a[style*=\'display: none\']').length === $el.previousElementSibling.children.length }">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 mb-4">
                    <i class="fa-solid fa-magnifying-glass text-gray-400 text-2xl"></i>
                </div>
                <p class="text-gray-600 font-medium">Tidak ditemukan instansi dengan kata kunci "<span x-text="search"
                        class="font-bold text-gray-900"></span>"</p>
                <button @click="search = ''; window.location.href='{{ url()->current() }}'"
                    class="mt-4 text-sm text-green-600 hover:text-green-800 font-bold hover:underline">
                    Reset Pencarian
                </button>
            </div>

        </div>
    </section>

@endsection