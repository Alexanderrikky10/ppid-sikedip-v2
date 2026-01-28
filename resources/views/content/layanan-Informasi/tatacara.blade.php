@extends('layout-app.app.app')

@section('content')
    {{-- 1. HERO SECTION: Judul & Definisi (SPACING DIPERBAIKI) --}}
    {{-- Perubahan: 'py-20' diubah menjadi 'pt-32 pb-20' agar konten turun dan tidak mepet navbar --}}
    <section class="relative bg-gradient-to-r from-green-900 to-green-800 pt-32 pb-20 overflow-hidden text-white">
        {{-- Background Pattern --}}
        <div class="absolute inset-0 opacity-10"
            style="background-image: radial-gradient(#dcfce7 1px, transparent 1px); background-size: 30px 30px;"></div>

        <div class="container relative mx-auto px-6 text-center z-10">
            <span
                class="inline-block py-1 px-3 rounded-full bg-green-700/50 border border-green-500 text-green-100 text-xs font-semibold tracking-wider mb-4">
                UU KETERBUKAAN INFORMASI PUBLIK
            </span>
            <h1 class="text-3xl md:text-5xl font-extrabold tracking-tight mb-6">
                Mekanisme Permohonan <span class="text-emerald-400">Informasi Publik</span>
            </h1>
            <p class="text-green-100 text-lg max-w-3xl mx-auto leading-relaxed font-light opacity-90">
                "Pemohon Informasi Publik adalah warga negara dan/atau badan hukum Indonesia yang mengajukan permintaan
                informasi publik sebagaimana diatur dalam Undang-Undang."
            </p>
        </div>
    </section>

    {{-- 2. SECTION MEKANISME (Timeline Vertical - FIXED SPACING & GREEN THEME) --}}
    <section class="py-16 bg-white">
        <div class="container mx-auto px-6 max-w-6xl">
            <div class="text-center mb-16">
                <h2 class="text-3xl font-bold text-gray-900">Alur & Proses Permohonan</h2>
                <div class="h-1 w-20 bg-green-600 mx-auto mt-4 rounded-full"></div>
            </div>

            <div class="relative">
                {{-- Vertical Line --}}
                <div class="absolute left-8 md:left-1/2 h-full w-0.5 bg-gray-200 -translate-x-1/2"></div>

                {{-- Step 1: Pengajuan & Persyaratan (KIRI) --}}
                <div class="relative flex flex-col md:flex-row items-start mb-16">
                    {{-- Teks (Kiri) - Menggunakan pr-16 agar tidak mepet garis tengah --}}
                    <div class="md:w-1/2 md:text-right order-2 md:order-1 pt-2 md:pr-16 pl-20 md:pl-0">
                        <h3 class="text-xl font-bold text-green-800">1. Pengajuan & Kelengkapan Dokumen</h3>
                        <p class="text-gray-600 mt-2 text-sm leading-relaxed">
                            Pemohon datang ke <strong>desk layanan informasi</strong> mengisi formulir permintaan.
                        </p>

                        {{-- Detail Persyaratan --}}
                        <div
                            class="mt-4 inline-block bg-green-50 border border-green-100 p-5 rounded-xl text-left shadow-sm">
                            <strong class="text-green-800 text-sm block mb-3 border-b border-green-200 pb-2">
                                <i class="fa-solid fa-list-check mr-1"></i> Dokumen Wajib Dilampirkan:
                            </strong>
                            <ul class="text-sm text-gray-700 space-y-3 list-disc pl-4">
                                <li><strong>Perorangan:</strong> Fotocopy KTP Pemohon & Pengguna Informasi.</li>
                                <li><strong>Lembaga/Ormas:</strong>
                                    <ul
                                        class="list-circle pl-4 mt-2 space-y-1 text-xs text-gray-600 bg-white p-2 rounded border border-green-100">
                                        <li>Fotocopy Akta Pendirian.</li>
                                        <li>Surat Keterangan Terdaftar di <strong>Badan Kesbangpol Prov. Kalbar</strong>.
                                        </li>
                                        <li>Surat Keterangan Domisili Lembaga/Ormas.</li>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                    </div>

                    {{-- Icon (Tengah) --}}
                    <div
                        class="absolute left-8 md:left-1/2 -translate-x-1/2 w-14 h-14 bg-green-600 rounded-full border-4 border-white shadow-lg flex items-center justify-center text-white z-10 order-1 md:order-2">
                        <i class="fa-solid fa-file-pen text-lg"></i>
                    </div>

                    {{-- Spacer (Kanan) --}}
                    <div class="md:w-1/2 order-3 md:order-3"></div>
                </div>

                {{-- Step 2: Verifikasi Tujuan (KANAN) --}}
                <div class="relative flex flex-col md:flex-row items-center mb-16">
                    <div class="md:w-1/2 order-3 md:order-1"></div>

                    {{-- Icon (Tengah) --}}
                    <div
                        class="absolute left-8 md:left-1/2 -translate-x-1/2 w-14 h-14 bg-emerald-500 rounded-full border-4 border-white shadow-lg flex items-center justify-center text-white z-10 order-1 md:order-2">
                        <i class="fa-solid fa-clipboard-check text-lg"></i>
                    </div>

                    {{-- Teks (Kanan) - Menggunakan pl-16 agar tidak mepet garis tengah --}}
                    <div class="md:w-1/2 md:text-left order-2 md:order-3 pt-2 md:pl-16 pl-20 md:pr-0">
                        <h3 class="text-xl font-bold text-green-800">2. Verifikasi & Tanda Bukti</h3>
                        <p class="text-gray-600 mt-2 text-sm leading-relaxed">
                            Maksud dan tujuan permintaan informasi <strong>harus jelas penggunaannya</strong>. Petugas akan
                            memproses dan memberikan <span class="text-green-700 font-bold bg-green-50 px-1 rounded">Tanda
                                Bukti Penerimaan</span> permintaan informasi publik
                            kepada pemohon.
                        </p>
                    </div>
                </div>

                {{-- Step 3: Pemrosesan (KIRI) --}}
                <div class="relative flex flex-col md:flex-row items-center mb-16">
                    {{-- Teks (Kiri) --}}
                    <div class="md:w-1/2 md:text-right order-2 md:order-1 pt-2 md:pr-16 pl-20 md:pl-0">
                        <h3 class="text-xl font-bold text-green-800">3. Pemrosesan Permintaan</h3>
                        <p class="text-gray-600 mt-2 text-sm leading-relaxed">
                            Petugas memproses permintaan sesuai formulir yang telah ditandatangani. Proses ini mencakup
                            pencarian dan pengumpulan materi informasi yang diminta.
                        </p>
                    </div>

                    {{-- Icon (Tengah) --}}
                    <div
                        class="absolute left-8 md:left-1/2 -translate-x-1/2 w-14 h-14 bg-teal-600 rounded-full border-4 border-white shadow-lg flex items-center justify-center text-white z-10 order-1 md:order-2">
                        <i class="fa-solid fa-gears text-lg"></i>
                    </div>

                    <div class="md:w-1/2 order-3 md:order-3"></div>
                </div>

                {{-- Step 4: Penyerahan / Penolakan (KANAN) --}}
                <div class="relative flex flex-col md:flex-row items-start">
                    <div class="md:w-1/2 order-3 md:order-1"></div>

                    {{-- Icon (Tengah) --}}
                    <div
                        class="absolute left-8 md:left-1/2 -translate-x-1/2 w-14 h-14 bg-green-700 rounded-full border-4 border-white shadow-lg flex items-center justify-center text-white z-10 order-1 md:order-2">
                        <i class="fa-solid fa-hand-holding-hand text-lg"></i>
                    </div>

                    {{-- Teks (Kanan) --}}
                    <div class="md:w-1/2 md:text-left order-2 md:order-3 pt-2 md:pl-16 pl-20 md:pr-0">
                        <h3 class="text-xl font-bold text-green-800">4. Penyerahan & Pencatatan</h3>
                        <ul class="text-sm text-gray-600 space-y-4 mt-4">
                            <li class="flex items-start bg-green-50 p-3 rounded-lg border border-green-100">
                                <i class="fa-solid fa-check text-green-600 mt-1 mr-3"></i>
                                <span>Petugas menyerahkan informasi yang diminta. Jika informasi
                                    <strong class="text-red-600">dikecualikan</strong>, PPID menyampaikan alasan sesuai
                                    undang-undang.</span>
                            </li>
                            <li class="flex items-start">
                                <i class="fa-solid fa-check text-green-600 mt-1 mr-3"></i>
                                <span>Memberikan <strong>Tanda Bukti Penyerahan</strong> kepada pengguna.</span>
                            </li>
                            <li class="flex items-start">
                                <i class="fa-solid fa-check text-green-600 mt-1 mr-3"></i>
                                <span>Petugas melakukan pembukuan dan pencatatan arsip.</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- 3. SECTION JANGKA WAKTU & PEMBERITAHUAN --}}
    <section class="py-16 bg-green-50/50 border-y border-green-100">
        <div class="container mx-auto px-6">
            <div class="grid lg:grid-cols-2 gap-12 items-start">

                {{-- Kartu Waktu --}}
                <div class="bg-white p-8 rounded-2xl shadow-sm border border-green-200">
                    <h3 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                        <i class="fa-solid fa-hourglass-half text-green-600 mr-3"></i>
                        Jangka Waktu Penyelesaian
                    </h3>
                    <p class="text-gray-600 mb-6 text-sm">
                        Proses dimulai setelah pemohon memenuhi seluruh persyaratan yang ditentukan.
                    </p>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="p-4 bg-orange-50 rounded-xl border border-orange-100 text-center">
                            <span class="block text-4xl font-extrabold text-orange-600">10</span>
                            <span class="text-xs font-bold text-orange-800 uppercase">Hari Kerja</span>
                            <p class="text-xs text-gray-500 mt-2">Batas waktu pemberitahuan awal (dikuasai/tidak dikuasai).
                            </p>
                        </div>
                        <div class="p-4 bg-green-50 rounded-xl border border-green-200 text-center">
                            <span class="block text-4xl font-extrabold text-green-600">+7</span>
                            <span class="text-xs font-bold text-green-800 uppercase">Hari Kerja</span>
                            <p class="text-xs text-gray-500 mt-2">Perpanjangan waktu maksimal yang diperbolehkan.</p>
                        </div>
                    </div>
                </div>

                {{-- Detail Pemberitahuan --}}
                <div class="space-y-6">
                    <div class="bg-white p-6 rounded-2xl shadow-sm border-l-4 border-green-500">
                        <h4 class="font-bold text-gray-900 text-lg mb-2">Isi Surat Pemberitahuan</h4>
                        <p class="text-gray-600 text-sm leading-relaxed">
                            Jika diterima, pemberitahuan mencakup materi informasi, format (softcopy/tulis), dan biaya (jika
                            ada). Penyerahan dilakukan langsung dengan menandatangani <strong>Berita Acara
                                Penerimaan</strong>.
                        </p>
                    </div>

                    <div class="bg-white p-6 rounded-2xl shadow-sm border-l-4 border-red-500">
                        <h4 class="font-bold text-gray-900 text-lg mb-2">Jika Permohonan Ditolak</h4>
                        <p class="text-gray-600 text-sm leading-relaxed">
                            Maka dalam surat pemberitahuan akan dicantumkan secara jelas <strong>alasan penolakan</strong>
                            berdasarkan Undang-Undang Keterbukaan Informasi Publik (UU KIP).
                        </p>
                    </div>
                </div>

            </div>
        </div>
    </section>

    {{-- 4. SECTION BIAYA / TARIF --}}
    <section class="py-16 bg-white">
        <div class="container mx-auto px-6">
            <div
                class="bg-gradient-to-br from-green-50 to-emerald-50 rounded-3xl p-8 md:p-12 border border-green-100 text-center">
                <div
                    class="inline-flex items-center justify-center w-16 h-16 bg-green-100 text-green-600 rounded-full mb-6">
                    <i class="fa-solid fa-coins text-3xl"></i>
                </div>
                <h2 class="text-3xl font-bold text-green-800 mb-4">Biaya / Tarif Layanan</h2>
                <div class="max-w-2xl mx-auto">
                    <p class="text-lg text-green-900 font-medium mb-2">
                        Pejabat Pengelola Informasi dan Dokumentasi (PPID) menyediakan informasi publik secara <span
                            class="bg-green-200 px-2 py-0.5 rounded text-green-800">GRATIS</span>.
                    </p>
                    <p class="text-gray-600 text-sm leading-relaxed mt-4">
                        Namun, untuk keperluan <strong>penggandaan</strong>, menjadi tanggung jawab atau beban pemohon
                        informasi. Pemohon dapat melakukan fotocopy sendiri di sekitar gedung badan publik (PPID) setempat
                        atau biaya ditanggung pemohon.
                    </p>
                </div>
            </div>
        </div>
    </section>

    {{-- Call to Action --}}
    <section class="py-12 bg-green-900 text-center">
        <div class="container mx-auto px-6">
            <h3 class="text-2xl font-bold text-white mb-4">Siap Mengajukan Permohonan?</h3>
            <a href="{{ route('permohonan-informasi') }}"
                class="inline-flex items-center px-8 py-3 bg-white text-green-800 hover:bg-green-50 rounded-lg font-bold transition-colors duration-300 shadow-lg">
                <i class="fa-solid fa-paper-plane mr-2"></i> Isi Formulir Online
            </a>
        </div>
    </section>
@endsection