@extends('layout-app.layout-auth.main')

@section('content')
    <div class="relative min-h-screen flex items-center justify-center bg-slate-50 overflow-hidden font-sans p-10">

        <div class="absolute inset-0 pointer-events-none z-0">
            <div
                class="absolute w-[550px] h-[550px] -top-[200px] -left-[150px] bg-emerald-500/10 rounded-full blur-[80px] animate-pulse">
            </div>
            <div
                class="absolute w-[450px] h-[450px] -bottom-[150px] -right-[100px] bg-emerald-600/5 rounded-full blur-[80px] animate-bounce">
            </div>
            <div class="absolute inset-0"
                style="background-image: radial-gradient(#10b981 0.5px, transparent 0.5px); background-size: 30px 30px; opacity: 0.1;">
            </div>
        </div>

        <div class="relative z-10 w-full max-w-4xl flex flex-col items-center gap-8">

            {{-- Header - Centered --}}
            <div class="flex flex-col items-center text-center gap-2">
                <div
                    class="w-14 h-14 rounded-2xl bg-gradient-to-br from-emerald-500 to-emerald-700 flex items-center justify-center shadow-lg shadow-emerald-500/30">
                    <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4" />
                        <polyline points="10 17 15 12 10 7" />
                        <line x1="15" y1="12" x2="3" y2="12" />
                    </svg>
                </div>
                <h1 class="mt-4 text-3xl font-extrabold text-slate-800 tracking-tight leading-tight">Sistem Portal SIKEDIP
                </h1>
                <p class="text-slate-500 font-medium">Diskominfo Provinsi Kalimantan Barat</p>
            </div>

            {{-- Cards Grid --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 w-full">

                {{-- Card: Login Lokal --}}
                <div
                    class="group bg-white border border-slate-200 rounded-[2rem] p-8 transition-all duration-300 hover:-translate-y-2 hover:shadow-2xl hover:border-emerald-500 flex flex-col shadow-sm">
                    <span
                        class="inline-flex items-center gap-2 text-[10px] font-bold uppercase tracking-widest px-3 py-1 bg-emerald-50 text-emerald-600 rounded-full w-fit mb-5">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                            <circle cx="12" cy="7" r="4" />
                        </svg>
                        Akun Lokal
                    </span>
                    <h2 class="text-xl font-bold text-slate-900 mb-2">Masuk Akun</h2>
                    <p class="text-sm text-slate-500 mb-6 leading-relaxed">Gunakan kredensial terdaftar untuk akses internal
                        SIKEDIP.</p>

                    <form action="" method="POST" class="flex flex-col gap-4">
                        @csrf
                        <div>
                            <label class="text-xs font-bold text-slate-600 mb-1.5 block">Username / Email</label>
                            <div class="relative flex items-center">
                                <svg class="absolute left-4 text-slate-400 group-focus-within:text-emerald-500" width="16"
                                    height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                                    <circle cx="12" cy="7" r="4" />
                                </svg>
                                <input type="text" name="email" required placeholder="Email Anda"
                                    class="w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm outline-none focus:border-emerald-500 focus:bg-white focus:ring-4 focus:ring-emerald-500/10 transition-all">
                            </div>
                        </div>

                        <div>
                            <label class="text-xs font-bold text-slate-600 mb-1.5 block">Password</label>
                            <div class="relative flex items-center">
                                <svg class="absolute left-4 text-slate-400 group-focus-within:text-emerald-500" width="16"
                                    height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2" />
                                    <path d="M7 11V7a5 5 0 0 1 10 0v4" />
                                </svg>
                                <input type="password" id="lp-password" name="password" required placeholder="••••••••"
                                    class="w-full pl-11 pr-12 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm outline-none focus:border-emerald-500 focus:bg-white focus:ring-4 focus:ring-emerald-500/10 transition-all">
                                <button type="button" onclick="lpTogglePass()"
                                    class="absolute right-4 text-slate-400 hover:text-emerald-500 transition-colors">
                                    <svg id="lp-eye-icon" width="18" height="18" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="2">
                                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" />
                                        <circle cx="12" cy="12" r="3" />
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <button type="submit"
                            class="mt-2 flex items-center justify-center gap-2 w-full py-3 bg-emerald-500 hover:bg-emerald-600 text-white rounded-xl font-bold text-sm shadow-lg shadow-emerald-500/25 transition-all active:scale-95">
                            Masuk Sekarang
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="5" y1="12" x2="19" y2="12" />
                                <polyline points="12 5 19 12 12 19" />
                            </svg>
                        </button>
                    </form>
                </div>

                {{-- Card: SSO --}}
                <div
                    class="group bg-white border border-slate-200 rounded-[2rem] p-8 transition-all duration-300 hover:-translate-y-2 hover:shadow-2xl hover:border-emerald-500 flex flex-col shadow-sm">
                    <span
                        class="inline-flex items-center gap-2 text-[10px] font-bold uppercase tracking-widest px-3 py-1 bg-slate-100 text-slate-600 rounded-full w-fit mb-5">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" />
                        </svg>
                        Single Sign-On
                    </span>
                    <h2 class="text-xl font-bold text-slate-900 mb-2">Masuk via SSO</h2>
                    <p class="text-sm text-slate-500 mb-6 leading-relaxed">Autentikasi satu pintu menggunakan akun resmi
                        Keycloak Diskominfo.</p>

                    <div class="flex flex-col gap-3 mb-auto">
                        <div class="flex items-center gap-3 text-sm text-slate-600">
                            <div class="w-1.5 h-1.5 rounded-full bg-emerald-500"></div> Login cepat & tanpa ribet
                        </div>
                        <div class="flex items-center gap-3 text-sm text-slate-600">
                            <div class="w-1.5 h-1.5 rounded-full bg-emerald-500"></div> Keamanan data berlapis
                        </div>
                        <div class="flex items-center gap-3 text-sm text-slate-600">
                            <div class="w-1.5 h-1.5 rounded-full bg-emerald-500"></div> Terintegrasi layanan Kalbar
                        </div>
                    </div>

                    <div class="mt-8">
                        <a href="{{ route('sso.redirect') }}"
                            class="flex items-center justify-center gap-3 w-full py-3.5 bg-white border-2 border-slate-100 hover:border-emerald-500 hover:bg-emerald-50 text-slate-700 hover:text-emerald-700 rounded-xl font-bold text-sm transition-all">
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
                        <p class="text-[10px] text-slate-400 text-center mt-3">* Pastikan akun SSO Anda sudah aktif</p>
                    </div>
                </div>

            </div>

            {{-- Contact Banner --}}
            <div
                class="w-full bg-emerald-50 border border-emerald-100 rounded-3xl p-5 flex flex-col sm:flex-row items-center gap-4 transition-all hover:bg-emerald-100/50">
                <div
                    class="w-11 h-11 rounded-xl bg-white text-emerald-600 flex items-center justify-center shadow-sm flex-shrink-0">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path
                            d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 13.5a19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 3.64 3h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L7.91 10.6a16 16 0 0 0 6 6l.94-.93a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 17.92z" />
                    </svg>
                </div>
                <div class="flex-1 text-center sm:text-left">
                    <span class="block text-sm font-bold text-emerald-900">Kendala Login? atau tidak memiliki Akun</span>
                    <span class="text-xs text-emerald-700">Hubungi Technical Support kami via WhatsApp</span>
                </div>
                <a href="https://wa.me/6281346699080" target="_blank"
                    class="px-5 py-2.5 bg-emerald-500 hover:bg-emerald-600 text-white text-xs font-bold rounded-lg transition-colors whitespace-nowrap">
                    Hubungi Admin
                </a>
            </div>

            {{-- Footer --}}
            <footer class="text-slate-400 text-xs flex items-center gap-2">
                <span>&copy; {{ date('Y') }} {{ config('app.name') }}</span>
                <span class="w-1 h-1 rounded-full bg-slate-300"></span>
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
                icon.innerHTML = '<path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/>';
            } else {
                inp.type = 'password';
                icon.innerHTML = '<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>';
            }
        }
    </script>
@endsection