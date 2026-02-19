<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SurveyKualitasResource\Pages;
use App\Models\SurveyKualitas;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class SurveyKualitasResource extends Resource
{
    protected static ?string $model = SurveyKualitas::class;
    protected static ?string $navigationGroup = 'MANAJEMEN SURVEY';

    protected static ?string $navigationLabel = 'Data Jawaban Responden';

    protected static ?string $modelLabel = 'Data Jawaban Responden';

    protected static ?string $pluralModelLabel = 'Data Jawaban Responden';

    // protected static ?int $navigationSort = 15;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make('Buat Pertanyaan Survey Kepuasan')
                            ->description('Buat pertanyaan untuk mengukur kepuasan layanan SIKEDIP')
                            ->icon('heroicon-o-clipboard-document-list')
                            ->schema([
                                Forms\Components\Textarea::make('pertanyaan')
                                    ->label('Pertanyaan Survey')
                                    ->required()
                                    ->rows(4)
                                    ->placeholder('Contoh: Bagaimana penilaian Anda terhadap tampilan website SIKEDIP?')
                                    ->helperText('Tulis pertanyaan yang jelas dan mudah dipahami responden')
                                    ->columnSpanFull(),

                                Forms\Components\Placeholder::make('preview_jawaban')
                                    ->label('Preview Skala Penilaian')
                                    ->content(function () {
                                        return new \Illuminate\Support\HtmlString('
                                            <div class="space-y-2 p-4 bg-gray-50 dark:bg-gray-800 rounded-lg">
                                                <p class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">
                                                    Responden akan menjawab dengan skala berikut:
                                                </p>
                                                <div class="space-y-2">
                                                    <div class="flex items-center gap-2">
                                                        <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-green-500 text-white text-xs font-bold">5</span>
                                                        <span class="text-sm">Sangat Setuju</span>
                                                    </div>
                                                    <div class="flex items-center gap-2">
                                                        <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-blue-500 text-white text-xs font-bold">4</span>
                                                        <span class="text-sm">Setuju</span>
                                                    </div>
                                                    <div class="flex items-center gap-2">
                                                        <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-yellow-500 text-white text-xs font-bold">3</span>
                                                        <span class="text-sm">Cukup</span>
                                                    </div>
                                                    <div class="flex items-center gap-2">
                                                        <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-orange-500 text-white text-xs font-bold">2</span>
                                                        <span class="text-sm">Tidak Setuju</span>
                                                    </div>
                                                    <div class="flex items-center gap-2">
                                                        <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-red-500 text-white text-xs font-bold">1</span>
                                                        <span class="text-sm">Sangat Tidak Setuju</span>
                                                    </div>
                                                </div>
                                            </div>
                                        ');
                                    })
                                    ->columnSpanFull(),
                            ])
                            ->columns(1)
                            ->collapsible(),
                    ])
                    ->columnSpan(['lg' => 3]),
            ])
            ->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('pertanyaan')
                    ->label('Pertanyaan Survey')
                    ->wrap()
                    ->sortable()
                    ->searchable()
                    ->toggleable(),


                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal Dibuat')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\Filter::make('created_at')
                    ->form([
                        Forms\Components\DatePicker::make('created_from')
                            ->label('Dari Tanggal')
                            ->native(false),
                        Forms\Components\DatePicker::make('created_until')
                            ->label('Sampai Tanggal')
                            ->native(false),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'],
                                fn(Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['created_until'],
                                fn(Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    }),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make()
                        ->icon('heroicon-o-eye')
                        ->color('info'),

                    Tables\Actions\EditAction::make()
                        ->icon('heroicon-o-pencil')
                        ->color('warning')
                        ->form([
                            Forms\Components\Textarea::make('pertanyaan')
                                ->label('Pertanyaan Survey')
                                ->required()
                                ->rows(4)
                                ->placeholder('Contoh: Bagaimana penilaian Anda terhadap tampilan website SIKEDIP?')
                                ->helperText('Tulis pertanyaan yang jelas dan mudah dipahami responden'),

                            Forms\Components\Placeholder::make('preview_jawaban')
                                ->label('Preview Skala Penilaian')
                                ->content(function () {
                                    return new \Illuminate\Support\HtmlString('
                                        <div class="space-y-2 p-4 bg-gray-50 dark:bg-gray-800 rounded-lg">
                                            <p class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">
                                                Responden akan menjawab dengan skala berikut:
                                            </p>
                                            <div class="space-y-2">
                                                <div class="flex items-center gap-2">
                                                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-green-500 text-white text-xs font-bold">5</span>
                                                    <span class="text-sm">Sangat Setuju</span>
                                                </div>
                                                <div class="flex items-center gap-2">
                                                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-blue-500 text-white text-xs font-bold">4</span>
                                                    <span class="text-sm">Setuju</span>
                                                </div>
                                                <div class="flex items-center gap-2">
                                                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-yellow-500 text-white text-xs font-bold">3</span>
                                                    <span class="text-sm">Cukup</span>
                                                </div>
                                                <div class="flex items-center gap-2">
                                                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-orange-500 text-white text-xs font-bold">2</span>
                                                    <span class="text-sm">Tidak Setuju</span>
                                                </div>
                                                <div class="flex items-center gap-2">
                                                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-red-500 text-white text-xs font-bold">1</span>
                                                    <span class="text-sm">Sangat Tidak Setuju</span>
                                                </div>
                                            </div>
                                        </div>
                                    ');
                                }),
                        ])
                        ->modalHeading('Edit Pertanyaan Survey')
                        ->modalDescription('Perbarui pertanyaan survey kepuasan')
                        ->modalSubmitActionLabel('Simpan Perubahan')
                        ->modalWidth('2xl'),

                    Tables\Actions\DeleteAction::make()
                        ->icon('heroicon-o-trash')
                        ->color('danger'),
                ])
                    ->icon('heroicon-m-ellipsis-vertical')
                    ->tooltip('Aksi'),
            ])
            ->actionsPosition(Tables\Enums\ActionsPosition::BeforeColumns)
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ExportBulkAction::make()
                        ->label('Export ke Excel')
                        ->color('success')
                        ->icon('heroicon-o-arrow-down-tray'),
                ]),
            ]);
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
            'index' => Pages\ListSurveyKualitas::route('/'),
            'create' => Pages\CreateSurveyKualitas::route('/create'),
            'edit' => Pages\EditSurveyKualitas::route('/{record}/edit'),
        ];
    }
}
