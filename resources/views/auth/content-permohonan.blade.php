@extends('layout-auth.main-permohonan')

@section('title', 'Permohonan Akun Staff')

@section('content')

    {{-- Page heading --}}
    <div class="animate-fadeup delay-1" style="margin-bottom:32px;">
        <div
            style="display:inline-flex; align-items:center; gap:8px; background:#f0fdf4; border:1px solid #bbf7d0; border-radius:20px; padding:5px 14px; margin-bottom:14px;">
            <div
                style="width:6px; height:6px; border-radius:50%; background:#22c55e; box-shadow:0 0 6px rgba(34,197,94,0.5);">
            </div>
            <span
                style="font-size:12px; font-weight:600; color:#15803d; font-family:'Sora',sans-serif; letter-spacing:0.04em;">FORMULIR
                PERMOHONAN</span>
        </div>
        <h2
            style="font-family:'Sora',sans-serif; font-size:26px; font-weight:800; color:#0f172a; margin:0 0 8px; line-height:1.25;">
            Daftarkan Akun Staff Anda
        </h2>
        <p style="color:#64748b; font-size:14px; line-height:1.6; margin:0;">
            Lengkapi semua field di bawah ini. Akun akan aktif setelah diverifikasi oleh administrator sistem.
        </p>
    </div>

    {{-- ── SUCCESS ALERT ── --}}
    @if(session('success'))
        <div class="alert-success animate-fadeup delay-1">
            <div
                style="width:36px; height:36px; border-radius:10px; background:#16a34a; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                <svg width="18" height="18" fill="none" stroke="white" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                </svg>
            </div>
            <div>
                <p style="font-family:'Sora',sans-serif; font-weight:700; font-size:14px; color:#14532d; margin:0 0 3px;">
                    Permohonan Berhasil Dikirim!</p>
                <p style="font-size:13px; color:#166534; margin:0; line-height:1.5;">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    {{-- ── ERROR ALERT ── --}}
    @if($errors->any())
        <div class="alert-error animate-fadeup delay-1">
            <div
                style="width:36px; height:36px; border-radius:10px; background:#ef4444; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                <svg width="18" height="18" fill="none" stroke="white" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M12 9v4m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z" />
                </svg>
            </div>
            <div>
                <p style="font-family:'Sora',sans-serif; font-weight:700; font-size:14px; color:#7f1d1d; margin:0 0 3px;">
                    {{ $errors->count() }} Kesalahan Ditemukan
                </p>
                <p style="font-size:13px; color:#991b1b; margin:0;">Silakan periksa kembali isian formulir Anda.</p>
            </div>
        </div>
    @endif

    {{-- ════════════════════════════════
    FORM
    ════════════════════════════════ --}}
    <form action="{{ route('permohonan.store') }}" method="POST">
        @csrf

        {{-- ───────────────────────────────
        SECTION 1 : DATA DIRI
        ─────────────────────────────── --}}
        <div class="form-section animate-fadeup delay-2" style="margin-bottom:20px;">
            <div class="section-header">
                <div class="section-icon">
                    <svg width="16" height="16" fill="none" stroke="#16a34a" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </div>
                <div>
                    <p style="font-family:'Sora',sans-serif; font-weight:700; font-size:14px; color:#0f172a; margin:0;">Data
                        Diri</p>
                    <p style="font-size:12px; color:#94a3b8; margin:2px 0 0;">Identitas dan kredensial akun</p>
                </div>
                <div
                    style="margin-left:auto; background:#dcfce7; border:1px solid #86efac; border-radius:6px; padding:3px 10px;">
                    <span style="font-size:11px; font-weight:600; color:#15803d; font-family:'Sora',sans-serif;">01</span>
                </div>
            </div>

            <div style="padding:24px; display:grid; grid-template-columns:1fr 1fr; gap:18px;">

                {{-- Nama Lengkap --}}
                <div style="grid-column:1/-1;" class="form-field">
                    <label class="form-label" for="name">
                        Nama Lengkap
                        <span style="color:#ef4444;">*</span>
                    </label>
                    <div style="position:relative;">
                        <span class="field-icon" style="top:50%; transform:translateY(-50%);">
                            <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </span>
                        <input type="text" id="name" name="name" value="{{ old('name') }}"
                            class="form-input has-icon {{ $errors->has('name') ? 'has-error' : '' }}"
                            placeholder="Masukkan nama lengkap sesuai identitas">
                    </div>
                    @error('name')
                        <p class="form-error">
                            <svg width="12" height="12" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                    clip-rule="evenodd" />
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- NIP --}}
                <div class="form-field">
                    <label class="form-label" for="nip">NIP</label>
                    <div style="position:relative;">
                        <span class="field-icon" style="top:50%; transform:translateY(-50%);">
                            <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2" />
                            </svg>
                        </span>
                        <input type="text" id="nip" name="nip" value="{{ old('nip') }}"
                            class="form-input has-icon {{ $errors->has('nip') ? 'has-error' : '' }}"
                            placeholder="Nomor Induk Pegawai">
                    </div>
                    @error('nip')
                        <p class="form-error">
                            <svg width="12" height="12" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                    clip-rule="evenodd" />
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Email --}}
                <div class="form-field">
                    <label class="form-label" for="email">Email <span style="color:#ef4444;">*</span></label>
                    <div style="position:relative;">
                        <span class="field-icon" style="top:50%; transform:translateY(-50%);">
                            <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                        </span>
                        <input type="email" id="email" name="email" value="{{ old('email') }}"
                            class="form-input has-icon {{ $errors->has('email') ? 'has-error' : '' }}"
                            placeholder="nama@instansi.go.id">
                    </div>
                    @error('email')
                        <p class="form-error">
                            <svg width="12" height="12" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                    clip-rule="evenodd" />
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Password --}}
                <div class="form-field">
                    <label class="form-label" for="password">Password <span style="color:#ef4444;">*</span></label>
                    <div style="position:relative;">
                        <span class="field-icon" style="top:50%; transform:translateY(-50%);">
                            <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                        </span>
                        <input type="password" id="password" name="password"
                            class="form-input has-icon {{ $errors->has('password') ? 'has-error' : '' }}"
                            placeholder="Min. 8 karakter" style="padding-right:42px;">
                        <button type="button" class="pw-toggle" onclick="togglePw('password', this)">
                            <svg id="eye-password" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </button>
                    </div>
                    {{-- Password strength --}}
                    <div id="pw-strength" style="margin-top:6px; display:none;">
                        <div style="display:flex; gap:4px; margin-bottom:4px;">
                            <div id="bar1" style="height:3px; flex:1; border-radius:2px; background:#e5e7eb;"></div>
                            <div id="bar2" style="height:3px; flex:1; border-radius:2px; background:#e5e7eb;"></div>
                            <div id="bar3" style="height:3px; flex:1; border-radius:2px; background:#e5e7eb;"></div>
                            <div id="bar4" style="height:3px; flex:1; border-radius:2px; background:#e5e7eb;"></div>
                        </div>
                        <p id="pw-strength-label" style="font-size:11px; color:#9ca3af; margin:0;"></p>
                    </div>
                    @error('password')
                        <p class="form-error">
                            <svg width="12" height="12" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                    clip-rule="evenodd" />
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Konfirmasi Password --}}
                <div class="form-field">
                    <label class="form-label" for="password_confirmation">Konfirmasi Password <span
                            style="color:#ef4444;">*</span></label>
                    <div style="position:relative;">
                        <span class="field-icon" style="top:50%; transform:translateY(-50%);">
                            <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                        </span>
                        <input type="password" id="password_confirmation" name="password_confirmation"
                            class="form-input has-icon {{ $errors->has('password_confirmation') ? 'has-error' : '' }}"
                            placeholder="Ulangi password" style="padding-right:42px;">
                        <button type="button" class="pw-toggle" onclick="togglePw('password_confirmation', this)">
                            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </button>
                    </div>
                    <div id="pw-match" style="display:none; margin-top:5px;"></div>
                    @error('password_confirmation')
                        <p class="form-error">
                            <svg width="12" height="12" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                    clip-rule="evenodd" />
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

            </div>
        </div>

        {{-- ───────────────────────────────
        SECTION 2 : INFORMASI ORGANISASI
        ─────────────────────────────── --}}
        <div class="form-section animate-fadeup delay-3" style="margin-bottom:20px;">
            <div class="section-header">
                <div class="section-icon">
                    <svg width="16" height="16" fill="none" stroke="#16a34a" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                </div>
                <div>
                    <p style="font-family:'Sora',sans-serif; font-weight:700; font-size:14px; color:#0f172a; margin:0;">
                        Informasi Organisasi</p>
                    <p style="font-size:12px; color:#94a3b8; margin:2px 0 0;">Perangkat daerah dan wilayah kerja</p>
                </div>
                <div
                    style="margin-left:auto; background:#dcfce7; border:1px solid #86efac; border-radius:6px; padding:3px 10px;">
                    <span style="font-size:11px; font-weight:600; color:#15803d; font-family:'Sora',sans-serif;">02</span>
                </div>
            </div>

            <div style="padding:24px; display:grid; grid-template-columns:1fr 1fr; gap:18px;">

                {{-- Kategori Hak Akses --}}
                <div class="form-field">
                    <label class="form-label" for="hak_akses">
                        Kategori Hak Akses
                        <span style="color:#ef4444;">*</span>
                    </label>
                    <select id="hak_akses" name="hak_akses"
                        class="form-input {{ $errors->has('hak_akses') ? 'has-error' : '' }}"
                        onchange="filterPerangkatDaerah(this.value)">
                        <option value="">— Pilih Kategori —</option>
                        @foreach($kategoriList as $kategori)
                            <option value="{{ $kategori->id }}" {{ old('hak_akses') == $kategori->id ? 'selected' : '' }}>
                                {{ $kategori->nama_kategori }}
                            </option>
                        @endforeach
                    </select>
                    @error('hak_akses')
                        <p class="form-error">
                            <svg width="12" height="12" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                    clip-rule="evenodd" />
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Perangkat Daerah --}}
                <div class="form-field">
                    <label class="form-label" for="perangkat_daerah_id">
                        Perangkat Daerah
                        <span style="color:#ef4444;">*</span>
                    </label>
                    <select id="perangkat_daerah_id" name="perangkat_daerah_id"
                        class="form-input {{ $errors->has('perangkat_daerah_id') ? 'has-error' : '' }}">
                        <option value="">— Pilih Kategori Dulu —</option>
                        @foreach($perangkatDaerahList as $pd)
                            <option value="{{ $pd->id }}" data-kategori="{{ $pd->kategori_informasi_id }}" {{ old('perangkat_daerah_id') == $pd->id ? 'selected' : '' }} style="display:none;">
                                {{ $pd->nama_perangkat_daerah }}
                            </option>
                        @endforeach
                    </select>
                    @error('perangkat_daerah_id')
                        <p class="form-error">
                            <svg width="12" height="12" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                    clip-rule="evenodd" />
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Daerah --}}
                <div class="form-field">
                    <label class="form-label" for="daerah">Daerah <span style="color:#ef4444;">*</span></label>
                    <div style="position:relative;">
                        <span class="field-icon" style="top:50%; transform:translateY(-50%);">
                            <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </span>
                        <input type="text" id="daerah" name="daerah" value="{{ old('daerah') }}"
                            class="form-input has-icon {{ $errors->has('daerah') ? 'has-error' : '' }}"
                            placeholder="Contoh: Kabupaten Sintang">
                    </div>
                    @error('daerah')
                        <p class="form-error">
                            <svg width="12" height="12" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                    clip-rule="evenodd" />
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Biro --}}
                <div class="form-field">
                    <label class="form-label" for="biro">Biro / Bagian <span style="color:#ef4444;">*</span></label>
                    <div style="position:relative;">
                        <span class="field-icon" style="top:50%; transform:translateY(-50%);">
                            <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                        </span>
                        <input type="text" id="biro" name="biro" value="{{ old('biro') }}"
                            class="form-input has-icon {{ $errors->has('biro') ? 'has-error' : '' }}"
                            placeholder="Contoh: Biro Humas & Protokol">
                    </div>
                    @error('biro')
                        <p class="form-error">
                            <svg width="12" height="12" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                    clip-rule="evenodd" />
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

            </div>
        </div>

        {{-- ── INFO BADGE ── --}}
        <div class="info-badge animate-fadeup delay-4" style="margin-bottom:24px;">
            <svg width="18" height="18" fill="none" stroke="#16a34a" stroke-width="2" viewBox="0 0 24 24"
                style="flex-shrink:0; margin-top:1px;">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <p style="font-size:13px; color:#166534; line-height:1.6; margin:0;">
                Akun yang didaftarkan akan otomatis mendapatkan <strong>role Staff</strong>.
                Akun baru aktif setelah diverifikasi oleh administrator sistem PPID.
            </p>
        </div>

        {{-- ── SUBMIT ── --}}
        <div class="animate-fadeup delay-5">
            <button type="submit" class="btn-primary" id="submit-btn">
                <span id="btn-text"
                    style="display:flex; align-items:center; justify-content:center; gap:8px; position:relative; z-index:1;">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                    </svg>
                    Kirim Permohonan Akun
                </span>
            </button>

            {{-- Divider --}}
            <div style="display:flex; align-items:center; gap:12px; margin:20px 0;">
                <div style="flex:1; height:1px; background:#e5e7eb;"></div>
                <span style="font-size:12px; color:#94a3b8; white-space:nowrap;">atau</span>
                <div style="flex:1; height:1px; background:#e5e7eb;"></div>
            </div>

            <p style="text-align:center; font-size:14px; color:#64748b; margin:0;">
                Sudah memiliki akun?
                <a href="{{ route('login') }}"
                    style="color:#16a34a; font-weight:600; font-family:'Sora',sans-serif; text-decoration:none; border-bottom:1.5px solid rgba(22,163,74,0.3); padding-bottom:1px; transition:border-color 0.15s;"
                    onmouseover="this.style.borderColor='#16a34a'"
                    onmouseout="this.style.borderColor='rgba(22,163,74,0.3)'">
                    Masuk di sini →
                </a>
            </p>
        </div>

    </form>

@endsection

@push('scripts')
    <script>
        // ── Filter perangkat daerah by kategori ──
        function filterPerangkatDaerah(kategoriId) {
            const select = document.getElementById('perangkat_daerah_id');
            const opts = select.querySelectorAll('option[data-kategori]');
            const ph = select.querySelector('option[value=""]');

            select.value = '';
            opts.forEach(o => {
                o.style.display = o.dataset.kategori === kategoriId ? '' : 'none';
            });
            ph.textContent = kategoriId ? '— Pilih Perangkat Daerah —' : '— Pilih Kategori Dulu —';
        }

        // ── Toggle password visibility ──
        function togglePw(id, btn) {
            const inp = document.getElementById(id);
            const isPass = inp.type === 'password';
            inp.type = isPass ? 'text' : 'password';

            const showIcon = `<svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>`;
            const hideIcon = `<svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>`;
            btn.innerHTML = isPass ? hideIcon : showIcon;
        }

        // ── Password strength indicator ──
        document.getElementById('password').addEventListener('input', function () {
            const val = this.value;
            const bars = ['bar1', 'bar2', 'bar3', 'bar4'];
            const wrap = document.getElementById('pw-strength');

            if (!val) { wrap.style.display = 'none'; return; }
            wrap.style.display = 'block';

            let score = 0;
            if (val.length >= 8) score++;
            if (/[A-Z]/.test(val)) score++;
            if (/[0-9]/.test(val)) score++;
            if (/[^A-Za-z0-9]/.test(val)) score++;

            const colors = ['#ef4444', '#f97316', '#eab308', '#22c55e'];
            const labels = ['Sangat Lemah', 'Lemah', 'Cukup Kuat', 'Kuat'];

            bars.forEach((id, i) => {
                const el = document.getElementById(id);
                el.style.background = i < score ? colors[score - 1] : '#e5e7eb';
            });

            const lbl = document.getElementById('pw-strength-label');
            lbl.textContent = 'Kekuatan: ' + (labels[score - 1] || 'Sangat Lemah');
            lbl.style.color = colors[score - 1] || '#ef4444';
        });

        // ── Password match indicator ──
        document.getElementById('password_confirmation').addEventListener('input', function () {
            const pw = document.getElementById('password').value;
            const conf = this.value;
            const box = document.getElementById('pw-match');

            if (!conf) { box.style.display = 'none'; return; }
            box.style.display = 'block';

            if (pw === conf) {
                box.innerHTML = `<span style="font-size:11.5px; color:#16a34a; display:flex; align-items:center; gap:4px;">
                        <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                        Password cocok
                    </span>`;
            } else {
                box.innerHTML = `<span style="font-size:11.5px; color:#ef4444; display:flex; align-items:center; gap:4px;">
                        <svg width="12" height="12" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                        Password tidak cocok
                    </span>`;
            }
        });

        // ── Submit button loading state ──
        document.querySelector('form').addEventListener('submit', function () {
            const btn = document.getElementById('submit-btn');
            const txt = document.getElementById('btn-text');
            btn.disabled = true;
            btn.style.opacity = '0.8';
            txt.innerHTML = `
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="animation:spin 1s linear infinite;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                    Mengirim Permohonan...`;
            // Add spin keyframe
            if (!document.getElementById('spin-style')) {
                const s = document.createElement('style');
                s.id = 'spin-style';
                s.textContent = '@keyframes spin { to { transform: rotate(360deg); } }';
                document.head.appendChild(s);
            }
        });

        // ── Restore filter on validation error (old values) ──
        document.addEventListener('DOMContentLoaded', function () {
            const saved = '{{ old('hak_akses') }}';
            if (saved) {
                filterPerangkatDaerah(saved);
                document.getElementById('perangkat_daerah_id').value = '{{ old('perangkat_daerah_id') }}';
            }
        });
    </script>
@endpush