@extends('layout-app.app.app')

@section('content')
    {{-- Notifikasi Success/Error --}}
    @if(session('success'))
        <div
            class="fixed top-24 right-5 z-[100] bg-green-500 text-white px-6 py-4 rounded-2xl shadow-2xl border-b-4 border-green-700 animate-bounce">
            <div class="flex items-center gap-3">
                <i class="fas fa-check-circle text-xl"></i>
                <span class="font-bold">{{ session('success') }}</span>
            </div>
        </div>
    @endif

    {{-- Header Section --}}
    <div
        class="relative bg-gradient-to-r from-green-800 via-green-700 to-emerald-600 text-white pt-32 pb-16 overflow-hidden">
        {{-- Background Watermark Logo Kalbar --}}
        <div class="absolute inset-0 flex items-center justify-center opacity-10 pointer-events-none select-none">
            <img src="{{ asset('images/logo.png') }}" class="w-[500px] h-[500px] object-contain scale-150 transform"
                alt="Background Watermark">
        </div>

        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <div class="flex justify-center mb-6">
                    <div class="p-3 bg-white/10 backdrop-blur-md rounded-full shadow-lg ring-1 ring-white/20">
                        <img src="{{ asset('images/logo.png') }}" alt="Logo Pemprov Kalbar"
                            class="h-20 w-auto drop-shadow-md filter brightness-110">
                    </div>
                </div>
                <h1 class="text-lg md:text-2xl font-bold mb-4 drop-shadow-md tracking-wide leading-relaxed uppercase">
                    PEMERINTAH PROVINSI KALIMANTAN BARAT<br>
                    PEJABAT PENGELOLA INFORMASI DAN<br>
                    DOKUMENTASI (PPID)
                </h1>
                <p
                    class="text-sm md:text-base text-green-50 max-w-2xl mx-auto drop-shadow-sm font-light mt-2 uppercase tracking-widest">
                    Survey Akses & Kualitas Informasi Publik
                </p>
            </div>
        </div>
    </div>

    {{-- Form Section --}}
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12 -mt-8 relative z-20">

        <form action="{{ route('survey-kualitas-informasi.store') }}" method="POST" class="space-y-8">
            @csrf

            {{-- Card Info Singkat --}}
            <div class="bg-white rounded-2xl shadow-xl p-6 border-t-4 border-green-600">
                <div class="flex items-center gap-4">
                    <div class="bg-green-100 p-3 rounded-full">
                        <i class="fas fa-poll-h text-green-700 text-xl"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-800 text-lg">Partisipasi Masyarakat</h3>
                        <p class="text-sm text-gray-600 leading-relaxed">
                            Mohon luangkan waktu Anda untuk menjawab pertanyaan berikut. Masukan Anda sangat berharga
                            bagi kami untuk meningkatkan kualitas layanan informasi.
                        </p>
                    </div>
                </div>
            </div>

            {{-- Card: Data Responden --}}
            <div
                class="bg-white rounded-3xl shadow-lg border border-gray-100 overflow-hidden transition-all hover:shadow-2xl">
                <div class="bg-gray-50/80 px-8 py-5 border-b flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-green-100 rounded-xl flex items-center justify-center mr-4">
                            <i class="fas fa-user-edit text-green-600"></i>
                        </div>
                        <h2 class="text-xl font-bold text-gray-800 uppercase tracking-wide">Data Responden</h2>
                    </div>
                </div>

                <div class="p-8 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-1">
                        <label class="text-xs font-bold text-gray-500 uppercase ml-1">Nama Lengkap</label>
                        <input type="text" name="nama" required placeholder="Masukkan nama Anda"
                            class="w-full px-5 py-3.5 rounded-2xl border-2 border-gray-50 bg-gray-50 focus:bg-white focus:ring-4 focus:ring-green-500/10 focus:border-green-500 transition-all outline-none shadow-sm">
                    </div>
                    <div class="space-y-1">
                        <label class="text-xs font-bold text-gray-500 uppercase ml-1">Nomor HP / WA</label>
                        <input type="text" name="no_hp" required placeholder="0812xxxx"
                            class="w-full px-5 py-3.5 rounded-2xl border-2 border-gray-50 bg-gray-50 focus:bg-white focus:ring-4 focus:ring-green-500/10 focus:border-green-500 transition-all outline-none shadow-sm">
                    </div>
                    <div class="space-y-1">
                        <label class="text-xs font-bold text-gray-500 uppercase ml-1">Usia</label>
                        <input type="number" name="usia" required placeholder="25"
                            class="w-full px-5 py-3.5 rounded-2xl border-2 border-gray-50 bg-gray-50 focus:bg-white focus:ring-4 focus:ring-green-500/10 focus:border-green-500 transition-all outline-none shadow-sm">
                    </div>
                    <div class="space-y-1">
                        <label class="text-xs font-bold text-gray-500 uppercase ml-1">Jenis Kelamin</label>
                        <select name="jenis_kelamin" required
                            class="w-full px-5 py-3.5 rounded-2xl border-2 border-gray-50 bg-gray-50 focus:bg-white focus:ring-4 focus:ring-green-500/10 focus:border-green-500 transition-all outline-none shadow-sm">
                            <option value="" disabled selected>Pilih...</option>
                            <option value="Laki-laki">Laki-laki</option>
                            <option value="Perempuan">Perempuan</option>
                        </select>
                    </div>
                    <div class="space-y-1">
                        <label class="text-xs font-bold text-gray-500 uppercase ml-1">Pendidikan</label>
                        <select name="pendidikan" required
                            class="w-full px-5 py-3.5 rounded-2xl border-2 border-gray-50 bg-gray-50 focus:bg-white focus:ring-4 focus:ring-green-500/10 focus:border-green-500 transition-all outline-none shadow-sm">
                            <option value="" disabled selected>Pilih Pendidikan...</option>
                            @foreach(['SD/MI sederajat', 'SMP/MTs sederajat', 'SMA/SMK/MA sederajat', 'D1/D3', 'D4/S1', 'S2/S3', 'Lainnya'] as $p)
                                <option value="{{ $p }}">{{ $p }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="space-y-1">
                        <label class="text-xs font-bold text-gray-500 uppercase ml-1">Pekerjaan</label>
                        <select name="pekerjaan" required
                            class="w-full px-5 py-3.5 rounded-2xl border-2 border-gray-50 bg-gray-50 focus:bg-white focus:ring-4 focus:ring-green-500/10 focus:border-green-500 transition-all outline-none shadow-sm">
                            <option value="" disabled selected>Pilih Pekerjaan...</option>
                            @foreach(['Pelajar/Mahasiswa', 'Pegawai Negeri Sipil', 'TNI/POLRI', 'Karyawan Swasta', 'Wirausaha', 'Lainnya'] as $pek)
                                <option value="{{ $pek }}">{{ $pek }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            {{-- Card: Kuisioner (Dinamis dari Database) --}}
            <div
                class="bg-white rounded-3xl shadow-lg border border-gray-100 overflow-hidden transition-all hover:shadow-2xl">
                <div class="bg-gray-50/80 px-8 py-5 border-b flex items-center">
                    <div class="w-10 h-10 bg-green-100 rounded-xl flex items-center justify-center mr-4">
                        <i class="fas fa-clipboard-list text-green-600"></i>
                    </div>
                    <h2 class="text-xl font-bold text-gray-800 uppercase tracking-wide">Kuisioner Penilaian</h2>
                </div>

                <div class="p-8 space-y-10">
                    @forelse($pertanyaan as $index => $item)
                        <div
                            class="p-6 bg-green-50/30 rounded-3xl border border-green-100 group transition-all hover:bg-green-50">
                            <p class="text-gray-800 font-bold mb-6 text-lg leading-relaxed">
                                {{ $index + 1 }}. {{ $item->pertanyaan }}
                            </p>
                            <div class="space-y-3">
                                @foreach(['Sangat Setuju', 'Setuju', 'Cukup', 'Tidak Setuju', 'Sangat Tidak Setuju'] as $option)
                                    <label
                                        class="flex items-center p-4 bg-white border-2 border-gray-100 rounded-2xl cursor-pointer hover:border-green-500 hover:shadow-md transition-all group/item">
                                        <input type="radio" name="jawaban[{{ $item->id }}]" value="{{ $option }}" required
                                            class="w-5 h-5 text-green-600 border-gray-300 focus:ring-green-500">
                                        <span
                                            class="ml-4 text-gray-700 font-semibold group-hover/item:text-green-700 transition-colors">{{ $option }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-10">
                            <i class="fas fa-folder-open text-gray-300 text-5xl mb-4"></i>
                            <p class="text-gray-500 font-medium">Belum ada pertanyaan survey yang tersedia.</p>
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- TOMBOL AKSI STICKY --}}
            <div
                class="bg-white rounded-2xl shadow-2xl p-6 md:p-8 sticky bottom-6 border border-gray-100 ring-4 ring-black/5 transition-all">
                <div class="flex items-center justify-between flex-wrap gap-4">
                    <div class="flex items-center gap-3">
                        <div class="hidden sm:flex w-10 h-10 bg-green-100 rounded-full items-center justify-center">
                            <i class="fas fa-shield-alt text-green-600"></i>
                        </div>
                        <p class="text-xs md:text-sm text-gray-600 font-medium">
                            Pastikan semua pertanyaan <br class="hidden md:block"> telah terisi dengan benar.
                        </p>
                    </div>

                    <div class="flex gap-3 flex-wrap w-full md:w-auto">
                        <a href="{{ route('beranda.index') }}"
                            class="flex-1 md:flex-none px-6 py-3 border-2 border-gray-200 text-gray-700 rounded-xl font-bold hover:bg-gray-50 transition-all text-center flex items-center justify-center gap-2">
                            <i class="fas fa-arrow-left text-xs"></i>
                            <span>Batal</span>
                        </a>
                        <button type="submit"
                            class="flex-[2] md:flex-none px-10 py-3 bg-gradient-to-r from-green-700 to-emerald-600 text-white rounded-xl font-bold hover:from-green-800 hover:to-emerald-700 hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-200 flex items-center justify-center gap-3 uppercase tracking-widest">
                            <span>Kirim Jawaban</span>
                            <i class="fas fa-paper-plane"></i>
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection