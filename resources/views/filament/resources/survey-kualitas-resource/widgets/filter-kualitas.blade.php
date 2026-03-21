<x-filament-widgets::widget>
    <x-filament::section>
        <form wire:submit="applyFilter">
            {{ $this->form }}

            <div class="mt-6 flex gap-2">
                <x-filament::button type="submit" color="warning" class="flex-1">
                    <x-filament::icon icon="heroicon-o-funnel" class="h-5 w-5 mr-1" />
                    Filter
                </x-filament::button>

                <x-filament::button type="button" color="gray" outlined class="flex-1" wire:click="resetFilter">
                    <x-filament::icon icon="heroicon-o-arrow-path" class="h-5 w-5 mr-1" />
                    Reset
                </x-filament::button>
            </div>

            @if($data['tanggal_mulai'] ?? false || $data['tanggal_selesai'] ?? false)
                <div
                    class="mt-4 p-3 bg-primary-50 dark:bg-primary-900/20 rounded-lg border border-primary-200 dark:border-primary-800">
                    <p class="text-xs font-semibold text-primary-600 dark:text-primary-400 mb-2">
                        Filter Aktif:
                    </p>
                    @if($data['tanggal_mulai'] ?? false)
                        <p class="text-sm text-gray-700 dark:text-gray-300">
                            Dari: {{ \Carbon\Carbon::parse($data['tanggal_mulai'])->format('d/m/Y H:i') }}
                        </p>
                    @endif
                    @if($data['tanggal_selesai'] ?? false)
                        <p class="text-sm text-gray-700 dark:text-gray-300">
                            Sampai: {{ \Carbon\Carbon::parse($data['tanggal_selesai'])->format('d/m/Y H:i') }}
                        </p>
                    @endif
                </div>
            @endif
        </form>
    </x-filament::section>
</x-filament-widgets::widget>