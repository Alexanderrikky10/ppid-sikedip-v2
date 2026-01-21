<x-filament-panels::page>
    <div class="space-y-6">
        {{-- Form Filter --}}
        <div class="bg-white rounded-lg shadow">
            <form wire:submit.prevent="submit">
                {{ $this->form }}
            </form>
        </div>

        {{-- Tabel Data --}}
        <div class="bg-white rounded-lg shadow">
            {{ $this->table }}
        </div>
    </div>

    {{-- Info Stats (Opsional) --}}
    @if($tahun_awal || $tahun_akhir)
        <div class="mt-4 grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <div class="text-sm text-blue-600 font-medium">Periode Tahun</div>
                <div class="text-2xl font-bold text-blue-900">
                    {{ $tahun_awal ?? 'Awal' }} - {{ $tahun_akhir ?? 'Akhir' }}
                </div>
            </div>
            <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                <div class="text-sm text-green-600 font-medium">Tempat Cetak</div>
                <div class="text-2xl font-bold text-green-900">
                    {{ $tempat ?? 'Belum diisi' }}
                </div>
            </div>
            <div class="bg-purple-50 border border-purple-200 rounded-lg p-4">
                <div class="text-sm text-purple-600 font-medium">Tanggal Cetak</div>
                <div class="text-2xl font-bold text-purple-900">
                    {{ $tanggal ? \Carbon\Carbon::parse($tanggal)->format('d/m/Y') : 'Belum diisi' }}
                </div>
            </div>
        </div>
    @endif
</x-filament-panels::page>