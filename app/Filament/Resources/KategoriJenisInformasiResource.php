<?php

namespace App\Filament\Resources;

use App\Filament\Resources\KategoriJenisInformasiResource\Pages;
use App\Filament\Resources\KategoriJenisInformasiResource\RelationManagers;
use App\Models\KategoriJenisInformasi;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class KategoriJenisInformasiResource extends Resource
{
    protected static ?string $navigationGroup = 'KATEGORI DAN TAG';
    protected static ?string $model = KategoriJenisInformasi::class;
    protected static ?int $navigationSort = 6;
    protected static ?string $navigationIcon = 'heroicon-o-tag';
    protected static ?string $navigationLabel = 'Jenis Informasi';
    protected static ?string $pluralModelLabel = 'Jenis Informasi';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Section Utama: Input Data
                Forms\Components\Section::make('Detail Kategori')
                    ->description('Manajemen data kategori jenis informasi')
                    ->icon('heroicon-o-rectangle-stack')
                    ->schema([
                        Forms\Components\TextInput::make('nama_kategori')
                            ->label('Nama Kategori')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true) // Generate slug otomatis saat diketik
                            ->afterStateUpdated(fn($set, ?string $state) => $set('slug', Str::slug($state)))
                            ->placeholder('Masukkan nama kategori')
                            ->helperText('Contoh: Berkala, Serta Merta, Setiap Saat'),

                        Forms\Components\TextInput::make('slug')
                            ->label('Slug URL')
                            ->required()
                            ->maxLength(255)
                            ->unique(KategoriJenisInformasi::class, 'slug', ignoreRecord: true)
                            ->readOnly()
                            ->dehydrated()
                            ->placeholder('slug-kategori')
                            ->helperText('Dibuat otomatis dari nama kategori'),
                    ])
                    ->columns(2), // Tampilan bersebelahan (kiri-kanan)

                // Section Tambahan: Metadata (Hanya muncul saat Edit)
                Forms\Components\Section::make('Info Metadata')
                    ->icon('heroicon-o-information-circle')
                    ->schema([
                        Forms\Components\Placeholder::make('created_at')
                            ->label('Dibuat Pada')
                            ->content(fn($record): string => $record?->created_at?->format('d F Y, H:i') ?? '-'),

                        Forms\Components\Placeholder::make('updated_at')
                            ->label('Terakhir Diubah')
                            ->content(fn($record): string => $record?->updated_at?->format('d F Y, H:i') ?? '-'),
                    ])
                    ->columns(2)
                    ->hidden(fn($operation) => $operation === 'create'), // Sembunyikan saat Create
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama_kategori')
                    ->label('Nama Kategori')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->icon('heroicon-o-tag'),

                Tables\Columns\TextColumn::make('slug')
                    ->label('Slug')
                    ->searchable()
                    ->color('gray')
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d/m/Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Diubah')
                    ->dateTime('d/m/Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                // Filter dihapus agar aman dari error Soft Deletes
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make()
                        ->color('info'),
                    Tables\Actions\EditAction::make()
                        ->color('warning'),
                    Tables\Actions\DeleteAction::make(),
                ])
                    ->icon('heroicon-m-ellipsis-vertical')
                    ->tooltip('Aksi'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListKategoriJenisInformasis::route('/'),
            'create' => Pages\CreateKategoriJenisInformasi::route('/create'),
            'edit' => Pages\EditKategoriJenisInformasi::route('/{record}/edit'),
        ];
    }
}