<x-filament-panels::page>
    @if($showForm)
        <form wire:submit="create">
            {{ $this->form }}
            <div class="mt-4 flex justify-end gap-x-3">
                <x-filament::button color="gray" wire:click="closeForm">
                    Batal
                </x-filament::button>
                <x-filament::button type="submit">
                    Simpan
                </x-filament::button>
            </div>
        </form>
    @else
        {{-- Bungkus dengan div agar Livewire tracking lebih stabil --}}
        <div wire:key="table-container">
            {{ $this->table }}
        </div>
    @endif
</x-filament-panels::page>