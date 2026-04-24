@extends('layout-auth.main')

@section('content')
    <div class="relative min-h-screen flex items-center justify-center overflow-hidden font-sans p-10"
        style="background:#f0fdf8;">

        {{-- Background blobs --}}
        <div class="absolute inset-0 pointer-events-none z-0">
            <div class="absolute rounded-full"
                style="width:500px;height:500px;top:-180px;left:-120px;background:rgba(16,185,129,0.08);filter:blur(60px);">
            </div>
            <div class="absolute rounded-full"
                style="width:400px;height:400px;bottom:-120px;right:-80px;background:rgba(5,150,105,0.05);filter:blur(60px);">
            </div>
            <div class="absolute inset-0"
                style="background-image:radial-gradient(#10b98120 1px,transparent 1px);background-size:28px 28px;"></div>
        </div>

        <div class="relative z-10 w-full max-w-4xl flex flex-col items-center gap-8">

            {{-- Header --}}
            <div class="flex flex-col items-center text-center gap-2">
                <div class="w-16 h-16 rounded-[20px] flex items-center justify-center"
                    style="background:linear-gradient(135deg,#10b981,#059669);box-shadow:0 8px 24px rgba(16,185,129,0.3);">
                    <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.2"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4" />
                        <polyline points="10 17 15 12 10 7" />
                        <line x1="15" y1="12" x2="3" y2="12" />
                    </svg>
                </div>
                <h1 class="mt-3 font-extrabold tracking-tight leading-tight" style="font-size:2rem;color:#0f172a;">
                    Sistem Portal SIKEDIP
                </h1>
                <p class="font-medium" style="font-size:1rem;color:#64748b;">Diskominfo Provinsi Kalimantan Barat</p>
            </div>

            {{-- ============================================ --}}
            {{-- GLOBAL ERROR ALERT (muncul jika ada error) --}}
            {{-- ============================================ --}}
            @if ($errors->any())
                <div id="global-alert" class="w-full flex items-start gap-3 animate-fade-in"
                    style="background:#fef2f2;border:1.5px solid #fecaca;border-radius:16px;padding:1rem 1.25rem;">
                    {{-- Icon --}}
                    <div class="flex-shrink-0 flex items-center justify-center"
                        style="width:36px;height:36px;background:#fee2e2;border-radius:10px;">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#ef4444" stroke-width="2.2"
                            stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10" />
                            <line x1="12" y1="8" x2="12" y2="12" />
                            <line x1="12" y1="16" x2="12.01" y2="16" />
                        </svg>
                    </div>
                    {{-- Messages --}}
                    <div class="flex-1">
                        <p class="font-bold" style="font-size:0.85rem;color:#b91c1c;margin-bottom:0.25rem;">
                            Terjadi Kesalahan
                        </p>
                        <ul style="margin:0;padding:0;list-style:none;">
                            @foreach ($errors->all() as $error)
                                <li style="font-size:0.82rem;color:#dc2626;">• {{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    {{-- Close Button --}}
                    <button onclick="document.getElementById('global-alert').remove()"
                        style="background:none;border:none;cursor:pointer;color:#f87171;padding:0;flex-shrink:0;"
                        onmouseenter="this.style.color='#ef4444'" onmouseleave="this.style.color='#f87171'">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                            <line x1="18" y1="6" x2="6" y2="18" />
                            <line x1="6" y1="6" x2="18" y2="18" />
                        </svg>
                    </button>
                </div>
            @endif

            {{-- Session Status (misal setelah logout) --}}
            @if (session('status'))
                <div class="w-full flex items-center gap-3"
                    style="background:#f0fdf4;border:1.5px solid #bbf7d0;border-radius:16px;padding:1rem 1.25rem;">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#10b981" stroke-width="2.2">
                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14" />
                        <polyline points="22 4 12 14.01 9 11.01" />
                    </svg>
                    <p style="font-size:0.85rem;color:#065f46;font-weight:600;">
                        {{ session('status') }}
                    </p>
                </div>
            @endif

            {{-- Cards Grid --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 w-full">

                {{-- ==================== --}}
                {{-- Card: Login Lokal --}}
                {{-- ==================== --}}
                <div class="bg-white flex flex-col transition-all duration-300 hover:-translate-y-1"
                    style="border:1px solid {{ $errors->any() ? '#fca5a5' : '#e2e8f0' }};border-radius:24px;padding:2.25rem;"
                    id="login-card"
                    onmouseenter="this.style.boxShadow='0 20px 48px rgba(16,185,129,0.12)';this.style.borderColor='{{ $errors->any() ? '#ef4444' : '#10b981' }}'"
                    onmouseleave="this.style.boxShadow='none';this.style.borderColor='{{ $errors->any() ? '#fca5a5' : '#e2e8f0' }}'">

                    <span class="inline-flex items-center gap-1.5 font-bold uppercase w-fit mb-6"
                        style="font-size:10px;letter-spacing:.1em;padding:5px 12px;background:#f0fdf4;color:#059669;border-radius:99px;">
                        <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2.5">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                            <circle cx="12" cy="7" r="4" />
                        </svg>
                        Akun Lokal
                    </span>

                    <h2 class="font-bold" style="font-size:1.35rem;color:#0f172a;margin-bottom:0.4rem;">Masuk Akun</h2>
                    <p style="font-size:0.9rem;color:#94a3b8;line-height:1.6;margin-bottom:1.75rem;">
                        Gunakan kredensial terdaftar untuk akses internal SIKEDIP.
                    </p>

                    <form action="{{ route('login') }}" method="POST" class="flex flex-col gap-4">
                        @csrf

                        {{-- ===== Field Email ===== --}}
                        <div>
                            <label class="block font-bold"
                                style="font-size:0.8rem;color:{{ $errors->has('email') ? '#dc2626' : '#475569' }};margin-bottom:0.5rem;">
                                Username / Email
                                @if ($errors->has('email'))
                                    <span style="color:#ef4444;font-weight:400;font-size:0.75rem;">
                                        — {{ $errors->first('email') }}
                                    </span>
                                @endif
                            </label>
                            <div class="relative flex items-center">
                                <svg class="absolute"
                                    style="left:14px;color:{{ $errors->has('email') ? '#ef4444' : '#94a3b8' }};" width="16"
                                    height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                                    <circle cx="12" cy="7" r="4" />
                                </svg>
                                <input type="text" name="email" required placeholder="Email Anda" value="{{ old('email') }}"
                                    id="input-email" style="width:100%;padding:13px 14px 13px 42px;
                                                   background:{{ $errors->has('email') ? '#fff5f5' : '#f8fafc' }};
                                                   border:1.5px solid {{ $errors->has('email') ? '#fca5a5' : '#e2e8f0' }};
                                                   border-radius:14px;font-size:0.9rem;outline:none;
                                                   color:#0f172a;transition:all .2s;box-sizing:border-box;"
                                    onfocus="this.style.borderColor='{{ $errors->has('email') ? '#ef4444' : '#10b981' }}';this.style.background='#fff';this.style.boxShadow='0 0 0 4px {{ $errors->has('email') ? 'rgba(239,68,68,0.08)' : 'rgba(16,185,129,0.08)' }}'"
                                    onblur="this.style.borderColor='{{ $errors->has('email') ? '#fca5a5' : '#e2e8f0' }}';this.style.background='{{ $errors->has('email') ? '#fff5f5' : '#f8fafc' }}';this.style.boxShadow='none'">
                            </div>
                            {{-- Pesan error inline email --}}
                            @error('email')
                                <p class="flex items-center gap-1 mt-1.5" style="font-size:0.75rem;color:#ef4444;">
                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2.5">
                                        <circle cx="12" cy="12" r="10" />
                                        <line x1="12" y1="8" x2="12" y2="12" />
                                        <line x1="12" y1="16" x2="12.01" y2="16" />
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        {{-- ===== Field Password ===== --}}
                        <div>
                            <label class="block font-bold"
                                style="font-size:0.8rem;color:{{ $errors->has('password') ? '#dc2626' : '#475569' }};margin-bottom:0.5rem;">
                                Password
                                @if ($errors->has('password'))
                                    <span style="color:#ef4444;font-weight:400;font-size:0.75rem;">
                                        — {{ $errors->first('password') }}
                                    </span>
                                @endif
                            </label>
                            <div class="relative flex items-center">
                                <svg class="absolute"
                                    style="left:14px;color:{{ $errors->has('password') ? '#ef4444' : '#94a3b8' }};"
                                    width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2">
                                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2" />
                                    <path d="M7 11V7a5 5 0 0 1 10 0v4" />
                                </svg>
                                <input type="password" id="lp-password" name="password" required placeholder="••••••••"
                                    style="width:100%;padding:13px 44px 13px 42px;
                                                   background:{{ $errors->has('password') ? '#fff5f5' : '#f8fafc' }};
                                                   border:1.5px solid {{ $errors->has('password') ? '#fca5a5' : '#e2e8f0' }};
                                                   border-radius:14px;font-size:0.9rem;outline:none;
                                                   color:#0f172a;transition:all .2s;box-sizing:border-box;"
                                    onfocus="this.style.borderColor='{{ $errors->has('password') ? '#ef4444' : '#10b981' }}';this.style.background='#fff';this.style.boxShadow='0 0 0 4px {{ $errors->has('password') ? 'rgba(239,68,68,0.08)' : 'rgba(16,185,129,0.08)' }}'"
                                    onblur="this.style.borderColor='{{ $errors->has('password') ? '#fca5a5' : '#e2e8f0' }}';this.style.background='{{ $errors->has('password') ? '#fff5f5' : '#f8fafc' }}';this.style.boxShadow='none'">
                                <button type="button" onclick="lpTogglePass()" class="absolute transition-colors"
                                    style="right:14px;background:none;border:none;cursor:pointer;color:#94a3b8;padding:0;display:flex;align-items:center;"
                                    onmouseenter="this.style.color='#10b981'" onmouseleave="this.style.color='#94a3b8'">
                                    <svg id="lp-eye-icon" width="18" height="18" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="2">
                                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" />
                                        <circle cx="12" cy="12" r="3" />
                                    </svg>
                                </button>
                            </div>
                            {{-- Pesan error inline password --}}
                            @error('password')
                                <p class="flex items-center gap-1 mt-1.5" style="font-size:0.75rem;color:#ef4444;">
                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2.5">
                                        <circle cx="12" cy="12" r="10" />
                                        <line x1="12" y1="8" x2="12" y2="12" />
                                        <line x1="12" y1="16" x2="12.01" y2="16" />
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        {{-- ===== Attempt Error Banner (Email/Password Salah) ===== --}}
                        @if ($errors->has('email') && !$errors->has('password'))
                            <div class="flex items-center gap-3"
                                style="background:#fef2f2;border:1px solid #fecaca;border-radius:12px;padding:0.85rem 1rem;">
                                <div class="flex-shrink-0"
                                    style="width:32px;height:32px;background:#fee2e2;border-radius:8px;display:flex;align-items:center;justify-content:center;">
                                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#ef4444"
                                        stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                        <path
                                            d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z" />
                                        <line x1="12" y1="9" x2="12" y2="13" />
                                        <line x1="12" y1="17" x2="12.01" y2="17" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-bold" style="font-size:0.8rem;color:#b91c1c;margin-bottom:2px;">
                                        Login Gagal
                                    </p>
                                    <p style="font-size:0.78rem;color:#dc2626;">
                                        {{ $errors->first('email') }}
                                    </p>
                                </div>
                            </div>
                        @endif

                        {{-- ===== Submit Button ===== --}}
                        <button type="submit"
                            class="flex items-center justify-center gap-2 w-full font-bold transition-all active:scale-95"
                            style="margin-top:0.5rem;padding:14px;
                                           background:{{ $errors->any() ? '#ef4444' : '#10b981' }};
                                           border:none;color:#fff;border-radius:14px;font-size:0.95rem;cursor:pointer;
                                           box-shadow:0 4px 16px {{ $errors->any() ? 'rgba(239,68,68,0.3)' : 'rgba(16,185,129,0.3)' }};"
                            id="submit-btn"
                            onmouseenter="this.style.background='{{ $errors->any() ? '#dc2626' : '#059669' }}'"
                            onmouseleave="this.style.background='{{ $errors->any() ? '#ef4444' : '#10b981' }}'">
                            {{ $errors->any() ? 'Coba Lagi' : 'Masuk Sekarang' }}
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                @if ($errors->any())
                                    {{-- Icon refresh jika error --}}
                                    <polyline points="1 4 1 10 7 10" />
                                    <path d="M3.51 15a9 9 0 1 0 .49-3.5" />
                                @else
                                    {{-- Icon arrow normal --}}
                                    <line x1="5" y1="12" x2="19" y2="12" />
                                    <polyline points="12 5 19 12 12 19" />
                                @endif
                            </svg>
                        </button>

                    </form>
                </div>

                {{-- ==================== --}}
                {{-- Card: SSO --}}
                {{-- ==================== --}}
                <div class="bg-white flex flex-col transition-all duration-300 hover:-translate-y-1"
                    style="border:1px solid #e2e8f0;border-radius:24px;padding:2.25rem;"
                    onmouseenter="this.style.boxShadow='0 20px 48px rgba(16,185,129,0.12)';this.style.borderColor='#10b981'"
                    onmouseleave="this.style.boxShadow='none';this.style.borderColor='#e2e8f0'">

                    <span class="inline-flex items-center gap-1.5 font-bold uppercase w-fit mb-6"
                        style="font-size:10px;letter-spacing:.1em;padding:5px 12px;background:#f8fafc;color:#64748b;border-radius:99px;">
                        <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2.5">
                            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" />
                        </svg>
                        Single Sign-On
                    </span>

                    <h2 class="font-bold" style="font-size:1.35rem;color:#0f172a;margin-bottom:0.4rem;">Masuk via SSO</h2>
                    <p style="font-size:0.9rem;color:#94a3b8;line-height:1.6;margin-bottom:1.75rem;">
                        Autentikasi satu pintu menggunakan akun resmi Keycloak Diskominfo.
                    </p>

                    <div class="flex flex-col gap-3 mb-auto">
                        <div class="flex items-center gap-3" style="font-size:0.9rem;color:#475569;">
                            <span class="rounded-full flex-shrink-0"
                                style="width:8px;height:8px;background:#10b981;"></span>
                            Login cepat & tanpa ribet
                        </div>
                        <div class="flex items-center gap-3" style="font-size:0.9rem;color:#475569;">
                            <span class="rounded-full flex-shrink-0"
                                style="width:8px;height:8px;background:#10b981;"></span>
                            Keamanan data berlapis
                        </div>
                        <div class="flex items-center gap-3" style="font-size:0.9rem;color:#475569;">
                            <span class="rounded-full flex-shrink-0"
                                style="width:8px;height:8px;background:#10b981;"></span>
                            Terintegrasi layanan Kalbar
                        </div>
                    </div>

                    <div class="mt-8">
                        <a href="{{ route('sso.redirect') }}"
                            class="flex items-center justify-center gap-3 w-full font-bold transition-all"
                            style="padding:14px;background:#fff;border:1.5px solid #e2e8f0;color:#334155;border-radius:14px;font-size:0.95rem;text-decoration:none;box-sizing:border-box;"
                            onmouseenter="this.style.borderColor='#10b981';this.style.background='#f0fdf4';this.style.color='#059669'"
                            onmouseleave="this.style.borderColor='#e2e8f0';this.style.background='#fff';this.style.color='#334155'">
                            <img src="https://www.vectorlogo.zone/logos/keycloak/keycloak-icon.svg" class="w-5 h-5"
                                alt="Keycloak">
                            Masuk dengan Keycloak
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2">
                                <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6" />
                                <polyline points="15 3 21 3 21 9" />
                                <line x1="10" y1="14" x2="21" y2="3" />
                            </svg>
                        </a>
                        <p class="text-center mt-3" style="font-size:0.7rem;color:#94a3b8;">
                            * Pastikan akun SSO Anda sudah aktif
                        </p>
                    </div>
                </div>

            </div>

            {{-- Contact Banner --}}
            <div class="w-full flex flex-col sm:flex-row items-center gap-4 transition-all"
                style="background:#f0fdf4;border:1px solid #d1fae5;border-radius:20px;padding:1.25rem 1.5rem;"
                onmouseenter="this.style.background='#ecfdf5'" onmouseleave="this.style.background='#f0fdf4'">
                <div class="flex items-center justify-center flex-shrink-0"
                    style="width:46px;height:46px;border-radius:14px;background:#fff;border:1px solid #d1fae5;">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#10b981" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path
                            d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 13.5a19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 3.64 3h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L7.91 10.6a16 16 0 0 0 6 6l.94-.93a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 17.92z" />
                    </svg>
                </div>
                <div class="flex-1 text-center sm:text-left">
                    <span class="block font-bold" style="font-size:0.9rem;color:#065f46;">
                        Kendala Login? atau tidak memiliki Akun
                    </span>
                    <span style="font-size:0.8rem;color:#047857;">
                        Hubungi Technical Support kami via WhatsApp
                    </span>
                </div>
                <a href="https://wa.me/6281346699080" target="_blank" class="font-bold whitespace-nowrap transition-colors"
                    style="padding:10px 20px;background:#10b981;color:#fff;font-size:0.82rem;border-radius:12px;text-decoration:none;"
                    onmouseenter="this.style.background='#059669'" onmouseleave="this.style.background='#10b981'">
                    Hubungi Admin
                </a>
            </div>

            {{-- Footer --}}
            <footer class="flex items-center gap-2" style="color:#94a3b8;font-size:0.78rem;">
                <span>&copy; {{ date('Y') }} {{ config('app.name') }}</span>
                <span class="rounded-full" style="width:4px;height:4px;background:#cbd5e1;"></span>
                <span>Diskominfo Provinsi Kalimantan Barat</span>
            </footer>

        </div>
    </div>

    {{-- ==================== --}}
    {{-- Auto dismiss alert --}}
    {{-- ==================== --}}
    <style>
        @keyframes fadeIn {
            @keyframes fadeIn {
                from {
                    opacity: 0;
                    transform: translateY(-8px);
                }

                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            .animate-fade-in {
                animation: fadeIn 0.4s ease forwards;
            }

            @keyframes shake {

                0%,
                100% {
                    transform: translateX(0);
                }

                20% {
                    transform: translateX(-8px);
                }

                40% {
                    transform: translateX(8px);
                }

                60% {
                    transform: translateX(-5px);
                }

                80% {
                    transform: translateX(5px);
                }
            }

            .shake {
                animation: shake 0.5s ease;
            }
    </style>

    <script>
        // ===== Toggle Password Visibility =====
        function lpTogglePass() {
            const inp = document.getElementById('lp-password');
            const icon = document.getElementById('lp-eye-icon');

            if (inp.type === 'password') {
                inp.type = 'text';
                icon.innerHTML = `
                                <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8
                                         a18.45 18.45 0 0 1 5.06-5.94
                                         M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8
                                         a18.5 18.5 0 0 1-2.16 3.19
                                         m-6.72-1.07a3 3 0 1 1-4.24-4.24"/>
                                <line x1="1" y1="1" x2="23" y2="23"/>
                            `;
            } else {
                inp.type = 'password';
                icon.innerHTML = `
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                <circle cx="12" cy="12" r="3"/>
                            `;
            }
        }

        // ===== Auto Dismiss Global Alert setelah 5 detik =====
        document.addEventListener('DOMContentLoaded', function () {
            const alert = document.getElementById('global-alert');

            if (alert) {
                // Shake animasi pada login card jika ada error
                const loginCard = document.getElementById('login-card');
                if (loginCard) {
                    loginCard.classList.add('shake');
                    loginCard.addEventListener('animationend', () => {
                        loginCard.classList.remove('shake');
                    });
                }

                // Progress bar auto dismiss
                let timeLeft = 5000;
                const interval = 50;
                const bar = document.getElementById('alert-progress');

                const timer = setInterval(() => {
                    timeLeft -= interval;
                    if (bar) {
                        bar.style.width = (timeLeft / 5000 * 100) + '%';
                    }
                    if (timeLeft <= 0) {
                        clearInterval(timer);
                        dismissAlert();
                    }
                }, interval);
            }
        });

        // ===== Dismiss Alert dengan animasi =====
        function dismissAlert() {
            const alert = document.getElementById('global-alert');
            if (alert) {
                alert.style.transition = 'opacity 0.4s ease, transform 0.4s ease';
                alert.style.opacity = '0';
                alert.style.transform = 'translateY(-8px)';
                setTimeout(() => alert.remove(), 400);
            }
        }

        // ===== Loading state pada tombol submit =====
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.querySelector('form');
            const btn = document.getElementById('submit-btn');

            if (form && btn) {
                form.addEventListener('submit', function () {
                    // Validasi sederhana sebelum loading
                    const email = document.querySelector('input[name="email"]').value.trim();
                    const password = document.querySelector('input[name="password"]').value.trim();

                    if (!email || !password) return;

                    // Set loading state
                    btn.disabled = true;
                    btn.style.opacity = '0.8';
                    btn.style.cursor = 'not-allowed';
                    btn.innerHTML = `
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none"
                                         stroke="currentColor" stroke-width="2.5"
                                         style="animation: spin 1s linear infinite;">
                                        <path d="M21 12a9 9 0 1 1-6.219-8.56"/>
                                    </svg>
                                    Memproses...
                                `;
                });
            }
        });
    </script>

    {{-- ===== Spin Keyframe untuk loading ===== --}}
    <style>
        @keyframes spin {
            from {
                transform: rotate(0deg);
            }

            to {
                transform: rotate(360deg);
            }
        }
    </style>

@endsection