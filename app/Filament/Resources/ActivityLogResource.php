<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ActivityLogResource\Pages;
use App\Models\ActivityLog;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

use Spatie\Activitylog\Models\Activity;
class ActivityLogResource extends Resource
{
    protected static ?string $model = ActivityLog::class;


    protected static ?int $navigationSort = 19;

    protected static ?string $navigationGroup = 'LAPORAN'; // Grouping Menu
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    public static function getModel(): string
    {
        return Activity::class;
    }
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('causer.name')
                    ->label('User')
                    ->searchable(),

                Tables\Columns\BadgeColumn::make('description')
                    ->label('Aktivitas')
                    ->colors([
                        'success' => fn($state) => str_contains($state, 'login'),
                        'warning' => fn($state) => str_contains($state, 'updated') || str_contains($state, 'logout'),
                        'danger' => fn($state) => str_contains($state, 'deleted'),
                        'info' => fn($state) => str_contains($state, 'created'),
                    ]),

                Tables\Columns\TextColumn::make('subject_type')
                    ->label('Model')
                    ->formatStateUsing(fn($state) => $state ? class_basename($state) : '—'),

                Tables\Columns\TextColumn::make('subject_id')
                    ->label('ID'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Waktu')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('log_name')
                    ->options(['auth' => 'Auth', 'default' => 'CRUD']),
            ])
            ->defaultSort('created_at', 'desc')
            ->paginated([10, 25, 50]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListActivityLogs::route('/'),
            'create' => Pages\CreateActivityLog::route('/create'),
            'view' => Pages\ViewActivityLog::route('/{record}'),
            'edit' => Pages\EditActivityLog::route('/{record}/edit'),
        ];
    }
}
