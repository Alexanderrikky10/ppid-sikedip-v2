<x-filament-widgets::widget>
    <div class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800">
        <!-- Judul -->
        <h2 class="mb-4 text-center text-lg font-bold text-gray-700 md:text-xl lg:text-2xl dark:text-gray-300">
            TINGKAT KEPUASAN MASYARAKAT TERHADAP<br>AKSES DAN KUALITAS INFORMASI PUBLIK
        </h2>

        <!-- Periode -->
        <div class="text-center mb-2">
            <h3 class="text-2xl font-bold text-primary-600 md:text-3xl lg:text-4xl dark:text-primary-400">
                {{ $periodeText ?? 'Semua Periode' }}
            </h3>
        </div>

        <!-- Rentang Tanggal -->
        @if($tanggalMulaiFormatted && $tanggalSelesaiFormatted)
            <div class="flex items-center justify-center gap-2 mt-3">
                <div class="flex items-center gap-1 px-3 py-1 bg-gray-100 dark:bg-gray-700 rounded-md">
                    <x-filament::icon icon="heroicon-o-calendar" class="h-4 w-4 text-gray-500 dark:text-gray-400" />
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-300">
                        {{ $tanggalMulaiFormatted }}
                    </p>
                </div>

                <span class="text-gray-400 dark:text-gray-500">—</span>

                <div class="flex items-center gap-1 px-3 py-1 bg-gray-100 dark:bg-gray-700 rounded-md">
                    <x-filament::icon icon="heroicon-o-calendar" class="h-4 w-4 text-gray-500 dark:text-gray-400" />
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-300">
                        {{ $tanggalSelesaiFormatted }}
                    </p>
                </div>
            </div>

            <!-- Durasi -->
            <p class="text-center mt-2 text-xs text-gray-500 dark:text-gray-400">
                @php
                    $mulai = \Carbon\Carbon::parse($tanggalMulai);
                    $selesai = \Carbon\Carbon::parse($tanggalSelesai);
                    $durasi = $mulai->diffInDays($selesai) + 1;
                @endphp
                Durasi: {{ $durasi }} hari
            </p>
        @else
            <p class="text-center mt-2 text-sm text-gray-500 dark:text-gray-400">
                Menampilkan seluruh data yang tersedia
            </p>
        @endif
    </div>
</x-filament-widgets::widget>