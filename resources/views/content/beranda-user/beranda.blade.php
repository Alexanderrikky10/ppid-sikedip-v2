@extends('layout-app.app.app')

@section('content')

    {{-- =======================================================================
    BAGIAN 1: HERO SLIDER (Tema Hijau)
    ======================================================================= --}}
    <section x-data="{ 
                                                activeSlide: 0, 
                                                slides: [
                                                    'https://images.unsplash.com/photo-1550684848-fac1c5b4e853?q=80&w=2070&auto=format&fit=crop', 
                                                    'https://images.unsplash.com/photo-1451187580459-43490279c0fa?q=80&w=2072&auto=format&fit=crop', 
                                                    'https://images.unsplash.com/photo-1519681393798-3828fb4090bb?q=80&w=2070&auto=format&fit=crop'
                                                ],
                                                init() {
                                                    setInterval(() => {
                                                        this.activeSlide = (this.activeSlide + 1) % this.slides.length;
                                                    }, 5000);
                                                }
                                            }"
        class="relative w-full h-screen min-h-[600px] overflow-hidden flex items-center justify-center bg-gray-900">

        {{-- Background Slider Images --}}
        <template x-for="(slide, index) in slides" :key="index">
            <div x-show="activeSlide === index" x-transition:enter="transition transform duration-1000 ease-in-out"
                x-transition:enter-start="opacity-0 scale-105" x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="transition transform duration-1000 ease-in-out"
                x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-100"
                class="absolute inset-0 w-full h-full">
                <img :src="slide" alt="Background" class="w-full h-full object-cover">
                {{-- Overlay Gelap --}}
                <div class="absolute inset-0 bg-black/60"></div>
            </div>
        </template>

        {{-- Content Hero --}}
        <div class="relative z-10 w-full max-w-5xl px-4 text-center mt-16">
            <h1 class="text-4xl sm:text-5xl md:text-6xl font-bold text-white mb-6 drop-shadow-lg leading-tight">
                Temukan Informasi Publik <br>
                <span class="text-white">yang Anda Butuhkan</span>
            </h1>

            <p class="text-lg text-gray-200 mb-10 max-w-2xl mx-auto font-light">
                Akses data, dokumen, dan layanan informasi dari Pemerintah Provinsi Kalimantan Barat dengan mudah dan
                transparan.
            </p>

            {{-- Search Bar --}}
            <div class="relative max-w-2xl mx-auto group">
                <span class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none">
                    <i class="fas fa-search text-gray-400 text-lg"></i>
                </span>
                <input type="text"
                    class="block w-full pl-12 pr-6 py-4 rounded-full text-gray-900 bg-white/95 border-0 shadow-xl focus:ring-4 focus:ring-green-500/30 text-lg placeholder-gray-500 transition-all hover:bg-white"
                    placeholder="Ketik kata kunci pencarian informasi...">
            </div>
        </div>

        {{-- Scroll Down Indicator --}}
        <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 animate-bounce">
            <a href="#layanan-section" class="text-white/70 hover:text-white transition-colors">
                <i class="fas fa-chevron-down text-3xl"></i>
            </a>
        </div>
    </section>

    {{-- =======================================================================
    BAGIAN 2: LAYANAN INFORMASI & KARTU (Tema Hijau)
    ======================================================================= --}}
    <section id="layanan-section" class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- HEADER SECTION --}}
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-extrabold text-gray-900 mt-2 mb-4">
                    Layanan Informasi Publik
                </h2>
                <p class="text-gray-600 max-w-2xl mx-auto text-base">
                    Kemudahan akses layanan informasi dan dokumentasi publik secara transparan, akuntabel, dan efisien untuk
                    masyarakat.
                </p>
                {{-- Garis Dekorasi Kecil (Hijau) --}}
                <div class="w-20 h-1.5 bg-green-600 mx-auto mt-6 rounded-full"></div>
            </div>

            {{-- GRID KARTU --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-stretch">

                {{-- KARTU KIRI (Besar Hijau) --}}
                <div
                    class="relative overflow-hidden rounded-3xl bg-green-700 text-white shadow-2xl lg:col-span-1 min-h-[400px] flex flex-col justify-between p-8 sm:p-10 group">

                    {{-- Decorative Background --}}
                    <div class="absolute inset-0 opacity-20 pointer-events-none mix-blend-overlay"
                        style="background-image: url('https://www.transparenttextures.com/patterns/cubes.png'); background-size: cover;">
                    </div>
                    <div class="absolute -right-10 -bottom-10 text-white/10 transform rotate-12 scale-150">
                        <i class="fas fa-bullhorn text-[200px]"></i>
                    </div>

                    <div class="relative z-10">
                        {{-- Badge --}}
                        <div
                            class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/20 backdrop-blur-sm border border-white/30 text-xs font-medium mb-6">
                            <i class="fas fa-info-circle"></i> Informasi Publik
                        </div>

                        <h2 class="text-4xl sm:text-5xl font-extrabold leading-tight mb-6">
                            Daftar <br> Informasi <br> Publik
                        </h2>

                        <p class="text-green-100 text-base leading-relaxed mb-8">
                            Akses data dan informasi resmi dari OPD Pemerintah Provinsi Kalimantan Barat dan Pemerintah
                            Kabupaten/Kota Se-Kalimantan Barat.
                        </p>
                    </div>

                    <div class="relative z-10 mt-auto">
                        <a href="{{ route('daftar-informasi-publik') }}"
                            class="inline-flex items-center justify-center w-full px-6 py-4 text-base font-bold text-green-700 bg-white rounded-xl shadow-lg hover:bg-green-50 transition-all duration-300 group-hover:translate-y-[-2px]">
                            Lihat Selengkapnya <i class="fas fa-arrow-right ml-2"></i>
                        </a>
                    </div>
                </div>

                {{-- GRID KANAN (4 Kartu Kecil) --}}
                <div class="lg:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-6">

                    {{-- Kartu 1: Permohonan --}}
                    <div
                        class="bg-white rounded-3xl p-6 shadow-md border border-green-100 hover:shadow-xl hover:border-green-300 transition-all duration-300 flex flex-col h-full group">
                        <div class="flex items-start justify-between mb-4">
                            <div
                                class="w-12 h-12 bg-green-600 rounded-xl flex items-center justify-center text-white text-xl shadow-lg shadow-green-600/30 group-hover:scale-110 transition-transform">
                                <i class="far fa-file-alt"></i>
                            </div>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">Permohonan Informasi</h3>
                        {{-- Garis kecil hijau --}}
                        <div class="w-10 h-1 bg-green-600 rounded-full mb-3"></div>
                        <p class="text-gray-500 text-sm mb-6 flex-grow">
                            Dapatkan informasi yang Anda butuhkan dengan mengisi formulir permohonan secara online.
                        </p>
                        <a href="{{ route('permohonan-informasi') }}"
                            class="text-green-600 font-semibold text-sm flex items-center group-hover:text-green-800 transition-colors">
                            Ajukan Permohonan <i
                                class="fas fa-arrow-right ml-2 transform group-hover:translate-x-1 transition-transform"></i>
                        </a>
                    </div>

                    {{-- Kartu 2: Keberatan --}}
                    <div
                        class="bg-white rounded-3xl p-6 shadow-md border border-green-100 hover:shadow-xl hover:border-green-300 transition-all duration-300 flex flex-col h-full group">
                        <div class="flex items-start justify-between mb-4">
                            <div
                                class="w-12 h-12 bg-green-600 rounded-xl flex items-center justify-center text-white text-xl shadow-lg shadow-green-600/30 group-hover:scale-110 transition-transform">
                                <i class="fas fa-shield-alt"></i>
                            </div>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">Keberatan Informasi</h3>
                        <div class="w-10 h-1 bg-green-600 rounded-full mb-3"></div>
                        <p class="text-gray-500 text-sm mb-6 flex-grow">
                            Sampaikan keberatan Anda atas permohonan informasi yang tidak sesuai harapan.
                        </p>
                        <a href="{{ route('keberatan-informasi') }}"
                            class="text-green-600 font-semibold text-sm flex items-center group-hover:text-green-800 transition-colors">
                            Ajukan Keberatan <i
                                class="fas fa-arrow-right ml-2 transform group-hover:translate-x-1 transition-transform"></i>
                        </a>
                    </div>

                    {{-- Kartu 3: Tata Cara --}}
                    <div
                        class="bg-white rounded-3xl p-6 shadow-md border border-green-100 hover:shadow-xl hover:border-green-300 transition-all duration-300 flex flex-col h-full group">
                        <div class="flex items-start justify-between mb-4">
                            <div
                                class="w-12 h-12 bg-green-600 rounded-xl flex items-center justify-center text-white text-xl shadow-lg shadow-green-600/30 group-hover:scale-110 transition-transform">
                                <i class="fas fa-book-open"></i>
                            </div>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">Tata Cara Informasi</h3>
                        <div class="w-10 h-1 bg-green-600 rounded-full mb-3"></div>
                        <p class="text-gray-500 text-sm mb-6 flex-grow">
                            Pelajari panduan lengkap dan alur pengajuan permohonan informasi publik.
                        </p>
                        <a href="{{ route('tata-cara-layanan-informasi') }}"
                            class="text-green-600 font-semibold text-sm flex items-center group-hover:text-green-800 transition-colors">
                            Lihat Panduan <i
                                class="fas fa-arrow-right ml-2 transform group-hover:translate-x-1 transition-transform"></i>
                        </a>
                    </div>

                    {{-- Kartu 4: Survei --}}
                    <div
                        class="bg-white rounded-3xl p-6 shadow-md border border-green-100 hover:shadow-xl hover:border-green-300 transition-all duration-300 flex flex-col h-full group">
                        <div class="flex items-start justify-between mb-4">
                            <div
                                class="w-12 h-12 bg-green-600 rounded-xl flex items-center justify-center text-white text-xl shadow-lg shadow-green-600/30 group-hover:scale-110 transition-transform">
                                <i class="fas fa-poll-h"></i>
                            </div>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">Survei Layanan</h3>
                        <div class="w-10 h-1 bg-green-600 rounded-full mb-3"></div>
                        <p class="text-gray-500 text-sm mb-6 flex-grow">
                            Berikan penilaian dan masukan untuk peningkatan kualitas layanan kami.
                        </p>
                        <a href="#"
                            class="text-green-600 font-semibold text-sm flex items-center group-hover:text-green-800 transition-colors">
                            Isi Survei <i
                                class="fas fa-arrow-right ml-2 transform group-hover:translate-x-1 transition-transform"></i>
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </section>

    @include('layout-app.app.footer')

@endsection