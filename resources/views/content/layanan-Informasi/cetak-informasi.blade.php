@extends('layout-app.app.app')

@section('content')
    {{-- PAGE HEADER --}}
    <div class="bg-gradient-to-r from-green-900 to-green-800 pt-32 pb-10 text-white relative overflow-hidden">
        <div class="absolute inset-0 opacity-10"
            style="background-image: radial-gradient(#dcfce7 1px, transparent 1px); background-size: 24px 24px;"></div>

        {{-- Perubahan di sini: menambahkan text-center --}}
        <div class="container mx-auto px-6 relative z-10 text-center">
            <h1 class="text-3xl font-bold mb-2">Laporan Daftar Informasi</h1>
            {{-- Menambahkan max-w-2xl dan mx-auto agar teks deskripsi tidak terlalu lebar dan tetap di tengah --}}
            <p class="text-green-100 opacity-90 text-sm max-w-2xl mx-auto">
                Filter dan cetak laporan daftar informasi publik sesuai kebutuhan Anda.
            </p>
        </div>
    </div>

    {{-- MAIN CONTENT --}}
    <section class="py-10 bg-gray-50 min-h-screen">
        <div class="container mx-auto px-6">

            {{-- Form Wrapper --}}
            <form action="#" method="POST"> {{-- Jangan lupa set route action --}}
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
                                            <select name="start_year"
                                                class="w-full pl-10 pr-4 py-2.5 rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500 shadow-sm transition-colors cursor-pointer bg-white">
                                                <option value="" disabled selected>Pilih Tahun...</option>
                                                @for ($i = date('Y'); $i >= 2015; $i--)
                                                    <option value="{{ $i }}">{{ $i }}</option>
                                                @endfor
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
                                            <select name="end_year"
                                                class="w-full pl-10 pr-4 py-2.5 rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500 shadow-sm transition-colors cursor-pointer bg-white">
                                                <option value="" disabled selected>Pilih Tahun...</option>
                                                @for ($i = date('Y'); $i >= 2015; $i--)
                                                    <option value="{{ $i }}">{{ $i }}</option>
                                                @endfor
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
                                        <select name="agency_id"
                                            class="w-full pl-10 pr-4 py-2.5 rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500 shadow-sm transition-colors cursor-pointer bg-white">
                                            <option value="" disabled selected>Semua Perangkat Daerah</option>
                                            <option value="1">Dinas Komunikasi dan Informatika</option>
                                            <option value="2">Dinas Kesehatan</option>
                                            <option value="3">Dinas Pendidikan</option>
                                            {{-- Tambahkan opsi lain sesuai database --}}
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
                                        <select name="category_id"
                                            class="w-full px-4 py-2.5 rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500 shadow-sm bg-white text-sm">
                                            <option value="">Semua Kategori</option>
                                            <option value="berkala">Informasi Berkala</option>
                                            <option value="serta-merta">Serta Merta</option>
                                            <option value="setiap-saat">Setiap Saat</option>
                                        </select>
                                    </div>

                                    {{-- Klasifikasi --}}
                                    <div>
                                        <label
                                            class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-2">Klasifikasi
                                            Informasi</label>
                                        <select name="classification_id"
                                            class="w-full px-4 py-2.5 rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500 shadow-sm bg-white text-sm">
                                            <option value="">Semua Klasifikasi</option>
                                            <option value="publik">Publik</option>
                                            <option value="dikecualikan">Dikecualikan</option>
                                        </select>
                                    </div>

                                    {{-- Jenis Informasi --}}
                                    <div>
                                        <label
                                            class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-2">Jenis
                                            Informasi</label>
                                        <select name="type_id"
                                            class="w-full px-4 py-2.5 rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500 shadow-sm bg-white text-sm">
                                            <option value="">Semua Jenis</option>
                                            <option value="dokumen">Dokumen</option>
                                            <option value="agenda">Agenda Kegiatan</option>
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
                                        <input type="text" name="print_location" placeholder="Cth: Pontianak"
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
                                        <input type="date" name="print_date" value="{{ date('Y-m-d') }}"
                                            class="w-full pl-10 pr-4 py-2.5 rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500 shadow-sm text-gray-600">
                                    </div>
                                </div>

                                <hr class="border-gray-100 my-2">

                                {{-- Action Buttons --}}
                                <div class="space-y-3 pt-2">
                                    <button type="submit"
                                        class="w-full flex items-center justify-center bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-4 rounded-lg shadow-md transition-all transform hover:scale-[1.02] duration-200 group">
                                        <i class="fa-solid fa-file-pdf mr-2 group-hover:animate-bounce"></i>
                                        Cetak Laporan
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