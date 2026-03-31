<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;
use Spatie\Activitylog\Models\Activity;
use Illuminate\Support\Carbon;

class ActivityLogWidget extends Widget
{
    protected static string $view = 'filament.widgets.activity-log-widget';

    protected int | string | array $columnSpan = 'full';

    protected static ?int $sort = 3;

    // Filter state (Livewire properties)
    public string $filterType = 'all';
    public string $filterPeriod = 'today';
    public int $perPage = 8;

    public function getStats(): array
    {
        $today = Carbon::today();

        return [
            'total'    => Activity::count(),
            'login'    => Activity::where('log_name', 'auth')
                            ->where('description', 'like', '%login%')
                            ->whereDate('created_at', $today)
                            ->count(),
            'changes'  => Activity::whereIn('description', ['updated', 'created'])
                            ->whereDate('created_at', $today)
                            ->count(),
            'deleted'  => Activity::where('description', 'deleted')
                            ->whereDate('created_at', $today)
                            ->count(),
        ];
    }

    public function getLogs()
    {
        $query = Activity::with('causer')->latest();

        // Filter periode
        $query->when($this->filterPeriod === 'today', fn($q) =>
            $q->whereDate('created_at', Carbon::today())
        )->when($this->filterPeriod === '7days', fn($q) =>
            $q->where('created_at', '>=', Carbon::now()->subDays(7))
        )->when($this->filterPeriod === '30days', fn($q) =>
            $q->where('created_at', '>=', Carbon::now()->subDays(30))
        );

        // Filter jenis aktivitas
        $query->when($this->filterType === 'auth', fn($q) =>
            $q->where('log_name', 'auth')
        )->when($this->filterType === 'crud', fn($q) =>
            $q->where('log_name', '!=', 'auth')
        );

        return $query->limit($this->perPage)->get();
    }

    public function getTodayCount(): int
    {
        return Activity::whereDate('created_at', Carbon::today())->count();
    }
}