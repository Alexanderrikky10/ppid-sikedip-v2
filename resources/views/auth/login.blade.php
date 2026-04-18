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

            {{-- Cards Grid --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 w-full">

                {{-- Card: Login Lokal --}}
                <div class="bg-white flex flex-col transition-all duration-300 hover:-translate-y-1"
                    style="border:1px solid #e2e8f0;border-radius:24px;padding:2.25rem;"
                    onmouseenter="this.style.boxShadow='0 20px 48px rgba(16,185,129,0.12)';this.style.borderColor='#10b981'"
                    onmouseleave="this.style.boxShadow='none';this.style.borderColor='#e2e8f0'">

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

                    <form action="" method="POST" class="flex flex-col gap-4">
                        @csrf

                        {{-- Email --}}
                        <div>
                            <label class="block font-bold" style="font-size:0.8rem;color:#475569;margin-bottom:0.5rem;">
                                Username / Email
                            </label>
                            <div class="relative flex items-center">
                                <svg class="absolute" style="left:14px;color:#94a3b8;" width="16" height="16"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                                    <circle cx="12" cy="7" r="4" />
                                </svg>
                                <input type="text" name="email" required placeholder="Email Anda"
                                    style="width:100%;padding:13px 14px 13px 42px;background:#f8fafc;border:1.5px solid #e2e8f0;border-radius:14px;font-size:0.9rem;outline:none;color:#0f172a;transition:all .2s;box-sizing:border-box;"
                                    onfocus="this.style.borderColor='#10b981';this.style.background='#fff';this.style.boxShadow='0 0 0 4px rgba(16,185,129,0.08)'"
                                    onblur="this.style.borderColor='#e2e8f0';this.style.background='#f8fafc';this.style.boxShadow='none'">
                            </div>
                        </div>

                        {{-- Password --}}
                        <div>
                            <label class="block font-bold" style="font-size:0.8rem;color:#475569;margin-bottom:0.5rem;">
                                Password
                            </label>
                            <div class="relative flex items-center">
                                <svg class="absolute" style="left:14px;color:#94a3b8;" width="16" height="16"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2" />
                                    <path d="M7 11V7a5 5 0 0 1 10 0v4" />
                                </svg>
                                <input type="password" id="lp-password" name="password" required placeholder="••••••••"
                                    style="width:100%;padding:13px 44px 13px 42px;background:#f8fafc;border:1.5px solid #e2e8f0;border-radius:14px;font-size:0.9rem;outline:none;color:#0f172a;transition:all .2s;box-sizing:border-box;"
                                    onfocus="this.style.borderColor='#10b981';this.style.background='#fff';this.style.boxShadow='0 0 0 4px rgba(16,185,129,0.08)'"
                                    onblur="this.style.borderColor='#e2e8f0';this.style.background='#f8fafc';this.style.boxShadow='none'">
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
                        </div>

                        {{-- Submit --}}
                        <button type="submit"
                            class="flex items-center justify-center gap-2 w-full font-bold transition-all active:scale-95"
                            style="margin-top:0.5rem;padding:14px;background:#10b981;border:none;color:#fff;border-radius:14px;font-size:0.95rem;cursor:pointer;box-shadow:0 4px 16px rgba(16,185,129,0.3);"
                            onmouseenter="this.style.background='#059669'" onmouseleave="this.style.background='#10b981'">
                            Masuk Sekarang
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="5" y1="12" x2="19" y2="12" />
                                <polyline points="12 5 19 12 12 19" />
                            </svg>
                        </button>
                    </form>
                </div>

                {{-- Card: SSO --}}
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

    <script>
        function lpTogglePass() {
            const inp = document.getElementById('lp-password');
            const icon = document.getElementById('lp-eye-icon');
            if (inp.type === 'password') {
                inp.type = 'text';
                icon.innerHTML =
                    '<path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/>';
            } else {
                inp.type = 'password';
                icon.innerHTML =
                    '<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>';
            }
        }
    </script>
@endsection