<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Permohonan Akun') — PPID</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@300;400;500;600;700;800&family=DM+Sans:ital,wght@0,300;0,400;0,500;1,300&display=swap" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        :root {
            --green-950: #052e16;
            --green-900: #14532d;
            --green-800: #166534;
            --green-700: #15803d;
            --green-600: #16a34a;
            --green-500: #22c55e;
            --green-400: #4ade80;
            --green-300: #86efac;
            --green-100: #dcfce7;
            --green-50:  #f0fdf4;
            --accent:    #a3e635; /* lime accent */
        }

        * { box-sizing: border-box; }

        body {
            font-family: 'DM Sans', sans-serif;
            background: #f8fafc;
            min-height: 100vh;
            margin: 0;
        }

        h1, h2, h3, .font-display {
            font-family: 'Sora', sans-serif;
        }

        /* ─── LEFT PANEL ─── */
        .left-panel {
            background: var(--green-950);
            position: relative;
            overflow: hidden;
        }

        .left-panel::before {
            content: '';
            position: absolute;
            inset: 0;
            background:
                radial-gradient(ellipse 80% 60% at 10% 20%, rgba(22,163,74,0.25) 0%, transparent 60%),
                radial-gradient(ellipse 60% 80% at 90% 80%, rgba(163,230,53,0.12) 0%, transparent 50%);
        }

        /* Geometric grid decoration */
        .geo-grid {
            position: absolute;
            inset: 0;
            background-image:
                linear-gradient(rgba(74,222,128,0.04) 1px, transparent 1px),
                linear-gradient(90deg, rgba(74,222,128,0.04) 1px, transparent 1px);
            background-size: 40px 40px;
        }

        /* Big circle ring */
        .ring-deco {
            position: absolute;
            border-radius: 50%;
            border: 1px solid rgba(74,222,128,0.1);
        }

        .ring-1 { width: 500px; height: 500px; top: -160px; right: -200px; }
        .ring-2 { width: 320px; height: 320px; bottom: -100px; left: -100px; }
        .ring-3 { width: 180px; height: 180px; bottom: 120px; right: 40px;
                  border-color: rgba(163,230,53,0.15); }

        /* Stat cards on left */
        .stat-card {
            background: rgba(255,255,255,0.04);
            border: 1px solid rgba(74,222,128,0.12);
            border-radius: 14px;
            backdrop-filter: blur(4px);
            padding: 14px 18px;
            display: flex;
            align-items: center;
            gap: 14px;
            transition: background 0.2s;
        }

        .stat-icon {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            background: rgba(74,222,128,0.12);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        /* ─── FORM STYLES ─── */
        .form-section {
            background: #ffffff;
            border-radius: 20px;
            border: 1px solid #e8f5e9;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0,0,0,0.04), 0 4px 16px rgba(0,0,0,0.03);
        }

        .section-header {
            padding: 18px 24px;
            border-bottom: 1px solid #f0fdf4;
            display: flex;
            align-items: center;
            gap: 12px;
            background: linear-gradient(135deg, #f0fdf4 0%, #ffffff 100%);
        }

        .section-icon {
            width: 34px;
            height: 34px;
            border-radius: 10px;
            background: var(--green-100);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .form-field { position: relative; }

        .form-label {
            display: block;
            font-size: 12.5px;
            font-weight: 600;
            color: #374151;
            margin-bottom: 6px;
            font-family: 'Sora', sans-serif;
            letter-spacing: 0.01em;
        }

        .form-input {
            width: 100%;
            padding: 11px 14px;
            border: 1.5px solid #e5e7eb;
            border-radius: 12px;
            font-size: 14px;
            font-family: 'DM Sans', sans-serif;
            color: #111827;
            background: #fafafa;
            outline: none;
            transition: all 0.2s ease;
        }

        .form-input:focus {
            border-color: var(--green-500);
            background: #ffffff;
            box-shadow: 0 0 0 3px rgba(34,197,94,0.1);
        }

        .form-input.has-error {
            border-color: #f87171;
            background: #fff5f5;
        }

        .form-input.has-error:focus {
            box-shadow: 0 0 0 3px rgba(248,113,113,0.12);
        }

        select.form-input {
            appearance: none;
            cursor: pointer;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%2316a34a' stroke-width='2'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' d='M19 9l-7 7-7-7'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 12px center;
            background-size: 16px;
            padding-right: 38px;
        }

        .form-error {
            display: flex;
            align-items: center;
            gap: 5px;
            font-size: 11.5px;
            color: #ef4444;
            margin-top: 5px;
        }

        .field-icon {
            position: absolute;
            left: 13px;
            top: 50%;
            transform: translateY(-50%);
            color: #9ca3af;
            pointer-events: none;
        }

        .form-input.has-icon { padding-left: 40px; }

        /* Toggle password btn */
        .pw-toggle {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
            color: #9ca3af;
            padding: 2px;
            transition: color 0.15s;
        }
        .pw-toggle:hover { color: var(--green-600); }

        /* Submit button */
        .btn-primary {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, var(--green-700) 0%, var(--green-600) 100%);
            color: white;
            font-family: 'Sora', sans-serif;
            font-weight: 600;
            font-size: 14px;
            border: none;
            border-radius: 14px;
            cursor: pointer;
            letter-spacing: 0.02em;
            transition: all 0.2s ease;
            box-shadow: 0 4px 14px rgba(22,163,74,0.35);
            position: relative;
            overflow: hidden;
        }

        .btn-primary::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(255,255,255,0.1) 0%, transparent 50%);
        }

        .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(22,163,74,0.45);
        }

        .btn-primary:active {
            transform: translateY(0);
        }

        /* Info badge */
        .info-badge {
            background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
            border: 1px solid #bbf7d0;
            border-radius: 12px;
            padding: 12px 16px;
            display: flex;
            align-items: flex-start;
            gap: 10px;
        }

        /* Alert */
        .alert-success {
            background: linear-gradient(135deg, #f0fdf4, #dcfce7);
            border: 1px solid #86efac;
            border-radius: 14px;
            padding: 16px;
            display: flex;
            gap: 12px;
            margin-bottom: 24px;
        }

        .alert-error {
            background: #fff5f5;
            border: 1px solid #fecaca;
            border-radius: 14px;
            padding: 16px;
            display: flex;
            gap: 12px;
            margin-bottom: 24px;
        }

        /* Step number */
        .step-num {
            width: 28px;
            height: 28px;
            border-radius: 8px;
            background: rgba(163,230,53,0.15);
            border: 1px solid rgba(163,230,53,0.3);
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Sora', sans-serif;
            font-size: 12px;
            font-weight: 700;
            color: #a3e635;
            flex-shrink: 0;
        }

        /* Scroll bar */
        .right-scroll::-webkit-scrollbar { width: 4px; }
        .right-scroll::-webkit-scrollbar-track { background: transparent; }
        .right-scroll::-webkit-scrollbar-thumb { background: #d1fae5; border-radius: 4px; }

        /* Fade in animation */
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(16px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .animate-fadeup {
            animation: fadeUp 0.45s ease forwards;
        }

        .delay-1 { animation-delay: 0.05s; opacity: 0; }
        .delay-2 { animation-delay: 0.12s; opacity: 0; }
        .delay-3 { animation-delay: 0.19s; opacity: 0; }
        .delay-4 { animation-delay: 0.26s; opacity: 0; }
        .delay-5 { animation-delay: 0.33s; opacity: 0; }

        /* Mobile top bar */
        .mobile-bar {
            background: var(--green-950);
            padding: 14px 20px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .logo-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: #a3e635;
            box-shadow: 0 0 8px rgba(163,230,53,0.6);
        }
    </style>

    @stack('styles')
</head>

<body>
<div style="min-height:100vh; display:flex;">

    {{-- ═══════════════════════════════════════════
         LEFT PANEL (hidden on mobile)
    ═══════════════════════════════════════════ --}}
    <div class="left-panel" style="display:none; width:420px; min-height:100vh; flex-direction:column; justify-content:space-between; padding:44px 40px; position:relative; flex-shrink:0;"
         id="left-panel">
        <div class="geo-grid"></div>
        <div class="ring-deco ring-1"></div>
        <div class="ring-deco ring-2"></div>
        <div class="ring-deco ring-3"></div>

        {{-- Logo --}}
        <div style="position:relative; z-index:10;">
            <div style="display:flex; align-items:center; gap:10px; margin-bottom:48px;">
                <div style="width:40px; height:40px; border-radius:12px; background:rgba(163,230,53,0.15); border:1px solid rgba(163,230,53,0.3); display:flex; align-items:center; justify-content:center;">
                    <svg width="20" height="20" fill="none" stroke="#a3e635" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                </div>
                <div>
                    <p style="color:white; font-family:'Sora',sans-serif; font-weight:700; font-size:16px; line-height:1.1;">PPID</p>
                    <p style="color:rgba(134,239,172,0.7); font-size:11px; margin-top:2px;">Sistem Informasi Publik</p>
                </div>
            </div>

            <div style="margin-bottom:16px;">
                <span style="font-size:11px; font-weight:600; letter-spacing:0.12em; color:rgba(163,230,53,0.7); text-transform:uppercase;">Permohonan Akun</span>
            </div>

            <h1 style="font-family:'Sora',sans-serif; font-size:32px; font-weight:800; color:white; line-height:1.2; margin:0 0 16px;">
                Bergabung &amp;<br>
                <span style="color:#a3e635;">Kelola Informasi</span>
            </h1>

            <p style="color:rgba(134,239,172,0.65); font-size:14px; line-height:1.7; max-width:280px;">
                Daftarkan akun staff untuk mengakses dan mengelola informasi publik sesuai perangkat daerah Anda.
            </p>

            {{-- Divider --}}
            <div style="height:1px; background:linear-gradient(90deg, rgba(74,222,128,0.2), transparent); margin:36px 0;"></div>

            {{-- Steps --}}
            <div style="display:flex; flex-direction:column; gap:18px;">
                @foreach([
                    ['Isi Formulir', 'Lengkapi data diri dan informasi organisasi Anda'],
                    ['Verifikasi Admin', 'Tim admin akan meninjau dan memverifikasi data'],
                    ['Akun Aktif', 'Login dan mulai kelola informasi publik'],
                ] as $i => $step)
                <div style="display:flex; align-items:flex-start; gap:14px;">
                    <div class="step-num">{{ $i + 1 }}</div>
                    <div>
                        <p style="color:white; font-family:'Sora',sans-serif; font-weight:600; font-size:13px; margin:0 0 3px;">{{ $step[0] }}</p>
                        <p style="color:rgba(134,239,172,0.55); font-size:12px; margin:0; line-height:1.5;">{{ $step[1] }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Bottom stat cards --}}
        <div style="position:relative; z-index:10; display:flex; flex-direction:column; gap:10px;">
            <div class="stat-card">
                <div class="stat-icon">
                    <svg width="16" height="16" fill="none" stroke="#4ade80" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
                <div>
                    <p style="color:white; font-family:'Sora',sans-serif; font-weight:600; font-size:13px; margin:0;">Akun Staff</p>
                    <p style="color:rgba(134,239,172,0.5); font-size:11px; margin:2px 0 0;">Diverifikasi oleh administrator</p>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon">
                    <svg width="16" height="16" fill="none" stroke="#4ade80" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                </div>
                <div>
                    <p style="color:white; font-family:'Sora',sans-serif; font-weight:600; font-size:13px; margin:0;">Data Aman & Terenkripsi</p>
                    <p style="color:rgba(134,239,172,0.5); font-size:11px; margin:2px 0 0;">Password diproteksi standar keamanan</p>
                </div>
            </div>

            <p style="color:rgba(134,239,172,0.3); font-size:11px; text-align:center; margin-top:8px;">© {{ date('Y') }} PPID. Hak cipta dilindungi.</p>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════
         RIGHT PANEL
    ═══════════════════════════════════════════ --}}
    <div style="flex:1; display:flex; flex-direction:column; min-height:100vh; background:#f8fafc;">

        {{-- Mobile top bar --}}
        <div class="mobile-bar" id="mobile-bar">
            <div style="width:36px; height:36px; border-radius:10px; background:rgba(163,230,53,0.15); border:1px solid rgba(163,230,53,0.25); display:flex; align-items:center; justify-content:center;">
                <svg width="18" height="18" fill="none" stroke="#a3e635" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                </svg>
            </div>
            <div>
                <div style="display:flex; align-items:center; gap:6px;">
                    <p style="color:white; font-family:'Sora',sans-serif; font-weight:700; font-size:14px; margin:0;">PPID</p>
                    <div class="logo-dot"></div>
                </div>
                <p style="color:rgba(134,239,172,0.6); font-size:11px; margin:0;">Permohonan Akun Staff</p>
            </div>
        </div>

        {{-- Scrollable area --}}
        <div class="right-scroll" style="flex:1; overflow-y:auto;">
            <div style="max-width:640px; margin:0 auto; padding:40px 24px 60px;">
                @yield('content')
            </div>
        </div>
    </div>

</div>

<script>
    // Show left panel only on large screens
    function checkLayout() {
        const lp = document.getElementById('left-panel');
        const mb = document.getElementById('mobile-bar');
        if (window.innerWidth >= 1024) {
            lp.style.display = 'flex';
            mb.style.display = 'none';
        } else {
            lp.style.display = 'none';
            mb.style.display = 'flex';
        }
    }
    checkLayout();
    window.addEventListener('resize', checkLayout);
</script>

@stack('scripts')
</body>
</html>