@extends('layout-app.app.app')

@section('content')

    {{-- 1. HERO HEADER --}}
    <div class="bg-gradient-to-r from-green-900 to-emerald-800 pt-32 pb-16 text-white relative overflow-hidden">
        <div class="absolute inset-0 opacity-10"
            style="background-image: radial-gradient(#fff 1px, transparent 1px); background-size: 24px 24px;"></div>

        <div class="container mx-auto px-6 relative z-10">
            <nav class="flex text-sm text-green-200 mb-4" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <a href="{{ route('beranda.index') }}" class="hover:text-white transition-colors">Beranda</a>
                    </li>
                    <li><i class="fa-solid fa-chevron-right text-xs"></i></li>
                    <li class="text-white font-semibold">Panduan Penyusunan DIP</li>
                </ol>
            </nav>

            <div class="max-w-4xl">
                <h1 class="text-3xl md:text-5xl font-extrabold leading-tight mb-4">
                    Pusat Panduan & Dokumen DIP
                </h1>
                <p class="text-green-100 text-lg max-w-2xl">
                    Akses materi instruksional dan pedoman resmi untuk penyusunan Daftar Informasi Publik di lingkungan
                    Pemerintah Provinsi Kalimantan Barat.
                </p>
            </div>
        </div>
    </div>

    {{-- 2. MAIN CONTENT --}}
    <section class="py-12 bg-gray-50 min-h-screen">
        <div class="container mx-auto px-6">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                {{-- KOLOM KIRI (UTAMA: Pratinjau PDF) --}}
                <div class="lg:col-span-2 space-y-6">
                    @if($utama)
                        <div class="bg-white rounded-2xl shadow-xl border border-gray-200 overflow-hidden">
                            <div class="p-5 border-b border-gray-100 flex justify-between items-center bg-white">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="w-10 h-10 bg-green-100 text-green-600 rounded-lg flex items-center justify-center">
                                        <i class="fa-solid fa-file-pdf text-xl"></i>
                                    </div>
                                    <div>
                                        <h3 class="font-bold text-gray-800">{{ $utama->judul }}</h3>
                                        <p class="text-xs text-gray-500 italic uppercase">Format: {{ $extension }}</p>
                                    </div>
                                </div>

                                <a href="{{ $fileUrl }}" target="_blank"
                                    class="text-sm bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg font-semibold flex items-center gap-2 transition">
                                    <i class="fa-solid fa-expand"></i> Layar Penuh
                                </a>
                            </div>

                            <div class="bg-gray-200 w-full h-[700px]">
                                @if(strtolower($extension) == 'pdf')
                                    <iframe src="{{ $fileUrl }}#toolbar=0" class="w-full h-full" frameborder="0"></iframe>
                                @else
                                    <div class="flex items-center justify-center h-full flex-col text-gray-500">
                                        <i class="fa-solid fa-file-circle-exclamation text-5xl mb-4"></i>
                                        <p>Pratinjau tidak tersedia untuk format ini.</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @else
                        <div class="bg-white rounded-2xl p-20 text-center border-2 border-dashed border-gray-300">
                            <i class="fa-solid fa-folder-open text-6xl text-gray-300 mb-4"></i>
                            <p class="text-gray-500">Belum ada dokumen panduan yang diunggah.</p>
                        </div>
                    @endif
                </div>

                {{-- KOLOM KANAN (SIDEBAR: Daftar File Lainnya) --}}
                <div class="lg:col-span-1 space-y-6">
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                        <h3 class="font-bold text-gray-800 mb-6 flex items-center gap-2">
                            <i class="fa-solid fa-list-ul text-green-600"></i> Daftar Panduan
                        </h3>

                        <div class="space-y-3">
                            @foreach($panduan as $item)
                                <div
                                    class="group relative p-4 rounded-xl border {{ $utama->id == $item->id ? 'border-green-500 bg-green-50' : 'border-gray-100 hover:border-green-300 hover:bg-gray-50' }} transition-all">
                                    <div class="flex items-start gap-4">
                                        <div
                                            class="flex-shrink-0 w-10 h-10 rounded-full bg-white border flex items-center justify-center shadow-sm">
                                            <i
                                                class="fa-solid fa-file-pdf {{ $utama->id == $item->id ? 'text-green-600' : 'text-red-500' }}"></i>
                                        </div>
                                        <div class="flex-1">
                                            <h4
                                                class="text-sm font-bold text-gray-800 group-hover:text-green-700 leading-tight mb-1">
                                                {{ $item->judul }}
                                            </h4>
                                            <p class="text-xs text-gray-400">Diperbarui:
                                                {{ $item->updated_at->format('d M Y') }}
                                            </p>

                                            <div class="mt-3 flex gap-2">
                                                <a href="{{ Storage::disk('minio')->temporaryUrl($item->file_path, now()->addMinutes(30)) }}"
                                                    target="_blank"
                                                    class="text-[10px] font-bold uppercase tracking-wider text-green-600 hover:underline">
                                                    Lihat Cepat
                                                </a>
                                                <span class="text-gray-300">|</span>
                                                <a href="{{ Storage::disk('minio')->temporaryUrl($item->file_path, now()->addMinutes(30)) }}"
                                                    download
                                                    class="text-[10px] font-bold uppercase tracking-wider text-emerald-600 hover:underline">
                                                    Download
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Tips Card --}}
                    <div class="bg-amber-50 border border-amber-200 rounded-2xl p-6">
                        <div class="flex gap-4">
                            <i class="fa-solid fa-lightbulb text-amber-500 text-xl mt-1"></i>
                            <div>
                                <h4 class="font-bold text-amber-900 text-sm mb-1">Butuh Bantuan?</h4>
                                <p class="text-xs text-amber-800 leading-relaxed">
                                    Jika Anda mengalami kendala dalam mengunduh dokumen, silakan hubungi tim Admin IT
                                    Kominfo melalui kanal bantuan SIKEDIP.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    @include('layout-app.app.footer')
@endsection