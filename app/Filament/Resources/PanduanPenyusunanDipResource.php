<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PanduanPenyusunanDipResource\Pages;
use App\Models\PanduanPenyusunanDip;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Storage;

class PanduanPenyusunanDipResource extends Resource
{
    protected static ?string $model = PanduanPenyusunanDip::class;
    protected static ?string $navigationGroup = 'MANAJEMEN CONTENT';

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationLabel = 'Panduan Penyusunan DIP';

    protected static ?string $modelLabel = 'Panduan';

    protected static ?string $pluralModelLabel = 'Panduan Penyusunan DIP';
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Panduan')
                    ->description('Masukkan judul dan upload file panduan')
                    ->schema([
                        Forms\Components\TextInput::make('judul')
                            ->label('Judul Panduan')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Contoh: Panduan Penyusunan DIP Tahun 2024')
                            ->columnSpanFull(),

                        Forms\Components\FileUpload::make('file_path')
                            ->label('File Panduan')
                            ->directory('panduan-dip')
                            ->disk('minio')
                            ->acceptedFileTypes([
                                'application/pdf',
                            ])
                            ->maxSize(10240) // 10MB
                            ->downloadable()
                            ->openable()
                            ->previewable(true)
                            ->required()
                            ->visibility('private')
                            ->helperText('Format: PDF (Max: 10MB)')
                            ->columnSpanFull(),
                    ])
                    ->columns(2)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('judul')
                    ->label('Judul Panduan')
                    ->searchable()
                    ->sortable()
                    ->wrap()
                    ->weight('medium')
                    ->icon('heroicon-m-document-text')
                    ->iconColor('primary'),

                Tables\Columns\TextColumn::make('file_path')
                    ->label('File')
                    ->formatStateUsing(fn(string $state): string => basename($state))
                    ->icon('heroicon-m-paper-clip')
                    ->iconColor('success')
                    ->limit(30)
                    ->tooltip(fn(string $state): string => basename($state)),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable()
                    ->icon('heroicon-m-calendar'),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Diperbarui')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->icon('heroicon-m-arrow-path'),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make()
                        ->icon('heroicon-m-eye')
                        ->color('info')
                        ->modalWidth('2xl'),

                    Tables\Actions\EditAction::make()
                        ->icon('heroicon-m-pencil-square')
                        ->color('warning')
                        ->modalWidth('2xl'),

                    Tables\Actions\Action::make('download')
                        ->label('Download')
                        ->icon('heroicon-m-arrow-down-tray')
                        ->color('success')
                        ->action(function (PanduanPenyusunanDip $record) {
                            return Storage::download($record->file_path, basename($record->file_path));
                        }),

                    Tables\Actions\DeleteAction::make()
                        ->icon('heroicon-m-trash')
                        ->before(function (PanduanPenyusunanDip $record) {
                            // Hapus file dari storage
                            if (Storage::exists($record->file_path)) {
                                Storage::delete($record->file_path);
                            }
                        }),
                ])
                    ->icon('heroicon-m-ellipsis-vertical')
                    ->size('sm')
                    ->color('gray')
                    ->button()
                    ->label('Aksi')
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->before(function ($records) {
                            // Hapus semua file dari storage
                            foreach ($records as $record) {
                                if (Storage::exists($record->file_path)) {
                                    Storage::delete($record->file_path);
                                }
                            }
                        }),
                ]),
            ])
            ->emptyStateHeading('Belum ada panduan')
            ->emptyStateDescription('Klik tombol di bawah untuk menambahkan panduan baru')
            ->emptyStateIcon('heroicon-o-document-text');
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
            'index' => Pages\ListPanduanPenyusunanDips::route('/'),
            'create' => Pages\CreatePanduanPenyusunanDip::route('/create'),
            'edit' => Pages\EditPanduanPenyusunanDip::route('/{record}/edit'),
        ];
    }
}
