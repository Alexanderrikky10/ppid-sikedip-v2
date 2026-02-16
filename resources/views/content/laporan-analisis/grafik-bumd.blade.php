@extends('layout-app.app.app')

@section('content')
    <div class="min-h-screen bg-[#f8fafc] relative overflow-hidden">

        {{-- HERO SECTION: Hijau Khas SIKEDIP (Untuk menopang Navbar Transparan) --}}
        <div class="absolute top-0 left-0 right-0 h-[450px] bg-gradient-to-br from-green-900 via-green-800 to-emerald-900">
            {{-- Ornamen Halus --}}
            <div class="absolute inset-0 opacity-10"
                style="background-image: url('https://www.transparenttextures.com/patterns/stardust.png');"></div>
            <div
                class="absolute top-[-10%] left-[-5%] w-[500px] h-[500px] bg-green-400/20 rounded-full blur-[120px] animate-pulse">
            </div>
            <div class="absolute bottom-0 right-0 w-[300px] h-[300px] bg-emerald-500/10 rounded-full blur-[80px]"></div>
        </div>

        {{-- KONTEN UTAMA --}}
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 pt-32 pb-20">

            {{-- HEADER: Title & Range Filter --}}
            <div class="flex flex-col xl:flex-row xl:items-end justify-between gap-8 mb-12">
                <div class="space-y-4">
                    <div
                        class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 border border-white/20 text-white text-xs font-bold uppercase tracking-widest backdrop-blur-md">
                        <span class="relative flex h-2 w-2">
                            <span
                                class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2 w-2 bg-green-400"></span>
                        </span>
                        Live Data Analisis
                    </div>
                    <h1 class="text-5xl font-black text-white leading-tight tracking-tight">
                        Statistik <span class="text-green-400">BUMD</span>
                    </h1>
                    <p class="text-green-100/80 text-lg max-w-2xl font-medium italic">
                        Perbandingan data informasi publik antar tahun dalam rentang waktu terpilih.
                    </p>
                </div>

                {{-- RANGE FILTER (Glassmorphism Hijau) --}}
                <div class="bg-white/10 backdrop-blur-lg p-6 rounded-[2.5rem] shadow-2xl border border-white/20">
                    <form action="{{ route('grafik-bumd') }}" method="GET" class="flex flex-wrap items-center gap-4">
                        <div class="flex flex-col gap-1">
                            <label class="text-[10px] font-black text-green-300 uppercase ml-2 tracking-tighter">Dari
                                Tahun</label>
                            <select name="dari_tahun"
                                class="rounded-xl border-none bg-green-800/40 text-white text-sm font-bold focus:ring-green-400 backdrop-blur-sm shadow-inner cursor-pointer">
                                @foreach(range(2020, date('Y')) as $year)
                                    <option class="text-slate-800" value="{{ $year }}" {{ $dariTahun == $year ? 'selected' : '' }}>{{ $year }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="pt-4 text-green-400/50">
                            <i class="fa-solid fa-arrows-left-right"></i>
                        </div>

                        <div class="flex flex-col gap-1">
                            <label class="text-[10px] font-black text-green-300 uppercase ml-2 tracking-tighter">Sampai
                                Tahun</label>
                            <select name="sampai_tahun"
                                class="rounded-xl border-none bg-green-800/40 text-white text-sm font-bold focus:ring-green-400 backdrop-blur-sm shadow-inner cursor-pointer">
                                @foreach(range(2020, date('Y')) as $year)
                                    <option class="text-slate-800" value="{{ $year }}" {{ $sampaiTahun == $year ? 'selected' : '' }}>{{ $year }}</option>
                                @endforeach
                            </select>
                        </div>

                        <button type="submit"
                            class="self-end px-6 py-2.5 bg-green-500 hover:bg-green-400 text-green-950 rounded-xl font-black transition-all duration-300 shadow-[0_10px_20px_rgba(34,197,94,0.4)] uppercase text-xs tracking-widest border border-green-300/30">
                            Update Data
                        </button>
                    </form>
                </div>
            </div>

            {{-- CHART BOX --}}
            <div class="bg-white rounded-[3rem] shadow-[0_20px_60px_rgba(0,0,0,0.08)] border border-green-50 p-8 md:p-12">
                <div class="flex flex-col md:flex-row items-center justify-between mb-12 gap-6">
                    <div class="flex items-center gap-4">
                        <div class="p-4 bg-green-600 rounded-2xl shadow-lg shadow-green-100">
                            <i class="fa-solid fa-chart-line text-white text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-2xl font-black text-slate-800 tracking-tight italic">Grafik Kontribusi OPD</h3>
                            <p class="text-sm text-green-600 font-bold uppercase tracking-widest opacity-70">BUMD Prov.
                                Kalbar</p>
                        </div>
                    </div>

                    {{-- Legend --}}
                    <div class="flex flex-wrap items-center justify-center gap-3">
                        @foreach($datasets as $dataset)
                            <div class="flex items-center gap-2 px-3 py-1.5 bg-green-50 rounded-full border border-green-100">
                                <span class="w-2.5 h-2.5 rounded-full"
                                    style="background-color: {{ $dataset['backgroundColor'] }}"></span>
                                <span
                                    class="text-[10px] font-black text-green-800 uppercase tracking-tighter">{{ $dataset['label'] }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="relative" style="height: {{ count($labels) * 55 + 200 }}px; min-height: 550px;">
                    <canvas id="bumdChart"></canvas>
                </div>
            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const ctx = document.getElementById('bumdChart').getContext('2d');
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: {!! json_encode($labels) !!},
                    datasets: {!! json_encode($datasets) !!}
                },
                options: {
                    indexAxis: 'y',
                    responsive: true,
                    maintainAspectRatio: false,
                    animation: { duration: 1500, easing: 'easeOutQuart' },
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            padding: 15,
                            cornerRadius: 12,
                            backgroundColor: '#064e3b', // Hijau sangat tua
                            titleFont: { weight: 'bold' },
                        }
                    },
                    scales: {
                        x: {
                            grid: { color: '#f0fdf4' },
                            ticks: { font: { weight: '700' }, color: '#10b981' }
                        },
                        y: {
                            grid: { display: false },
                            ticks: { color: '#064e3b', font: { size: 12, weight: '800' } }
                        }
                    }
                }
            });
        });
    </script>
@endsection