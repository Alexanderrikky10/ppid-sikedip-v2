<?php

namespace App\Filament\Resources\SurveyKualitasResource\Widgets;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Widgets\Widget;

class FilterKualitas extends Widget implements HasForms
{
    use InteractsWithForms;

    protected static string $view = 'filament.resources.survey-kualitas-resource.widgets.filter-kualitas';

    protected int|string|array $columnSpan = [
        'md' => 1,
        'xl' => 1,
        'sm' => 'full',
    ];

    protected static ?int $sort = 2;

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill([
            'tanggal_mulai' => null,
            'tanggal_selesai' => null,
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Filter Data')
                    ->description('Pilih rentang tanggal untuk memfilter data')
                    ->schema([
                        DatePicker::make('tanggal_mulai')
                            ->label('Tanggal Mulai')
                            ->placeholder('hh/bb/tttt')
                            ->native(false)
                            ->displayFormat('d/m/Y')
                            ->maxDate(fn($get) => $get('tanggal_selesai'))
                            ->reactive(),

                        DatePicker::make('tanggal_selesai')
                            ->label('Tanggal Selesai')
                            ->placeholder('hh/bb/tttt')
                            ->native(false)
                            ->displayFormat('d/m/Y')
                            ->minDate(fn($get) => $get('tanggal_mulai'))
                            ->reactive(),
                    ])
                    ->columns(1)
            ])
            ->statePath('data');
    }

    public function applyFilter(): void
    {
        $data = $this->form->getState();

        // Validasi tanggal
        if (!empty($data['tanggal_mulai']) && !empty($data['tanggal_selesai'])) {
            // Dispatch event ke semua widget lain
            $this->dispatch(
                'filterApplied',
                tanggal_mulai: $data['tanggal_mulai'],
                tanggal_selesai: $data['tanggal_selesai']
            );

            \Filament\Notifications\Notification::make()
                ->title('Filter diterapkan')
                ->body('Data telah difilter berdasarkan rentang tanggal yang dipilih')
                ->success()
                ->send();
        } else {
            \Filament\Notifications\Notification::make()
                ->title('Filter tidak lengkap')
                ->body('Silakan pilih tanggal mulai dan tanggal selesai')
                ->warning()
                ->send();
        }
    }

    public function resetFilter(): void
    {
        $this->form->fill([
            'tanggal_mulai' => null,
            'tanggal_selesai' => null,
        ]);

        // Dispatch event reset ke semua widget
        $this->dispatch('filterReset');

        \Filament\Notifications\Notification::make()
            ->title('Filter direset')
            ->body('Menampilkan semua data')
            ->success()
            ->send();
    }
}