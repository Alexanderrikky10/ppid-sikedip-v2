<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PejabatResource\Pages;
use App\Filament\Resources\PejabatResource\RelationManagers;
use App\Models\Pejabat;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PejabatResource extends Resource
{
    protected static ?string $model = Pejabat::class;

    protected static ?string $navigationGroup = 'PERANGKAT DAERAH DAN PEJABAT'; // Grouping Menu
    protected static ?string $navigationLabel = 'Pejabat';

    protected static ?string $pluralModelLabel = 'Pejabat';

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Grup utama untuk kolom kiri (lebih lebar)
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make('Detail Pejabat')
                            ->schema([
                                Forms\Components\TextInput::make('nama_kepala')
                                    ->label('Nama Pejabat')
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('nip')
                                    ->label('NIP')
                                    ->maxLength(50),
                                Forms\Components\TextInput::make('pangkat_kepala')
                                    ->label('Pangkat / Golongan')
                                    ->maxLength(255),
                                Forms\Components\Select::make('jabatan_id')
                                    ->label('Jabatan')
                                    ->relationship('jabatan', 'nama_jabatan'),
                            ])
                            ->columns(2), // Membuat 2 kolom di dalam section ini
                    ])
                    ->columnSpan(['lg' => 2]), // Mengambil 2/3 dari lebar form

                // Grup untuk kolom kanan (lebih sempit)
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make('Relasi Perangkat Daerah')
                            ->schema([
                                Forms\Components\Select::make('perangkat_daerah_id')
                                    ->label('Perangkat Daerah Terkait')
                                    ->relationship('perangkatDaerah', 'nama_perangkat_daerah')
                                    ->searchable()
                                    ->preload()
                                    ->required(),
                            ]),
                    ])
                    ->columnSpan(['lg' => 1]), // Mengambil 1/3 dari lebar form

            ])
            ->columns(3); // Menetapkan layout utama form menjadi 3 kolom
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // Menambahkan kolom relasi perangkatDaerah untuk tampilan yang lebih informatif
                Tables\Columns\TextColumn::make('perangkatDaerah.nama_perangkat_daerah')
                    ->label('Nama Perangkat Daerah')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('nama_kepala')
                    ->label('Nama kepala')
                    ->searchable(),
                Tables\Columns\TextColumn::make('pangkat_kepala')
                    ->label('Pangkat')
                    ->searchable(),
                Tables\Columns\TextColumn::make('nip')
                    ->searchable(),

                Tables\Columns\TextColumn::make('jabatan.nama_jabatan')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListPejabats::route('/'),
            'create' => Pages\CreatePejabat::route('/create'),
            'edit' => Pages\EditPejabat::route('/{record}/edit'),
        ];
    }
}
