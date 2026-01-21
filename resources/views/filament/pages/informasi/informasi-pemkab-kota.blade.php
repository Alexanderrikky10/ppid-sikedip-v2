<x-filament-panels::page>
    @if($showForm)
        {{-- TAMPILAN FORM --}}
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
        {{-- TAMPILAN TABEL --}}
        {{ $this->table }}
    @endif
</x-filament-panels::page>