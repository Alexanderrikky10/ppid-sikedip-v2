<x-filament-widgets::widget>
    <x-filament::section>

        {{-- Header --}}
        <x-slot name="heading">
            <div class="flex items-center gap-2">
                <x-heroicon-o-clipboard-document-list class="w-5 h-5 text-gray-500" />
                Activity log
            </div>
        </x-slot>

        <x-slot name="headerEnd">
            <x-filament::badge color="gray">
                Hari ini: {{ $this->getTodayCount() }} aktivitas
            </x-filament::badge>
        </x-slot>

        {{-- Stats Cards --}}
        <div class="grid grid-cols-2 gap-3 mb-4 sm:grid-cols-4">
            @php $stats = $this->getStats(); @endphp

            <div class="rounded-lg bg-gray-50 dark:bg-gray-800 p-3">
                <p class="text-xs text-gray-500 dark:text-gray-400">Total log</p>
                <p class="text-xl font-semibold text-gray-900 dark:text-white">
                    {{ number_format($stats['total']) }}
                </p>
            </div>

            <div class="rounded-lg bg-green-50 dark:bg-green-900/30 p-3">
                <p class="text-xs text-green-700 dark:text-green-400">Login hari ini</p>
                <p class="text-xl font-semibold text-green-800 dark:text-green-300">
                    {{ $stats['login'] }}
                </p>
            </div>

            <div class="rounded-lg bg-amber-50 dark:bg-amber-900/30 p-3">
                <p class="text-xs text-amber-700 dark:text-amber-400">Perubahan data</p>
                <p class="text-xl font-semibold text-amber-800 dark:text-amber-300">
                    {{ $stats['changes'] }}
                </p>
            </div>

            <div class="rounded-lg bg-red-50 dark:bg-red-900/30 p-3">
                <p class="text-xs text-red-700 dark:text-red-400">Hapus data</p>
                <p class="text-xl font-semibold text-red-800 dark:text-red-300">
                    {{ $stats['deleted'] }}
                </p>
            </div>
        </div>

        {{-- Filter Bar --}}
        <div class="flex flex-wrap gap-2 mb-4">
            <select wire:model.live="filterType"
                class="text-sm rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-700 dark:text-gray-300 px-3 py-1.5">
                <option value="all">Semua aktivitas</option>
                <option value="auth">Login / Logout</option>
                <option value="crud">CRUD data</option>
            </select>

            <select wire:model.live="filterPeriod"
                class="text-sm rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-700 dark:text-gray-300 px-3 py-1.5">
                <option value="today">Hari ini</option>
                <option value="7days">7 hari terakhir</option>
                <option value="30days">30 hari terakhir</option>
                <option value="all">Semua</option>
            </select>
        </div>

        {{-- Tabel Log --}}
        <div class="overflow-x-auto rounded-lg border border-gray-200 dark:border-gray-700">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 dark:bg-gray-800 text-left">
                        <th class="px-4 py-2.5 text-xs font-medium text-gray-500 uppercase tracking-wide">User</th>
                        <th class="px-4 py-2.5 text-xs font-medium text-gray-500 uppercase tracking-wide">Aktivitas</th>
                        <th class="px-4 py-2.5 text-xs font-medium text-gray-500 uppercase tracking-wide">Target</th>
                        <th class="px-4 py-2.5 text-xs font-medium text-gray-500 uppercase tracking-wide">Waktu</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    @forelse($this->getLogs() as $log)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">

                            {{-- User --}}
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-2">
                                    <div
                                        class="w-7 h-7 rounded-full bg-primary-100 dark:bg-primary-900 flex items-center justify-center text-xs font-medium text-primary-700 dark:text-primary-300 flex-shrink-0">
                                        {{ strtoupper(substr($log->causer?->name ?? 'S', 0, 2)) }}
                                    </div>
                                    <span class="font-medium text-gray-900 dark:text-white text-xs">
                                        {{ $log->causer?->name ?? 'System' }}
                                    </span>
                                </div>
                            </td>

                            {{-- Badge aktivitas --}}
                            <td class="px-4 py-3">
                                @php
                                    $color = match (true) {
                                        str_contains($log->description, 'login') => 'success',
                                        str_contains($log->description, 'logout') => 'warning',
                                        str_contains($log->description, 'created') => 'info',
                                        str_contains($log->description, 'updated') => 'warning',
                                        str_contains($log->description, 'deleted') => 'danger',
                                        default => 'gray',
                                    };
                                @endphp
                                <x-filament::badge :color="$color" size="sm">
                                    {{ $log->description }}
                                </x-filament::badge>
                            </td>

                            {{-- Target model --}}
                            <td class="px-4 py-3">
                                @if($log->subject_type)
                                    <span class="font-mono text-xs text-gray-500 dark:text-gray-400">
                                        {{ class_basename($log->subject_type) }} #{{ $log->subject_id }}
                                    </span>
                                @else
                                    <span class="text-gray-300 dark:text-gray-600">—</span>
                                @endif
                            </td>

                            {{-- Waktu --}}
                            <td class="px-4 py-3">
                                <span class="text-xs text-gray-500 dark:text-gray-400"
                                    title="{{ $log->created_at->format('d/m/Y H:i:s') }}">
                                    {{ $log->created_at->diffForHumans() }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-4 py-8 text-center text-sm text-gray-400">
                                Belum ada aktivitas tercatat
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Footer link --}}
        @if(auth()->user()->can('view-activity-log'))
            <div class="mt-3 text-right">
                <a href="{{ route('filament.admin.resources.activity-logs.index') }}"
                    class="text-xs text-primary-600 hover:text-primary-700 dark:text-primary-400">
                    Lihat semua log →
                </a>
            </div>
        @endif

    </x-filament::section>
</x-filament-widgets::widget>