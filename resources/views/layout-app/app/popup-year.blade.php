{{--
COMPONENT: POPUP TAHUN MODERN
Trigger: $dispatch('open-year-modal')
--}}
<div x-data="{ open: false }" x-show="open" @open-year-modal.window="open = true" @keydown.escape.window="open = false"
    class="relative z-[100]" aria-labelledby="modal-title" role="dialog" aria-modal="true" style="display: none;">

    {{-- Backdrop dengan Blur Effect --}}
    <div x-show="open" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
        class="fixed inset-0 bg-gray-900/60 backdrop-blur-md transition-opacity" @click="open = false"></div>

    {{-- Modal Panel Wrapper --}}
    <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
        <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">

            <div x-show="open" x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-xl border border-gray-100">

                {{-- Decorative Header --}}
                <div class="h-2 bg-gradient-to-r from-green-500 to-green-600"></div>

                {{-- Modal Content --}}
                <div class="px-6 py-8 sm:p-8">
                    <div class="text-center">
                        {{-- Icon Circle --}}
                        <div
                            class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-green-50 ring-8 ring-green-50/50 mb-6">
                            <i class="fa-regular fa-calendar-check text-3xl text-green-600"></i>
                        </div>

                        {{-- Title & Desc --}}
                        <h3 class="text-2xl font-bold leading-tight text-gray-900 mb-2" id="modal-title">
                            Pilih Tahun Data
                        </h3>
                        <p class="text-sm text-gray-500 max-w-sm mx-auto mb-8">
                            Akses arsip Daftar Informasi Publik (DIP) <strong>Pemprov Kalimantan Barat</strong>
                            berdasarkan tahun terbit.
                        </p>

                        {{-- Modern Grid Layout --}}
                        <div
                            class="grid grid-cols-3 sm:grid-cols-4 gap-3 max-h-[300px] overflow-y-auto custom-scrollbar p-1">
                            @foreach(range(date('Y'), 2017) as $year)
                                {{-- Ganti '1' dengan ID Pemprov Kalbar --}}
                                <a href="{{ route('daftar-informasi.pemprov', ['tahun' => $year, 'opd' => 1]) }}"
                                    class="group relative flex flex-col items-center justify-center py-4 px-2 rounded-xl border border-gray-200 bg-white hover:border-green-500 hover:bg-green-50 transition-all duration-200 shadow-sm hover:shadow-md cursor-pointer text-decoration-none">

                                    <span
                                        class="text-lg font-bold text-gray-700 group-hover:text-green-700 transition-colors">
                                        {{ $year }}
                                    </span>

                                    {{-- Indikator Tahun Ini (Opsional) --}}
                                    @if($year == date('Y'))
                                        <span class="absolute top-2 right-2 flex h-2 w-2">
                                            <span
                                                class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                                            <span class="relative inline-flex rounded-full h-2 w-2 bg-green-500"></span>
                                        </span>
                                    @endif
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- Footer Action --}}
                <div class="bg-gray-50 px-6 py-4 flex justify-center sm:justify-end border-t border-gray-100">
                    <button type="button" @click="open = false"
                        class="inline-flex w-full justify-center rounded-lg bg-white px-5 py-2.5 text-sm font-semibold text-gray-700 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 hover:text-gray-900 sm:mt-0 sm:w-auto transition-colors">
                        Batal
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Custom Scrollbar Style (Opsional, agar scroll lebih rapi) --}}
<style>
    .custom-scrollbar::-webkit-scrollbar {
        width: 6px;
    }

    .custom-scrollbar::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 4px;
    }

    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: #d1d5db;
        border-radius: 4px;
    }

    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: #9ca3af;
    }
</style>