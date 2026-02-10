@extends('layout-app.app.app')

@section('content')
    {{-- Header Section --}}
    <div
        class="relative bg-gradient-to-r from-green-800 via-green-700 to-emerald-600 text-white pt-32 pb-16 overflow-hidden">

        {{-- Background Watermark Logo Kalbar --}}
        <div class="absolute inset-0 flex items-center justify-center opacity-10 pointer-events-none select-none">
            <img src="{{ asset('images/logo.png') }}" class="w-[500px] h-[500px] object-contain scale-150 transform"
                alt="Background Watermark">
        </div>

        {{-- Konten Utama Header --}}
        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">

                {{-- Logo Asli --}}
                <div class="flex justify-center mb-6">
                    <div class="p-3 bg-white/10 backdrop-blur-md rounded-full shadow-lg ring-1 ring-white/20">
                        <img src="{{ asset('images/logo.png') }}" alt="Logo Pemprov Kalbar"
                            class="h-20 w-auto drop-shadow-md filter brightness-110">
                    </div>
                </div>

                {{-- UBAH: Judul menjadi lebih kecil & konten teks diganti --}}
                {{-- text-lg (besar) pada mobile, md:text-xl (lebih besar sedikit) pada desktop --}}
                <h1 class="text-lg md:text-2xl font-bold mb-4 drop-shadow-md tracking-wide leading-relaxed uppercase">
                    PEMERINTAH PROVINSI KALIMANTAN BARAT<br>
                    PEJABAT PENGELOLA INFORMASI DAN<br>
                    DOKUMENTASI (PPID)
                </h1>

                {{-- Deskripsi --}}
                <p class="text-sm md:text-base text-green-50 max-w-2xl mx-auto drop-shadow-sm font-light mt-2">
                    Formulir Permohonan Informasi Publik
                </p>
            </div>
        </div>
    </div>

    {{-- Form Section (Warna sudah disesuaikan dengan Header Hijau) --}}
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-12 -mt-8 relative z-20">

        {{-- === BAGIAN ALERT / PEMBERITAHUAN === --}}
        @if (session('success'))
            <div class="bg-green-50 border-l-4 border-green-500 p-4 mb-6 rounded-r shadow-md animate-fade-in-down" role="alert">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-check-circle text-green-500 text-xl"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-green-700 font-bold">Berhasil!</p>
                        <p class="text-sm text-green-700 mt-1">{{ session('success') }}</p>
                    </div>
                </div>
            </div>
        @endif

        @if ($errors->any())
            <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded-r shadow-md animate-fade-in-down" role="alert">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-exclamation-circle text-red-500 text-xl"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-red-700 font-bold">Terdapat Kesalahan Pengisian!</p>
                        <ul class="mt-1 list-disc list-inside text-sm text-red-700">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif
        {{-- === END ALERT === --}}

        {{-- Card Header Form --}}
        <div class="bg-white rounded-2xl shadow-xl p-6 mb-8 border-t-4 border-green-600">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="bg-green-100 p-3 rounded-full">
                        <i class="fas fa-clipboard-list text-green-700 text-xl"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-800">Formulir Permohonan</h3>
                        <p class="text-sm text-gray-600">Isi semua data yang diperlukan</p>
                    </div>
                </div>
                <div class="hidden md:flex items-center gap-2 text-sm text-gray-600">
                    <i class="fas fa-info-circle text-green-700"></i>
                    <span>Field bertanda <span class="text-red-500">*</span> wajib diisi</span>
                </div>
            </div>
        </div>

        <form action="{{ route('permohonan-informasi.store') }}" method="POST" enctype="multipart/form-data"
            class="space-y-6">
            @csrf

            {{-- 1. Perangkat Daerah --}}
            <div class="bg-white rounded-2xl shadow-lg p-6 md:p-8 transition-all hover:shadow-xl">
                <div class="flex items-center gap-3 mb-6 pb-4 border-b-2 border-gray-100">
                    <div class="bg-green-100 p-3 rounded-lg">
                        <i class="fas fa-building text-green-700 text-xl"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-gray-800">Perangkat Daerah Tujuan</h2>
                        <p class="text-sm text-gray-600">Pilih OPD yang Anda tuju</p>
                    </div>
                </div>

                {{-- BAGIAN SELECT PERANGKAT DAERAH (DIPERBARUI) --}}
                <div class="mb-4">
                    <label for="perangkat_daerah_id" class="block text-sm font-semibold text-gray-700 mb-2">
                        OPD Tujuan <span class="text-red-500">*</span>
                    </label>
                    <select name="perangkat_daerah_id" required
                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all text-gray-900 bg-white @error('perangkat_daerah_id') border-red-500 @enderror">
                        <option value="" class="text-gray-500">-- Pilih Perangkat Daerah --</option>

                        {{-- LEVEL 1: Loop Kategori (Pemprov, Pemkab, BUMD) --}}
                        @foreach($opdList as $kategori)
                            {{-- Tampilkan Nama Kategori sebagai Group Label (Disabled) --}}
                            <option disabled class="font-extrabold text-black bg-gray-200 py-2">
                                ⭐⭐ {{ strtoupper($kategori->nama_kategori) }}
                            </option>

                            {{-- LEVEL 2: Loop Perangkat Daerah Induk (Dinas Prov, Nama Kabupaten, Induk BUMD) --}}
                            @foreach($kategori->perangkatDaerahs as $parentOpd)
                                <option value="{{ $parentOpd->id }}" 
                                    class="font-bold text-gray-900 bg-gray-50"
                                    {{ old('perangkat_daerah_id') == $parentOpd->id ? 'selected' : '' }}>
                                    &nbsp;&nbsp;★ {{ $parentOpd->nama_perangkat_daerah }}
                                </option>

                                {{-- LEVEL 3: Loop Sub Perangkat Daerah (Children) --}}
                                {{-- LOGIKA: Tampilkan anak HANYA JIKA Kategori BUKAN "Pemkab/Kota" (ID 2) --}}
                                @if($kategori->id != 2 && $parentOpd->children->isNotEmpty())
                                    @foreach($parentOpd->children as $childOpd)
                                        <option value="{{ $childOpd->id }}" 
                                            class="text-gray-600 bg-white"
                                            {{ old('perangkat_daerah_id') == $childOpd->id ? 'selected' : '' }}>
                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;├─ {{ $childOpd->nama_perangkat_daerah }}
                                        </option>
                                    @endforeach
                                @endif
                            @endforeach
                        @endforeach
                    </select>
                    @error('perangkat_daerah_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>  
            </div>

            {{-- 2. Jenis Pemohon --}}
            <div class="bg-white rounded-2xl shadow-lg p-6 md:p-8 transition-all hover:shadow-xl">
                <div class="flex items-center gap-3 mb-6 pb-4 border-b-2 border-gray-100">
                    <div class="bg-amber-100 p-3 rounded-lg">
                        <i class="fas fa-users text-amber-600 text-xl"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-gray-800">Jenis Pemohon</h2>
                        <p class="text-sm text-gray-600">Pilih kategori pemohon</p>
                    </div>
                </div>

                <div class="grid md:grid-cols-3 gap-4">
                    <label class="relative cursor-pointer group">
                        <input type="radio" name="jenis_permohonan" value="perorangan" {{ old('jenis_permohonan') == 'perorangan' ? 'checked' : '' }} required class="peer hidden"
                            onchange="toggleDokumenTambahan()">
                        <div
                            class="border-2 border-gray-200 rounded-xl p-6 text-center transition-all peer-checked:border-green-600 peer-checked:bg-green-50 peer-checked:shadow-lg hover:border-green-300 group-hover:scale-105">
                            <i
                                class="fas fa-user text-4xl text-gray-400 mb-3 peer-checked:text-green-600 transition-colors"></i>
                            <p class="font-semibold text-gray-700">Perorangan</p>
                        </div>
                    </label>
                    <label class="relative cursor-pointer group">
                        <input type="radio" name="jenis_permohonan" value="badan_hukum" {{ old('jenis_permohonan') == 'badan_hukum' ? 'checked' : '' }} required class="peer hidden"
                            onchange="toggleDokumenTambahan()">
                        <div
                            class="border-2 border-gray-200 rounded-xl p-6 text-center transition-all peer-checked:border-amber-500 peer-checked:bg-amber-50 peer-checked:shadow-lg hover:border-amber-300 group-hover:scale-105">
                            <i
                                class="fas fa-building text-4xl text-gray-400 mb-3 peer-checked:text-amber-600 transition-colors"></i>
                            <p class="font-semibold text-gray-700">Badan Hukum</p>
                        </div>
                    </label>
                    <label class="relative cursor-pointer group">
                        <input type="radio" name="jenis_permohonan" value="kelompok" {{ old('jenis_permohonan') == 'kelompok' ? 'checked' : '' }} required class="peer hidden" onchange="toggleDokumenTambahan()">
                        <div
                            class="border-2 border-gray-200 rounded-xl p-6 text-center transition-all peer-checked:border-emerald-600 peer-checked:bg-emerald-50 peer-checked:shadow-lg hover:border-emerald-300 group-hover:scale-105">
                            <i
                                class="fas fa-users text-4xl text-gray-400 mb-3 peer-checked:text-emerald-600 transition-colors"></i>
                            <p class="font-semibold text-gray-700">Kelompok</p>
                        </div>
                    </label>
                </div>
                @error('jenis_permohonan') <p class="text-red-500 text-xs mt-2">{{ $message }}</p> @enderror
            </div>

            {{-- 3. Data Pemohon --}}
            <div class="bg-white rounded-2xl shadow-lg p-6 md:p-8 transition-all hover:shadow-xl">
                <div class="flex items-center gap-3 mb-6 pb-4 border-b-2 border-gray-100">
                    <div class="bg-green-100 p-3 rounded-lg">
                        <i class="fas fa-id-card text-green-600 text-xl"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-gray-800">Data Pemohon</h2>
                        <p class="text-sm text-gray-600">Informasi identitas pemohon</p>
                    </div>
                </div>

                <div class="grid md:grid-cols-2 gap-6">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Pemohon <span
                                class="text-red-500">*</span></label>
                        <input type="text" name="nama_pemohon" value="{{ old('nama_pemohon') }}" required
                            class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all @error('nama_pemohon') border-red-500 @enderror"
                            placeholder="Masukkan nama lengkap">
                        @error('nama_pemohon') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Tanggal Lahir <span
                                class="text-red-500">*</span></label>
                        <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir') }}" required
                            class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all @error('tanggal_lahir') border-red-500 @enderror">
                        @error('tanggal_lahir') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Jenis Kelamin <span
                                class="text-red-500">*</span></label>
                        <select name="jenis_kelamin" required
                            class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all @error('jenis_kelamin') border-red-500 @enderror">
                            <option value="">-- Pilih --</option>
                            <option value="Laki-laki" {{ old('jenis_kelamin') == 'Laki-laki' ? 'selected' : '' }}>Laki-laki
                            </option>
                            <option value="Perempuan" {{ old('jenis_kelamin') == 'Perempuan' ? 'selected' : '' }}>Perempuan
                            </option>
                        </select>
                        @error('jenis_kelamin') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">No. Identitas (KTP/NIK) <span
                                class="text-red-500">*</span></label>
                        <input type="text" name="no_identitas" value="{{ old('no_identitas') }}" maxlength="16" required
                            class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all @error('no_identitas') border-red-500 @enderror"
                            placeholder="16 digit NIK">
                        @error('no_identitas') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Scan KTP/Identitas <span
                                class="text-red-500">*</span></label>
                        <input type="file" name="scan_identitas" accept="image/*,.pdf" required
                            class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-green-50 file:text-green-700 hover:file:bg-green-100 @error('scan_identitas') border-red-500 @enderror">
                        <p class="text-xs text-gray-500 mt-1">Format: JPG, PNG, PDF (Max: 2MB)</p>
                        @error('scan_identitas') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            {{-- 4. Dokumen Tambahan (Kondisional) --}}
            <div id="dokumenTambahanSection"
                class="bg-white rounded-2xl shadow-lg p-6 md:p-8 transition-all hover:shadow-xl hidden">
                <div class="flex items-center gap-3 mb-6 pb-4 border-b-2 border-gray-100">
                    <div class="bg-amber-100 p-3 rounded-lg">
                        <i class="fas fa-folder-open text-amber-600 text-xl"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-gray-800">Dokumen Tambahan</h2>
                        <p class="text-sm text-gray-600">Upload dokumen pendukung</p>
                    </div>
                </div>

                <div class="bg-amber-50 border-l-4 border-amber-500 p-4 mb-4 rounded-r-lg">
                    <div class="flex gap-3">
                        <i class="fas fa-info-circle text-amber-600 mt-1"></i>
                        <div>
                            <p class="font-semibold text-amber-800">Dokumen yang Diperlukan:</p>
                            <ul class="text-sm text-amber-700 mt-2 space-y-1">
                                <li id="dokBadanHukum" class="hidden">• Untuk Badan Hukum: Akta Pendirian, SK Kemenkumham
                                </li>
                                <li id="dokKelompok" class="hidden">• Untuk Kelompok: Surat Keterangan Kelompok, Daftar
                                    Anggota</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Upload Dokumen <span class="text-red-500"
                            id="requiredMark">*</span></label>
                    <input type="file" name="dokumen_tambahan_path" accept=".pdf,.doc,.docx,.jpg,.png"
                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-amber-50 file:text-amber-700 hover:file:bg-amber-100 @error('dokumen_tambahan_path') border-red-500 @enderror">
                    <p class="text-xs text-gray-500 mt-1">Format: PDF, DOC, JPG, PNG (Max: 5MB)</p>
                    @error('dokumen_tambahan_path') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            {{-- 5. Informasi Kontak --}}
            <div class="bg-white rounded-2xl shadow-lg p-6 md:p-8 transition-all hover:shadow-xl">
                <div class="flex items-center gap-3 mb-6 pb-4 border-b-2 border-gray-100">
                    <div class="bg-green-100 p-3 rounded-lg">
                        <i class="fas fa-address-book text-green-600 text-xl"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-gray-800">Informasi Kontak</h2>
                        <p class="text-sm text-gray-600">Data kontak untuk tindak lanjut</p>
                    </div>
                </div>

                <div class="grid md:grid-cols-2 gap-6">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Alamat Lengkap <span
                                class="text-red-500">*</span></label>
                        <textarea name="alamat_lengkap" rows="3" required
                            class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all @error('alamat_lengkap') border-red-500 @enderror"
                            placeholder="Jalan, RT/RW, Kelurahan, Kecamatan, Kota">{{ old('alamat_lengkap') }}</textarea>
                        @error('alamat_lengkap') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Nomor WhatsApp <span
                                class="text-red-500">*</span></label>
                        <div class="relative">
                            <span class="absolute left-4 top-3.5 text-gray-500"><i class="fab fa-whatsapp"></i></span>
                            <input type="text" name="nomor_whatsapp" value="{{ old('nomor_whatsapp') }}" required
                                class="w-full pl-12 pr-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all @error('nomor_whatsapp') border-red-500 @enderror"
                                placeholder="08xxxxxxxxxx">
                        </div>
                        @error('nomor_whatsapp') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Email <span
                                class="text-red-500">*</span></label>
                        <div class="relative">
                            <span class="absolute left-4 top-3.5 text-gray-500"><i class="fas fa-envelope"></i></span>
                            <input type="email" name="alamat_email" value="{{ old('alamat_email') }}" required
                                class="w-full pl-12 pr-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all @error('alamat_email') border-red-500 @enderror"
                                placeholder="email@example.com">
                        </div>
                        @error('alamat_email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Nomor Fax <span
                                class="text-gray-400 text-xs">(Opsional)</span></label>
                        <input type="text" name="nomor_fax" value="{{ old('nomor_fax') }}"
                            class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all @error('nomor_fax') border-red-500 @enderror"
                            placeholder="021-xxxxxxx (Opsional)">
                        @error('nomor_fax') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            {{-- 6. Detail Permohonan --}}
            <div class="bg-white rounded-2xl shadow-lg p-6 md:p-8 transition-all hover:shadow-xl">
                <div class="flex items-center gap-3 mb-6 pb-4 border-b-2 border-gray-100">
                    <div class="bg-emerald-100 p-3 rounded-lg">
                        <i class="fas fa-clipboard-list text-emerald-600 text-xl"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-gray-800">Detail Permohonan</h2>
                        <p class="text-sm text-gray-600">Rincian informasi yang diminta</p>
                    </div>
                </div>

                <div class="grid gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Informasi yang Diminta <span
                                class="text-red-500">*</span></label>
                        <textarea name="informasi_diminta" rows="4" required
                            class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all @error('informasi_diminta') border-red-500 @enderror"
                            placeholder="Jelaskan secara detail informasi yang Anda butuhkan...">{{ old('informasi_diminta') }}</textarea>
                        @error('informasi_diminta') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Alasan Permintaan <span
                                class="text-red-500">*</span></label>
                        <textarea name="alasan_permintaan" rows="4" required
                            class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all @error('alasan_permintaan') border-red-500 @enderror"
                            placeholder="Jelaskan mengapa Anda memerlukan informasi ini...">{{ old('alasan_permintaan') }}</textarea>
                        @error('alasan_permintaan') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Cara Penyampaian Informasi <span
                                class="text-red-500">*</span></label>
                        <input type="text" name="cara_penyampaian_informasi" value="{{ old('cara_penyampaian_informasi') }}"
                            required
                            class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all @error('cara_penyampaian_informasi') border-red-500 @enderror"
                            placeholder="Contoh: Softcopy via email, Hardcopy dijemput">
                        @error('cara_penyampaian_informasi') <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Tindak Lanjut <span
                                class="text-red-500">*</span></label>
                        <div class="grid md:grid-cols-3 gap-3">
                            <label class="relative cursor-pointer group">
                                <input type="radio" name="tindak_lanjut" value="Email" {{ old('tindak_lanjut') == 'Email' ? 'checked' : '' }} required class="peer hidden">
                                <div
                                    class="border-2 border-gray-200 rounded-xl p-4 text-center transition-all peer-checked:border-emerald-600 peer-checked:bg-emerald-50 hover:border-emerald-300 group-hover:scale-105">
                                    <i
                                        class="fas fa-envelope text-2xl text-gray-400 mb-2 transition-colors peer-checked:text-emerald-600"></i>
                                    <p class="text-sm font-semibold">Email</p>
                                </div>
                            </label>
                            <label class="relative cursor-pointer group">
                                <input type="radio" name="tindak_lanjut" value="WhatsApp" {{ old('tindak_lanjut') == 'WhatsApp' ? 'checked' : '' }} required class="peer hidden">
                                <div
                                    class="border-2 border-gray-200 rounded-xl p-4 text-center transition-all peer-checked:border-green-600 peer-checked:bg-green-50 hover:border-green-300 group-hover:scale-105">
                                    <i
                                        class="fab fa-whatsapp text-2xl text-gray-400 mb-2 transition-colors peer-checked:text-green-600"></i>
                                    <p class="text-sm font-semibold">WhatsApp</p>
                                </div>
                            </label>
                            <label class="relative cursor-pointer group">
                                <input type="radio" name="tindak_lanjut" value="whatsapp/email" {{ old('tindak_lanjut') == 'whatsapp/email' ? 'checked' : '' }} required class="peer hidden">
                                <div
                                    class="border-2 border-gray-200 rounded-xl p-4 text-center transition-all peer-checked:border-amber-500 peer-checked:bg-amber-50 hover:border-amber-300 group-hover:scale-105">
                                    <i
                                        class="fas fa-paper-plane text-2xl text-gray-400 mb-2 transition-colors peer-checked:text-amber-600"></i>
                                    <p class="text-sm font-semibold">WA/Email</p>
                                </div>
                            </label>
                        </div>
                        @error('tindak_lanjut') <p class="text-red-500 text-xs mt-2">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            {{-- Tombol Aksi --}}
            <div class="bg-white rounded-2xl shadow-lg p-6 md:p-8 sticky bottom-4">
                <div class="flex items-center justify-between flex-wrap gap-4">
                    <p class="text-sm text-gray-600">
                        <i class="fas fa-shield-alt text-green-600"></i>
                        Data Anda akan kami jaga kerahasiaannya
                    </p>
                    <div class="flex gap-3 flex-wrap">
                        <a href="{{ route('beranda.index') }}"
                            class="px-6 py-3 border-2 border-gray-300 text-gray-700 rounded-xl font-semibold hover:bg-gray-50 transition-all inline-flex items-center gap-2">
                            <i class="fas fa-arrow-left"></i>
                            <span>Kembali</span>
                        </a>
                        <a type="button" href="{{ url()->current() }}"
                            class="px-6 py-3 border-2 border-gray-300 text-gray-700 rounded-xl font-semibold hover:bg-gray-50 transition-all inline-flex items-center gap-2">
                            <i class="fas fa-redo"></i>
                            <span>Reset</span>
                        </a>
                        <button type="submit"
                            class="px-8 py-3 bg-gradient-to-r from-green-700 to-emerald-600 text-white rounded-xl font-semibold hover:from-green-800 hover:to-emerald-700 hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-200 inline-flex items-center gap-2">
                            <i class="fas fa-paper-plane"></i>
                            <span>Kirim Permohonan</span>
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    {{-- Script untuk Toggle Dokumen --}}
    <script>
        function toggleDokumenTambahan() {
            const radioSelected = document.querySelector('input[name="jenis_permohonan"]:checked');
            const sectionDokumen = document.getElementById('dokumenTambahanSection');
            const listBadanHukum = document.getElementById('dokBadanHukum');
            const listKelompok = document.getElementById('dokKelompok');
            const fileInput = document.querySelector('input[name="dokumen_tambahan_path"]');

            const jenisPermohonan = radioSelected ? radioSelected.value : null;

            if (listBadanHukum) listBadanHukum.classList.add('hidden');
            if (listKelompok) listKelompok.classList.add('hidden');

            if (jenisPermohonan === 'badan_hukum' || jenisPermohonan === 'kelompok') {
                sectionDokumen.classList.remove('hidden');
                if (fileInput) fileInput.required = true;

                if (jenisPermohonan === 'badan_hukum') {
                    listBadanHukum.classList.remove('hidden');
                } else if (jenisPermohonan === 'kelompok') {
                    listKelompok.classList.remove('hidden');
                }
            } else {
                sectionDokumen.classList.add('hidden');
                if (fileInput) fileInput.required = false;
            }
        }

        document.addEventListener('DOMContentLoaded', function () {
            toggleDokumenTambahan();
        });
    </script>
@endsection