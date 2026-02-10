@extends('layout-app.app.app')

@section('content')

    {{-- DEFINISI URL FILE (Agar tidak ngetik ulang) --}}
    @php
        // Membuat URL sementara yang valid selama 30 menit
        $fileUrl = \Illuminate\Support\Facades\Storage::disk('minio')->temporaryUrl(
            $info->media,
            now()->addMinutes(30)
        );
    @endphp

    {{-- 1. HERO HEADER --}}
    <div class="bg-gradient-to-r from-green-900 to-green-800 pt-32 pb-16 text-white relative overflow-hidden">
        <div class="absolute inset-0 opacity-10"
            style="background-image: radial-gradient(#dcfce7 1px, transparent 1px); background-size: 24px 24px;"></div>

        <div class="container mx-auto px-6 relative z-10">
            {{-- Breadcrumb --}}
            <nav class="flex text-sm text-green-200 mb-4" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <a href="{{ route('beranda.index') }}" class="hover:text-white transition-colors">Beranda</a>
                    </li>
                    <li><i class="fa-solid fa-chevron-right text-xs"></i></li>
                    <li>
                        <a href="{{ route('daftar-informasi-publik') }}" class="hover:text-white transition-colors">Daftar
                            Informasi</a>
                    </li>
                    <li><i class="fa-solid fa-chevron-right text-xs"></i></li>
                    <li aria-current="page" class="text-white font-semibold truncate max-w-[200px] md:max-w-md">Detail
                        Informasi</li>
                </ol>
            </nav>

            <div class="flex flex-col md:flex-row md:items-start justify-between gap-6">
                <div class="max-w-4xl">
                    {{-- Badges --}}
                    <div class="flex flex-wrap gap-2 mb-4">
                        <span
                            class="bg-green-700/50 border border-green-500 text-green-50 text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wide">
                            {{ $info->klasifikasiInformasi->nama_klasifikasi ?? 'Umum' }}
                        </span>
                        <span
                            class="bg-blue-600/50 border border-blue-400 text-blue-50 text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wide">
                            Tahun {{ $info->tahun }}
                        </span>
                    </div>

                    <h1 class="text-2xl md:text-4xl font-extrabold leading-tight mb-4">
                        {{ $info->judul_informasi }}
                    </h1>

                    <div class="flex flex-wrap items-center gap-6 text-sm text-green-100/80">
                        <div class="flex items-center gap-2">
                            <i class="fa-regular fa-calendar"></i>
                            {{ \Carbon\Carbon::parse($info->created_at)->translatedFormat('d F Y') }}
                        </div>
                        <div class="flex items-center gap-2">
                            <i class="fa-solid fa-eye"></i>
                            {{ $info->views_count }} Dilihat
                        </div>
                        <div class="flex items-center gap-2">
                            <i class="fa-solid fa-download"></i>
                            {{ $info->downloads_count ?? 0 }} Diunduh
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- 2. MAIN CONTENT --}}
    <section class="py-12 bg-gray-50 min-h-screen">
        <div class="container mx-auto px-6">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                {{-- KOLOM KIRI (UTAMA: Preview & Deskripsi) --}}
                <div class="lg:col-span-2 space-y-8">

                    {{-- Card Preview File --}}
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                        <div class="p-4 border-b border-gray-100 flex justify-between items-center bg-gray-50">
                            <h3 class="font-bold text-gray-800 flex items-center gap-2">
                                <i class="fa-regular fa-file-lines text-green-600"></i> Pratinjau Dokumen
                            </h3>

                            {{-- UBAH 1: Link Layar Penuh ke URL MinIO --}}
                            <a href="{{ $fileUrl }}" target="_blank"
                                class="text-sm text-green-600 hover:text-green-800 font-semibold flex items-center gap-1">
                                <i class="fa-solid fa-expand"></i> Layar Penuh
                            </a>
                        </div>

                        <div class="bg-gray-100 w-full h-[500px] flex items-center justify-center">

                            {{-- UBAH 2: Source Iframe/Image ke URL MinIO --}}
                            @if(in_array(strtolower($extension), ['pdf']))
                                <iframe src="{{ $fileUrl }}" class="w-full h-full" frameborder="0"></iframe>
                            @elseif(in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'webp']))
                                <img src="{{ $fileUrl }}" class="max-w-full max-h-full object-contain" alt="Preview">
                            @else
                                <div class="text-center p-10">
                                    <div
                                        class="w-20 h-20 bg-gray-200 rounded-full flex items-center justify-center mx-auto mb-4 text-gray-400 text-3xl">
                                        <i class="fa-solid fa-file-zipper"></i>
                                    </div>
                                    <p class="text-gray-600 font-medium">Pratinjau tidak tersedia untuk format ini.</p>
                                    <p class="text-sm text-gray-500">Silakan unduh file untuk melihat isinya.</p>

                                    {{-- Tombol Download Kecil --}}
                                    <a href="{{ route('informasi.download', $info->slug) }}"
                                        class="mt-4 inline-block px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                                        Unduh File
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Card Ringkasan & Penjelasan --}}
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 md:p-8">
                        <h3 class="text-xl font-bold text-gray-800 mb-4">Ringkasan Informasi</h3>
                        <p class="text-gray-600 leading-relaxed mb-8">
                            {{ $info->ringkasan }}
                        </p>

                        @if($info->penjelasan)
                            <h3 class="text-xl font-bold text-gray-800 mb-4">Penjelasan Detail</h3>
                            <div class="prose prose-green max-w-none text-gray-600 leading-relaxed">
                                {!! nl2br(e($info->penjelasan)) !!}
                            </div>
                        @endif
                    </div>

                </div>

                {{-- KOLOM KANAN (SIDEBAR: Metadata & Related) --}}
                <div class="lg:col-span-1 space-y-8">

                    {{-- Card Detail Metadata --}}
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                        <h3 class="font-bold text-gray-800 mb-6 pb-2 border-b border-gray-100">Detail Dokumen</h3>

                        <div class="space-y-4">
                            <div>
                                <p class="text-xs text-gray-400 uppercase font-bold tracking-wider mb-1">Perangkat Daerah
                                </p>
                                <div class="flex items-start gap-3">
                                    <div
                                        class="mt-1 w-8 h-8 rounded bg-green-50 flex items-center justify-center text-green-600">
                                        <i class="fa-solid fa-building-columns text-sm"></i>
                                    </div>
                                    <p class="text-sm font-semibold text-gray-700 leading-snug">
                                        {{ $info->perangkatDaerah->nama_perangkat_daerah ?? $info->pj_penerbit_informasi }}
                                    </p>
                                </div>
                            </div>

                            <div>
                                <p class="text-xs text-gray-400 uppercase font-bold tracking-wider mb-1">Pejabat Penanggung
                                    Jawab</p>
                                <div class="flex items-start gap-3">
                                    <div
                                        class="mt-1 w-8 h-8 rounded bg-blue-50 flex items-center justify-center text-blue-600">
                                        <i class="fa-solid fa-user-tie text-sm"></i>
                                    </div>
                                    <p class="text-sm font-semibold text-gray-700">
                                        {{ $info->pejabat_pj ?? '-' }}
                                    </p>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <p class="text-xs text-gray-400 uppercase font-bold tracking-wider mb-1">Format</p>
                                    <span
                                        class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-bold bg-gray-100 text-gray-700 uppercase">
                                        {{ $extension }}
                                    </span>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-400 uppercase font-bold tracking-wider mb-1">Ukuran</p>
                                    <span class="text-sm font-semibold text-gray-700">{{ $fileSize }}</span>
                                </div>
                            </div>

                            <div>
                                <p class="text-xs text-gray-400 uppercase font-bold tracking-wider mb-1">Waktu Penyimpanan
                                </p>
                                <p class="text-sm text-gray-600 flex items-center gap-2">
                                    <i class="fa-solid fa-clock-rotate-left text-orange-500"></i>
                                    {{ $info->waktu_penyimpanan }}
                                </p>
                            </div>
                        </div>

                        <div class="mt-8 pt-6 border-t border-gray-100">
                            {{-- UBAH 3: Tombol Download Menggunakan Route Controller --}}
                            {{-- 'download' attribute dihilangkan karena controller sudah mengirim header download --}}
                            <a href="{{ route('informasi.download', $info->slug) }}"
                                class="block w-full py-3 bg-green-600 hover:bg-green-700 text-white font-bold text-center rounded-xl shadow-lg shadow-green-200 transition-all transform hover:-translate-y-1">
                                <i class="fa-solid fa-download mr-2"></i> Download File
                            </a>
                        </div>
                    </div>

                    {{-- Card Informasi Terkait --}}
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                        <h3 class="font-bold text-gray-800 mb-4 flex items-center gap-2">
                            <i class="fa-solid fa-link text-blue-500"></i> Informasi Terkait
                        </h3>

                        <div class="space-y-4">
                            @forelse($relatedInfos as $related)
                                {{-- Pastikan route ini sesuai dengan route detail Anda (misal: informasi.detail) --}}
                                <a href="{{ route('daftar-informasi.read', $related->slug) }}" class="block group">
                                    <div class="flex gap-3">
                                        <div class="flex-shrink-0 mt-1">
                                            <div
                                                class="w-2 h-2 bg-gray-300 rounded-full group-hover:bg-green-500 transition-colors">
                                            </div>
                                        </div>
                                        <div>
                                            <h4
                                                class="text-sm font-medium text-gray-700 group-hover:text-green-700 transition-colors line-clamp-2">
                                                {{ $related->judul_informasi }}
                                            </h4>
                                            <span class="text-xs text-gray-400 mt-1 block">
                                                {{ \Carbon\Carbon::parse($related->created_at)->translatedFormat('d M Y') }}
                                            </span>
                                        </div>
                                    </div>
                                </a>
                            @empty
                                <p class="text-sm text-gray-400 italic">Tidak ada informasi terkait lainnya.</p>
                            @endforelse
                        </div>
                    </div>

                    {{-- Tombol Kembali --}}
                    <a href="{{ route('daftar-informasi-publik') }}"
                        class="block w-full py-3 border-2 border-gray-200 text-gray-600 font-bold text-center rounded-xl hover:bg-gray-50 hover:border-gray-300 transition-all">
                        <i class="fa-solid fa-arrow-left mr-2"></i> Kembali ke Daftar
                    </a>

                </div>
            </div>
        </div>
    </section>

@endsection