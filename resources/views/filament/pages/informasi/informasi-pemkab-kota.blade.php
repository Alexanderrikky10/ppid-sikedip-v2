<x-filament-panels::page>
    {{-- Kondisi: Jika showForm TRUE, tampilkan Form --}}
    @if($showForm)
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm ring-1 ring-gray-950/5 dark:ring-white/10 p-6 transition-all">
            <div class="mb-4 flex items-center justify-between">
                <h2 class="text-lg font-bold text-gray-800 dark:text-gray-100">Buat Informasi Baru</h2>
                
                {{-- Tombol Batal --}}
                <x-filament::button 
                    color="gray" 
                    size="sm" 
                    wire:click="closeForm"
                    icon="heroicon-o-x-mark"
                >
                    Batal
                </x-filament::button>
            </div>

            <form wire:submit="create">
                {{ $this->form }}

                <div class="mt-6 flex justify-end gap-3 border-t pt-4 dark:border-gray-700">
                    <x-filament::button 
                        type="button" 
                        color="gray" 
                        wire:click="closeForm"
                    >
                        Batal
                    </x-filament::button>

                    <x-filament::button 
                        type="submit" 
                        color="primary" 
                        icon="heroicon-o-check"
                    >
                        Simpan Informasi
                    </x-filament::button>
                </div>
            </form>
        </div>
    
    @else
    {{-- Kondisi: Jika showForm FALSE, tampilkan Tombol Tambah & List Data --}}
        
        <div class="flex justify-end mb-4">
            <x-filament::button 
                wire:click="openForm"
                color="primary"
                icon="heroicon-o-plus"
            >
                Tambah Informasi Pemkab/Kota
            </x-filament::button>
        </div>

        {{-- List Data --}}
        <div class="grid gap-4">
            @forelse($informasiList as $info)
                <div class="p-4 bg-white dark:bg-gray-800 rounded-lg shadow border border-gray-200 dark:border-gray-700">
                    <div class="flex justify-between items-start">
                        <div>
                            <h3 class="font-bold text-lg text-gray-900 dark:text-white">{{ $info->judul_informasi }}</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                {{ \Carbon\Carbon::parse($info->tanggal_publikasi)->translatedFormat('d F Y') }}
                                • {{ $info->perangkatDaerah->nama_perangkat_daerah ?? '-' }}
                            </p>
                            <p class="text-sm text-gray-600 dark:text-gray-300 mt-2 line-clamp-2">
                                {{ $info->ringkasan }}
                            </p>
                        </div>
                        <div class="flex gap-2">
                            <span class="px-2 py-1 text-xs rounded bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                {{ $info->klasifikasiInformasi->nama_klasifikasi ?? 'Umum' }}
                            </span>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center p-6 bg-gray-50 dark:bg-gray-900 rounded-lg border border-dashed border-gray-300 dark:border-gray-700">
                    <p class="text-gray-500 dark:text-gray-400">Belum ada data informasi Pemkab/Kota.</p>
                </div>
            @endforelse
        </div>

    @endif
</x-filament-panels::page>