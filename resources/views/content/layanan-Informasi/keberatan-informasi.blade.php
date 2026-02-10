@extends('layout-app.app.app')

@section('content')
            {{-- === HEADER SECTION (SESUAI TEMA PEMPROV) === --}}
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

                        {{-- Logo Asli Tengah --}}
                        <div class="flex justify-center mb-6">
                            <div class="p-3 bg-white/10 backdrop-blur-md rounded-full shadow-lg ring-1 ring-white/20">
                                <img src="{{ asset('images/logo.png') }}" alt="Logo Pemprov Kalbar"
                                    class="h-20 w-auto drop-shadow-md filter brightness-110">
                            </div>
                        </div>

                        {{-- Judul Formal --}}
                        <h1 class="text-lg md:text-2xl font-bold mb-4 drop-shadow-md tracking-wide leading-relaxed uppercase">
                            PEMERINTAH PROVINSI KALIMANTAN BARAT<br>
                            PEJABAT PENGELOLA INFORMASI DAN<br>
                            DOKUMENTASI (PPID)
                        </h1>

                        {{-- Sub-judul --}}
                        <p class="text-sm md:text-base text-green-50 max-w-2xl mx-auto drop-shadow-sm font-light mt-2">
                            Formulir Pengajuan Keberatan Informasi Publik
                        </p>
                    </div>
                </div>
            </div>

            {{-- === FORM SECTION === --}}
            <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 -mt-8 pb-20 relative z-20">
                {{-- === BAGIAN ALERT / PEMBERITAHUAN === --}}

                {{-- 1. Alert SUKSES --}}
                @if (session('success'))
                    <div class="bg-green-50 border-l-4 border-green-500 p-4 mb-8 rounded-r-xl shadow-md animate-fade-in-down flex items-start gap-3"
                        role="alert">
                        <div class="flex-shrink-0 mt-0.5">
                            <i class="fas fa-check-circle text-green-600 text-xl"></i>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-bold text-green-800">
                                Berhasil Dikirim!
                            </p>
                            <p class="text-sm text-green-700 mt-1 leading-relaxed">
                                {{ session('success') }}
                            </p>
                        </div>
                        <button type="button" onclick="this.parentElement.remove()"
                            class="text-green-500 hover:text-green-700 transition-colors">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                @endif

                {{-- 2. Alert GAGAL / ERROR --}}
                @if ($errors->any())
                    <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-8 rounded-r-xl shadow-md animate-fade-in-down flex items-start gap-3"
                        role="alert">
                        <div class="flex-shrink-0 mt-0.5">
                            <i class="fas fa-exclamation-circle text-red-600 text-xl"></i>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-bold text-red-800">
                                Gagal Menyimpan!
                            </p>
                            <p class="text-sm text-red-700 mt-1">
                                Mohon periksa kembali isian formulir Anda:
                            </p>
                            <ul class="mt-2 list-disc list-inside text-sm text-red-700 space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                        <button type="button" onclick="this.parentElement.remove()"
                            class="text-red-500 hover:text-red-700 transition-colors">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                @endif
                {{-- === END ALERT === --}}


                <form action="{{ route('keberatan-informasi.store') }}" method="POST" enctype="multipart/form-data"
                    class="space-y-8">
                    @csrf

                    {{-- CARD 1: IDENTITAS & REFERENSI --}}
                    {{-- UBAH: Shadow dan Border menjadi nuansa hijau/netral --}}
                    <div class="bg-white rounded-3xl shadow-xl p-8 border-t-4 border-green-600 transition-all hover:shadow-2xl">
                        <div class="flex items-center gap-4 mb-8 pb-4 border-b border-gray-100">
                            {{-- UBAH: Icon Biru -> Hijau --}}
                            <div class="w-12 h-12 bg-green-100 rounded-2xl flex items-center justify-center shadow-sm">
                                <i class="fas fa-user-shield text-green-700 text-xl"></i>
                            </div>
                            <div>
                                <h2 class="text-xl font-bold text-gray-800">Identitas & Referensi</h2>
                                <p class="text-sm text-gray-500">Masukkan NIK untuk melengkapi data dan memilih permohonan</p>
                            </div>
                        </div>

                        <div class="grid md:grid-cols-2 gap-6">

                            {{-- 1. INPUT NIK (MASTER KEY) --}}
                            <div class="md:col-span-2">
                                <label class="block text-sm font-bold text-gray-700 mb-2">
                                    NIK Pemohon <span class="text-red-500">*</span>
                                </label>
                                <div class="flex gap-2 relative">
                                    {{-- UBAH: Focus ring Indigo -> Green --}}
                                    <input type="text" name="nik_pemohon" id="nik_pemohon" maxlength="16"
                                        value="{{ old('nik_pemohon') }}"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 transition-all placeholder-gray-400 font-medium tracking-wide"
                                        placeholder="Masukkan 16 digit NIK Anda">

                                    {{-- UBAH: Tombol Cek Indigo -> Green --}}
                                    <button type="button" id="btn_cek_nik"
                                        class="absolute right-2 top-2 bottom-2 px-4 bg-green-700 text-white font-bold rounded-lg hover:bg-green-800 transition-all text-sm flex items-center gap-2 shadow-md">
                                        <i class="fas fa-search"></i> Cek Riwayat
                                    </button>
                                </div>
                                <p id="search_status" class="text-xs mt-2 hidden"></p>
                                @error('nik_pemohon') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>

                            {{-- 2. DROPDOWN NO REGISTRASI --}}
                            <div class="md:col-span-2 bg-gray-50 p-4 rounded-xl border border-gray-200" id="wrapper_registrasi">
                                <label class="block text-sm font-bold text-gray-700 mb-2">
                                    Pilih Permohonan yang Diajukan Keberatan <span class="text-red-500">*</span>
                                </label>
                                {{-- UBAH: Focus ring Indigo -> Green --}}
                                <select name="permohonan_informasi_id" id="permohonan_informasi_id" disabled
                                    class="w-full px-4 py-3 bg-gray-200 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all cursor-not-allowed opacity-60 text-gray-500">
                                    <option value="">-- Masukkan & Cek NIK Terlebih Dahulu --</option>
                                </select>
                                <p class="text-xs text-gray-500 mt-2">
                                    <i class="fas fa-info-circle"></i> Daftar nomor registrasi akan muncul otomatis jika NIK Anda
                                    ditemukan dalam sistem.
                                </p>
                            </div>

                            <div class="md:col-span-1">
                                <label class="block text-sm font-bold text-gray-700 mb-2">Nama Lengkap <span
                                        class="text-red-500">*</span></label>
                                {{-- UBAH: Focus ring --}}
                                <input type="text" name="nama_pemohon" id="nama_pemohon" value="{{ old('nama_pemohon') }}"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 transition-all bg-gray-50"
                                    readonly>
                            </div>

                            <div class="md:col-span-1">
                                <label class="block text-sm font-bold text-gray-700 mb-2">Nomor Telepon/WA <span
                                        class="text-red-500">*</span></label>
                                <div class="relative">
                                    <span class="absolute left-4 top-3.5 text-gray-500"><i class="fas fa-phone"></i></span>
                                    {{-- UBAH: Focus ring --}}
                                    <input type="text" name="telepon_pemohon" id="telepon_pemohon"
                                        value="{{ old('telepon_pemohon') }}"
                                        class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 transition-all "
                                        placeholder="08xxxxxx">
                                </div>
                            </div>

                            <div class="md:col-span-2">
                                <label class="block text-sm font-bold text-gray-700 mb-2">Pekerjaan</label>
                                {{-- UBAH: Focus ring --}}
                                <input type="text" name="pekerjaan" value="{{ old('pekerjaan') }}"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 transition-all">
                            </div>

                            <div class="md:col-span-2">
                                <label class="block text-sm font-bold text-gray-700 mb-2">Alamat Lengkap</label>
                                {{-- UBAH: Focus ring --}}
                                <textarea name="alamat_pemohon" id="alamat_pemohon" rows="3"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 transition-all resize-none">{{ old('alamat_pemohon') }}</textarea>
                            </div>
                        </div>
                    </div>

                    {{-- CARD 2: IDENTITAS KUASA (SECONDARY COLOR: AMBER/EMAS) --}}
                    <div class="bg-white rounded-3xl shadow-xl p-8 border border-gray-100 transition-all hover:shadow-2xl">
                        <div class="flex items-center justify-between mb-6 pb-4 border-b border-gray-100">
                            <div class="flex items-center gap-4">
                                {{-- UBAH: Purple -> Amber (Kuning Emas) --}}
                                <div class="w-12 h-12 bg-amber-100 rounded-2xl flex items-center justify-center shadow-sm">
                                    <i class="fas fa-gavel text-amber-600 text-xl"></i>
                                </div>
                                <div>
                                    <h2 class="text-xl font-bold text-gray-800">Identitas Kuasa</h2>
                                    <p class="text-sm text-gray-500">Diisi jika pengajuan dikuasakan</p>
                                </div>
                            </div>
                            {{-- UBAH: Toggle Switch Color --}}
                            <label class="inline-flex items-center cursor-pointer">
                                <input type="checkbox" id="toggleKuasa" class="sr-only peer">
                                <div
                                    class="relative w-14 h-7 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-amber-300 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:start-[4px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-6 after:w-6 after:transition-all peer-checked:bg-amber-500">
                                </div>
                                <span class="ms-3 text-sm font-medium text-gray-700">Dikuasakan</span>
                            </label>
                        </div>

                        <div id="formKuasa" class="hidden space-y-6 animate-fade-in-down">
                            {{-- UBAH: Background form kuasa --}}
                            <div class="bg-amber-50 p-6 rounded-2xl border border-amber-100">
                                <div class="grid md:grid-cols-2 gap-6">
                                    <div class="md:col-span-1">
                                        <label class="block text-sm font-bold text-gray-700 mb-2">Nama Penerima Kuasa</label>
                                        {{-- UBAH: Focus ring Amber --}}
                                        <input type="text" name="nama_kuasa" value="{{ old('nama_kuasa') }}"
                                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-amber-500 transition-all">
                                    </div>
                                    <div class="md:col-span-1">
                                        <label class="block text-sm font-bold text-gray-700 mb-2">Nomor Telepon Kuasa</label>
                                        <input type="text" name="telepon_kuasa" value="{{ old('telepon_kuasa') }}"
                                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-amber-500 transition-all">
                                    </div>
                                    <div class="md:col-span-2">
                                        <label class="block text-sm font-bold text-gray-700 mb-2">Alamat Kuasa</label>
                                        <textarea name="alamat_kuasa" rows="2"
                                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-amber-500 transition-all resize-none">{{ old('alamat_kuasa') }}</textarea>
                                    </div>
                                    <div class="md:col-span-2">
                                        <label class="block text-sm font-bold text-gray-700 mb-2">
                                            Upload Surat Kuasa <span class="text-xs font-normal text-gray-500">(PDF/JPG, Max
                                                2MB)</span>
                                        </label>
                                        {{-- UBAH: File input styling --}}
                                        <input type="file" name="surat_kuasa"
                                            class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-amber-100 file:text-amber-700 hover:file:bg-amber-200">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- CARD 3: ALASAN KEBERATAN --}}
                    <div class="bg-white rounded-3xl shadow-xl p-8 border border-gray-100 transition-all hover:shadow-2xl">
                        <div class="flex items-center gap-4 mb-6 pb-4 border-b border-gray-100">
                            <div class="w-12 h-12 bg-red-100 rounded-2xl flex items-center justify-center shadow-sm">
                                <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
                            </div>
                            <div>
                                <h2 class="text-xl font-bold text-gray-800">Alasan Keberatan</h2>
                                <p class="text-sm text-gray-500">Pilih alasan keberatan</p>
                            </div>
                        </div>
                        <div class="grid md:grid-cols-2 gap-4">
                            @php
    $alasanList = [
        'Permohonan Informasi Di Tolak',
        'Informasi Berkala Tidak Disediakan',
        'Permintaan Informasi Tidak Ditanggapi',
        'Permintaan Informasi Ditanggapi Tidak Sebagaimana Yang Diminta',
        'Permintaan Informasi Tidak Dipenuhi',
        'Biaya Yang Dikenakan Tidak Wajar',
        'Informasi Disampaikan Melebihi Jangka Waktu Yang Ditentukan',
    ];
                            @endphp
                            @foreach($alasanList as $index => $alasan)
                                <div class="relative flex items-start py-2">
                                    <div class="flex items-center h-6">
                                        {{-- UBAH: Checkbox color Indigo -> Green --}}
                                        <input id="alasan_{{ $index }}" name="alasan_keberatan[]" value="{{ $alasan }}" type="checkbox"
                                            class="w-5 h-5 text-green-600 bg-gray-100 border-gray-300 rounded focus:ring-green-500 focus:ring-2 cursor-pointer"
                                            {{ (is_array(old('alasan_keberatan')) && in_array($alasan, old('alasan_keberatan'))) ? 'checked' : '' }}>
                                    </div>
                                    <div class="ml-3 text-sm leading-6">
                                        <label for="alasan_{{ $index }}"
                                            class="font-medium text-gray-700 cursor-pointer">{{ $alasan }}</label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        @error('alasan_keberatan') <p class="text-red-500 text-sm mt-3 font-medium">{{ $message }}</p> @enderror
                    </div>

                    {{-- CARD 4: KASUS POSISI --}}
                    <div class="bg-white rounded-3xl shadow-xl p-8 border border-gray-100 transition-all hover:shadow-2xl">
                        <div class="flex items-center gap-4 mb-6 pb-4 border-b border-gray-100">
                            {{-- UBAH: Teal -> Green --}}
                            <div class="w-12 h-12 bg-green-100 rounded-2xl flex items-center justify-center shadow-sm">
                                <i class="fas fa-pen-fancy text-green-700 text-xl"></i>
                            </div>
                            <div>
                                <h2 class="text-xl font-bold text-gray-800">Kasus Posisi</h2>
                                <p class="text-sm text-gray-500">Jelaskan secara singkat kasus/tujuan penggunaan informasi</p>
                            </div>
                        </div>
                        {{-- UBAH: Focus ring --}}
                        <textarea name="tujuan_penggunaan_informasi" rows="4"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 transition-all resize-none"
                            placeholder="Jelaskan kasus...">{{ old('tujuan_penggunaan_informasi') }}</textarea>
                    </div>

                    {{-- FOOTER / TOMBOL AKSI --}}
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
                                <a type="reset" href="{{ url()->current() }}"
                                    class="px-6 py-3 border-2 border-gray-300 text-gray-700 rounded-xl font-semibold hover:bg-gray-50 transition-all inline-flex items-center gap-2">
                                    <i class="fas fa-redo"></i>
                                    <span>Reset</span>
                                </a>
                                {{-- UBAH: Tombol Submit Gradient Hijau --}}
                                <button type="submit"
                                    class="px-8 py-3 bg-gradient-to-r from-green-700 to-emerald-600 text-white rounded-xl font-semibold hover:from-green-800 hover:to-emerald-700 hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-200 inline-flex items-center gap-2">
                                    <i class="fas fa-paper-plane"></i>
                                    <span>Kirim Keberatan</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            {{-- SCRIPT --}}
           <script>
            document.addEventListener('DOMContentLoaded', function () {

                // === LOGIKA TOGGLE KUASA ===
                const toggleKuasa = document.getElementById('toggleKuasa');
                const formKuasa = document.getElementById('formKuasa');

                function handleToggle() {
                    if (toggleKuasa.checked) {
                        formKuasa.classList.remove('hidden');
                    } else {
                        formKuasa.classList.add('hidden');
                    }
                }

                // Event Listener
                if(toggleKuasa) {
                    toggleKuasa.addEventListener('change', handleToggle);
                    // Cek old data (jika validasi gagal)
                    if ("{{ old('nama_kuasa') }}" != "") { 
                        toggleKuasa.checked = true; 
                        handleToggle(); 
                    }
                }

                // === LOGIKA PENCARIAN NIK & AUTOFILL ===
                const btnCekNik = document.getElementById('btn_cek_nik');
                const inputNik = document.getElementById('nik_pemohon');
                const searchStatus = document.getElementById('search_status');
                const selectRegistrasi = document.getElementById('permohonan_informasi_id');
                const wrapperRegistrasi = document.getElementById('wrapper_registrasi');

                // Field Identitas untuk Autofill
                const fieldNamaPemohon = document.getElementById('nama_pemohon');
                const fieldTelpPemohon = document.getElementById('telepon_pemohon');
                const fieldAlamat = document.getElementById('alamat_pemohon');

                // Pastikan tombol ada sebelum menambahkan event listener
                if(btnCekNik) {
                    btnCekNik.addEventListener('click', function () {
                        const nik = inputNik.value.trim();
                        const originalBtnText = btnCekNik.innerHTML;

                        // Loading State
                        btnCekNik.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Loading...';
                        btnCekNik.disabled = true;

                        // Reset State
                        searchStatus.classList.add('hidden');
                        searchStatus.className = 'text-xs mt-2 hidden';
                        selectRegistrasi.innerHTML = '<option value="">Sedang mencari...</option>';
                        selectRegistrasi.disabled = true;
                        selectRegistrasi.classList.add('bg-gray-200', 'cursor-not-allowed', 'text-gray-500');
                        selectRegistrasi.classList.remove('bg-white', 'text-gray-900');

                        if (nik.length < 16) {
                            searchStatus.textContent = 'NIK harus 16 digit.';
                            searchStatus.classList.remove('hidden');
                            searchStatus.classList.add('text-red-500');
                            selectRegistrasi.innerHTML = '<option value="">-- NIK tidak valid --</option>';
                            btnCekNik.innerHTML = originalBtnText;
                            btnCekNik.disabled = false;
                            return;
                        }

                        // API Call
                        fetch(`{{ route('ajax.get-permohonan') }}?nik=${nik}`)
                            .then(response => response.json())
                            .then(data => {
                                btnCekNik.innerHTML = originalBtnText;
                                btnCekNik.disabled = false;

                                if (data.error) { throw new Error(data.error); }

                                if (data.length > 0) {
                                    // DATA DITEMUKAN
                                    selectRegistrasi.innerHTML = '<option value="">-- Silakan Pilih Nomor Registrasi --</option>';

                                    data.forEach(item => {
                                        const option = document.createElement('option');
                                        option.value = item.id; // VALUE ADALAH ID (Relasi)
                                        option.textContent = `${item.no_registrasi} - ${item.informasi_diminta.substring(0, 40)}...`;
                                        selectRegistrasi.appendChild(option);
                                    });

                                    // Aktifkan Select
                                    selectRegistrasi.disabled = false;
                                    selectRegistrasi.classList.remove('bg-gray-200', 'cursor-not-allowed', 'opacity-60', 'text-gray-500');
                                    selectRegistrasi.classList.add('bg-white', 'text-gray-900');

                                    // Visual Cue Hijau
                                    wrapperRegistrasi.classList.remove('bg-gray-50', 'border-gray-200');
                                    wrapperRegistrasi.classList.add('bg-green-50', 'border-green-200');

                                    // Status Sukses
                                    searchStatus.innerHTML = `<i class="fas fa-check-circle"></i> Ditemukan ${data.length} permohonan. Silakan pilih nomor registrasi.`;
                                    searchStatus.classList.remove('hidden');
                                    searchStatus.classList.add('text-green-600', 'font-bold');

                                    // AUTO FILL IDENTITAS
                                    fieldNamaPemohon.value = data[0].nama_pemohon;
                                    fieldNamaPemohon.readOnly = true;
                                    fieldNamaPemohon.classList.add('bg-gray-100');

                                    fieldTelpPemohon.value = data[0].nomor_whatsapp || '';
                                    fieldAlamat.value = data[0].alamat_lengkap || '';

                                } else {
                                    // DATA KOSONG
                                    selectRegistrasi.innerHTML = '<option value="">-- Tidak ada riwayat permohonan untuk NIK ini --</option>';
                                    searchStatus.textContent = 'Data permohonan tidak ditemukan. Pastikan NIK sesuai dengan pengajuan sebelumnya.';
                                    searchStatus.classList.remove('hidden');
                                    searchStatus.classList.add('text-red-500');

                                    wrapperRegistrasi.classList.add('bg-gray-50', 'border-gray-200');
                                    wrapperRegistrasi.classList.remove('bg-green-50', 'border-green-200');

                                    // Reset field
                                    fieldNamaPemohon.value = '';
                                    fieldNamaPemohon.readOnly = false;
                                    fieldNamaPemohon.classList.remove('bg-gray-100');
                                    fieldTelpPemohon.value = '';
                                    fieldAlamat.value = '';
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                btnCekNik.innerHTML = originalBtnText;
                                btnCekNik.disabled = false;
                                selectRegistrasi.innerHTML = '<option value="">-- Error Terjadi --</option>';
                                searchStatus.textContent = 'Terjadi kesalahan saat mengambil data.';
                                searchStatus.classList.remove('hidden');
                                searchStatus.classList.add('text-red-500');
                            });
                    });
                }
            });
        </script>
@endsection