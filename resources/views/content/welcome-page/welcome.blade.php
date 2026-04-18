@extends('layout-welcome-page.app')

@section('content')



    <link rel="stylesheet" href="{{ asset('css/slider/slider-welcome.css')}}">

    <div class="flex min-h-screen w-full">

        {{-- =========================================
        PANEL KIRI: Branding & Navigasi
        ========================================= --}}
        <div class="w-full lg:w-1/2 flex flex-col justify-center items-center bg-white p-4 sm:p-8 lg:p-16 relative z-10">

            <div
                class="bg-gray-100 bg-opacity-90 p-6 sm:p-8 w-full max-w-md sm:max-w-lg rounded-xl shadow-lg border border-gray-200">

                {{-- Logo --}}
                <div class="flex justify-center mb-6">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-20 w-auto object-contain">
                </div>

                {{-- Judul --}}
                <div class="text-center mb-6">
                    <h1 class="text-4xl sm:text-5xl font-extrabold text-slate-900 tracking-tight mb-4">
                        {{ $welcomePage->title ?? 'SIKEDIP' }}
                    </h1>
                    <div class="space-y-1">
                        @foreach (explode("\n", $welcomePage->sub_title ?? 'SISTEM KELOLA DAFTAR INFORMASI PUBLIK') as $line)
                            @if (trim($line))
                                <h2 class="text-sm font-bold text-slate-600 tracking-wider uppercase">
                                    {{ trim($line) }}
                                </h2>
                            @endif
                        @endforeach
                    </div>
                </div>

                {{-- Deskripsi --}}
                <p class="text-slate-500 mb-8 text-sm text-center leading-relaxed">
                    {{ $welcomePage->description ?? 'SIKEDIP merupakan sistem pengelolaan daftar informasi publik Provinsi Kalimantan Barat.' }}
                </p>

                {{-- Tombol --}}
                <div class="flex flex-col sm:flex-row gap-3 w-full justify-center mb-8">
                    <a href="{{ route('beranda.index') }}"
                        class="px-6 py-3 bg-emerald-500 text-white font-semibold rounded shadow hover:bg-emerald-600 transition text-center text-sm sm:text-base w-full sm:w-auto">
                        Beranda
                    </a>
                    <a href="{{ route('login') }}"
                        class="px-6 py-3 bg-transparent border border-emerald-500 text-emerald-600 font-semibold rounded hover:bg-emerald-50 transition text-center text-sm sm:text-base w-full sm:w-auto">
                        Login and Register
                    </a>
                </div>

                {{-- Logo Partner --}}
                <div class="flex items-center justify-center space-x-6 mt-6">
                    <img src="{{ asset('images/logo-kominfo.png') }}" class="h-8 w-auto object-contain" alt="Kominfo">
                    <img src="{{ asset('images/opendata-logo.png') }}" class="h-8 w-auto object-contain" alt="Open Data">
                    <img src="{{ asset('images/jdih-logo.png') }}" class="h-8 w-auto object-contain" alt="Jdih">
                </div>

            </div>
        </div>

        {{-- =========================================
        PANEL KANAN: Slider Foto + Teks Overlay
        ========================================= --}}
        <div class="hidden lg:block w-1/2 relative overflow-hidden bg-gray-900">

            @php
                $imageUrls = $welcomePage?->media_urls ?? [];
                $slideTexts = $welcomePage?->slides_text ?? [];
                $hasContent = !empty($imageUrls);
                // Pairing: gambar ke-N dipasangkan dengan teks ke-N
                $totalSlides = count($imageUrls);
            @endphp

            @if ($hasContent)

                {{-- === LAYER 1: Background Foto Slider === --}}
                @foreach ($imageUrls as $index => $url)
                    <div class="bg-slide {{ $index === 0 ? 'active' : '' }}" style="background-image: url('{{ $url }}');">
                        {{-- Overlay gelap agar teks terbaca --}}
                        <div class="absolute inset-0 bg-black/55"></div>
                    </div>
                @endforeach

                {{-- === LAYER 2: Teks Overlay per Slide === --}}
                <div class="absolute inset-0 z-10">
                    @foreach ($imageUrls as $index => $url)
                        @php
                            $slideTitle = $slideTexts[$index]['title'] ?? ($welcomePage->title ?? 'SIKEDIP');
                            $slideText = $slideTexts[$index]['text'] ?? ($welcomePage->sub_title ?? '');
                        @endphp
                        <div class="text-slide {{ $index === 0 ? 'active' : '' }}">

                            {{-- Badge / nomor slide --}}
                            <div
                                class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-white/15 backdrop-blur-sm border border-white/25 text-white text-xs font-medium mb-6">
                                <span class="w-2 h-2 rounded-full bg-green-400 animate-pulse"></span>
                                Slide {{ $index + 1 }} / {{ $totalSlides }}
                            </div>

                            {{-- Garis dekorasi --}}
                            <div class="w-12 h-1 bg-green-400 rounded-full mb-6"></div>

                            {{-- Judul Slide --}}
                            <h3
                                class="text-4xl sm:text-5xl font-extrabold text-white text-center leading-tight drop-shadow-xl mb-5 px-4">
                                {{ $slideTitle }}
                            </h3>

                            {{-- Teks Slide --}}
                            <p class="text-white/80 text-center text-base max-w-sm mx-auto leading-relaxed drop-shadow px-4">
                                {{ $slideText }}
                            </p>

                        </div>
                    @endforeach

                    {{-- Progress Bar --}}
                    <div class="slide-progress" id="slide-progress"></div>

                    {{-- Dots Navigasi --}}
                    @if ($totalSlides > 1)
                        <div class="absolute bottom-8 left-1/2 -translate-x-1/2 slider-dots z-20">
                            @foreach ($imageUrls as $index => $url)
                                <button class="slider-dot {{ $index === 0 ? 'active' : '' }}" data-index="{{ $index }}"
                                    aria-label="Slide {{ $index + 1 }}">
                                </button>
                            @endforeach
                        </div>
                    @endif

                </div>

            @else
                {{-- Fallback --}}
                <div class="absolute inset-0 flex items-center justify-center">
                    <div class="absolute inset-0 opacity-10"
                        style="background-image: radial-gradient(#374151 1px, transparent 1px); background-size: 32px 32px;">
                    </div>
                    <div class="relative z-10 text-center text-slate-400 px-10">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-24 h-24 mx-auto text-slate-600 mb-4">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                        </svg>
                        <h3 class="text-xl font-medium text-slate-300 mb-2">Media belum diatur</h3>
                        <p class="text-sm text-slate-500">Silakan upload gambar di halaman admin</p>
                    </div>
                </div>
            @endif

        </div>{{-- end panel kanan --}}

    </div>

    {{-- =============================================
    JAVASCRIPT SLIDER
    ============================================= --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const bgSlides = document.querySelectorAll('.bg-slide');
            const textSlides = document.querySelectorAll('.text-slide');
            const dots = document.querySelectorAll('.slider-dot');
            const progress = document.getElementById('slide-progress');
            const total = bgSlides.length;
            const DELAY = 5000; // ms per slide

            if (total <= 1) {
                // Jika hanya 1 slide, pastikan tetap tampil
                if (bgSlides[0]) bgSlides[0].classList.add('active');
                if (textSlides[0]) textSlides[0].classList.add('active');
                return;
            }

            let current = 0;
            let timer = null;
            let progTimer = null;

            function goTo(index) {
                // Nonaktifkan semua
                bgSlides[current].classList.remove('active');
                textSlides[current]?.classList.remove('active');
                dots[current]?.classList.remove('active');

                current = (index + total) % total;

                // Aktifkan target
                bgSlides[current].classList.add('active');
                textSlides[current]?.classList.add('active');
                dots[current]?.classList.add('active');

                // Reset & jalankan progress bar
                startProgress();
            }

            function startProgress() {
                if (!progress) return;
                clearTimeout(progTimer);
                progress.style.transition = 'none';
                progress.style.width = '0%';
                // Trigger reflow
                progress.getBoundingClientRect();
                progress.style.transition = `width ${DELAY}ms linear`;
                progress.style.width = '100%';
            }

            function startTimer() {
                timer = setInterval(() => goTo(current + 1), DELAY);
            }

            function resetTimer() {
                clearInterval(timer);
                startTimer();
            }

            // Klik dot
            dots.forEach(function (dot) {
                dot.addEventListener('click', function () {
                    goTo(parseInt(this.dataset.index));
                    resetTimer();
                });
            });

            // Mulai
            startTimer();
            startProgress();
        });
    </script>

@endsection