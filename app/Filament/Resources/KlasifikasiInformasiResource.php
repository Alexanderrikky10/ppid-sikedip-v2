<?php

namespace App\Filament\Resources;

use App\Filament\Resources\KlasifikasiInformasiResource\Pages;
use App\Filament\Resources\KlasifikasiInformasiResource\RelationManagers;
use App\Models\KlasifikasiInformasi;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class KlasifikasiInformasiResource extends Resource
{
    protected static ?string $model = KlasifikasiInformasi::class;

    protected static ?int $navigationSort = 7;

    protected static ?string $navigationGroup = 'KATEGORI DAN TAG';

    protected static ?string $navigationLabel = 'Klasifikasi Informasi';

    protected static ?string $pluralModelLabel = 'Klasifikasi Informasi';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-group';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Menggunakan Section agar ada kotak putih (Card) seperti di gambar
                Forms\Components\Section::make('Informasi Klasifikasi')
                    ->description('Data klasifikasi informasi publik')
                    ->icon('heroicon-o-rectangle-stack') // Icon kotak tumpuk
                    ->schema([
                        // Nama Klasifikasi (Sebelah Kiri)
                        Forms\Components\TextInput::make('nama_klasifikasi')
                            ->label('Nama Klasifikasi')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true) // Generate slug saat user selesai ketik
                            ->afterStateUpdated(fn($set, ?string $state) => $set('slug', Str::slug($state)))
                            ->placeholder('Masukkan nama klasifikasi')
                            ->helperText('Contoh: Informasi Publik Terbuka, Informasi Dikecualikan'),

                        // Slug (Sebelah Kanan)
                        Forms\Components\TextInput::make('slug')
                            ->label('Slug')
                            ->required()
                            ->maxLength(255)
                            ->unique(KlasifikasiInformasi::class, 'slug', ignoreRecord: true)
                            ->readOnly()
                            ->dehydrated()
                            ->placeholder('slug-klasifikasi')
                            ->helperText('Slug digunakan untuk URL unik klasifikasi ini.'),
                    ])
                    ->columns(2), // ✅ PENTING: Membuat inputan bersebelahan (2 kolom)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama_klasifikasi')
                    ->label('Nama Klasifikasi')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('slug')
                    ->label('Slug')
                    ->searchable()
                    ->color('gray')
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal Dibuat')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Terakhir Diperbarui')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                // Filter dihapus karena tidak ada parent_id dan soft deletes
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make()
                        ->icon('heroicon-o-eye'),

                    Tables\Actions\EditAction::make()
                        ->icon('heroicon-o-pencil'),

                    Tables\Actions\DeleteAction::make()
                        ->icon('heroicon-o-trash'),
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
            'index' => Pages\ListKlasifikasiInformasis::route('/'),
            'create' => Pages\CreateKlasifikasiInformasi::route('/create'),
            'edit' => Pages\EditKlasifikasiInformasi::route('/{record}/edit'),
        ];
    }
}