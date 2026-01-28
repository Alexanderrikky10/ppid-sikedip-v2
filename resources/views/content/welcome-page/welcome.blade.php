@extends('layout-app.layout-welcome-page.app')

@section('content')
    <div class="flex min-h-screen w-full">

        <div class="w-full lg:w-1/2 flex flex-col justify-center items-center bg-white p-4 sm:p-8 lg:p-16">

            <div
                class="bg-gray-100 bg-opacity-90 p-6 sm:p-8 w-full max-w-md sm:max-w-lg rounded-xl shadow-lg border border-gray-200">

                <div class="flex justify-center mb-6">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo Kalbar" class="h-20 w-auto object-contain">
                </div>

                <div class="text-center mb-6">
                    <h1 class="text-4xl sm:text-5xl font-extrabold text-slate-900 tracking-tight mb-4">
                        SIKEDIP
                    </h1>

                    <div class="space-y-1">
                        <h2 class="text-sm font-bold text-slate-600 tracking-wider uppercase">
                            SISTEM KELOLA DAFTAR INFORMASI PUBLIK
                        </h2>
                        <h2 class="text-sm font-bold text-slate-600 tracking-wider uppercase">
                            PEMERINTAH PROVINSI KALIMANTAN BARAT
                        </h2>
                    </div>
                </div>

                <p class="text-slate-500 mb-8 text-sm text-center leading-relaxed">
                    SIKEDIP merupakan sistem pengelolaan daftar informasi publik Provinsi Kalimantan Barat yang dirancang
                    untuk mendukung
                    keterbukaan informasi. Sistem ini memudahkan pengelompokan, penyimpanan, dan penyajian informasi publik
                    secara
                    terstruktur, akurat, serta mudah diakses oleh masyarakat dan perangkat daerah terkait.
                </p>

                <div class="flex flex-col sm:flex-row gap-3 w-full justify-center mb-8">
                    <a href="{{ route('beranda.index') }}"
                        class="px-6 py-3 bg-black text-white font-semibold rounded shadow hover:bg-gray-800 transition text-center text-sm sm:text-base w-full sm:w-auto">
                        Beranda
                    </a>

                    <a href="#"
                        class="px-6 py-3 bg-transparent border border-gray-400 text-gray-800 font-semibold rounded hover:bg-gray-200 transition text-center text-sm sm:text-base w-full sm:w-auto">
                        Login and Register
                    </a>
                </div>

                <div class="flex items-center justify-center space-x-6 mt-6">
                    <img src="{{ asset('images/logo-kominfo.png') }}" class="h-8 w-auto object-contain" alt="Kominfo">
                    <img src="{{ asset('images/opendata-logo.png') }}" class="h-8 w-auto object-contain" alt="Open Data">
                    <img src="{{ asset('images/jdih-logo.png') }}" class="h-8 w-auto object-contain" alt="Jdih Data">
                </div>

            </div>
        </div>

        <div class="hidden lg:flex w-1/2 flex-col justify-center items-center bg-[#111827] text-slate-400 p-10 relative">

            <div class="absolute inset-0 opacity-10"
                style="background-image: radial-gradient(#374151 1px, transparent 1px); background-size: 32px 32px;">
            </div>

            <div class="relative z-10 flex flex-col items-center text-center">
                <div class="mb-6">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-24 h-24 text-slate-600">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                    </svg>
                </div>

                <h3 class="text-xl font-medium text-slate-300 mb-2">Media atau konten belum diatur</h3>
                <p class="text-sm text-slate-500">Silakan upload gambar dan atur teks di halaman admin</p>
            </div>
        </div>

    </div>
@endsection