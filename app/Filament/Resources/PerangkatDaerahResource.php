<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PerangkatDaerahResource\Pages;
use App\Models\PerangkatDaerah;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class PerangkatDaerahResource extends Resource
{
    protected static ?string $model = PerangkatDaerah::class;

    protected static ?int $navigationSort = 5;

    protected static ?string $navigationGroup = 'PERANGKAT DAERAH DAN PEJABAT'; // Grouping Menu

    protected static ?string $navigationIcon = 'heroicon-o-building-office-2'; // Icon Gedung

    protected static ?string $navigationLabel = 'Perangkat Daerah';

    protected static ?string $pluralModelLabel = 'Perangkat Daerah';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // KOTAK KIRI (INFORMASI UTAMA)
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make('Identitas Perangkat Daerah')
                            ->description('Informasi dasar mengenai OPD/BUMD')
                            ->icon('heroicon-o-identification')
                            ->schema([
                                Forms\Components\TextInput::make('nama_perangkat_daerah')
                                    ->label('Nama Perangkat Daerah')
                                    ->required()
                                    ->maxLength(255)
                                    ->live(onBlur: true) // Auto Slug
                                    ->afterStateUpdated(fn($set, ?string $state) => $set('slug', Str::slug($state)))
                                    ->placeholder('Dinas Komunikasi dan Informatika'),

                                Forms\Components\TextInput::make('labele_perangkat_daerah')
                                    ->label('Label / Singkatan')
                                    ->required()
                                    ->maxLength(100)
                                    ->placeholder('DISKOMINFO'),

                                Forms\Components\TextInput::make('slug')
                                    ->label('Slug URL')
                                    ->required()
                                    ->readOnly()
                                    ->maxLength(255)
                                    ->unique(PerangkatDaerah::class, 'slug', ignoreRecord: true)
                                    ->helperText('Otomatis dibuat dari nama perangkat daerah'),

                                // Dropdown Parent (Hierarki)
                                Forms\Components\Select::make('parent_id')
                                    ->label('Induk Organisasi (Opsional)')
                                    ->relationship('parent', 'nama_perangkat_daerah') // Pastikan ada relasi 'parent' di Model
                                    ->searchable()
                                    ->preload()
                                    ->placeholder('Pilih jika ini adalah sub-organisasi'),
                            ])
                            ->columns(2), // Tampilan 2 kolom bersebelahan
                    ])
                    ->columnSpan(['lg' => 2]),

                // KOTAK KANAN (KATEGORI & GAMBAR)
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make('Atribut & Media')
                            ->icon('heroicon-o-photo')
                            ->schema([
                                // Dropdown Kategori
                                Forms\Components\Select::make('kategori_informasi_id')
                                    ->label('Kategori Instansi')
                                    ->relationship('kategoriInformasi', 'nama_kategori') // Pastikan ada relasi 'kategoriInformasi' di Model
                                    ->required()
                                    ->searchable()
                                    ->preload(),

                                // Upload Gambar
                                Forms\Components\FileUpload::make('images')
                                    ->label('Logo Instansi')
                                    ->image()
                                    ->disk('minio') // Sesuaikan disk
                                    ->directory('logo-perangkat-daerah')
                                    ->visibility('private')
                                    ->imageEditor()
                                    ->columnSpanFull(),

                                // Metadata (Hanya Tampil saat Edit)
                                Forms\Components\Placeholder::make('created_at')
                                    ->label('Dibuat Pada')
                                    ->content(fn($record) => $record?->created_at?->format('d F Y H:i') ?? '-')
                                    ->hidden(fn($operation) => $operation === 'create'),
                            ]),
                    ])
                    ->columnSpan(['lg' => 1]),
            ])
            ->columns(3); // Layout Grid 3 Kolom (2 Kiri, 1 Kanan)
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // Kolom Gambar/Logo
                Tables\Columns\ImageColumn::make('images')
                    ->label('Logo')
                    ->disk('minio')
                    ->visibility('private')
                    ->circular(),

                Tables\Columns\TextColumn::make('nama_perangkat_daerah')
                    ->label('Nama Perangkat Daerah')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->wrap(),

                Tables\Columns\TextColumn::make('labele_perangkat_daerah')
                    ->label('Singkatan')
                    ->searchable()
                    ->badge()
                    ->color('gray'),

                // Menampilkan Nama Kategori, bukan ID
                Tables\Columns\TextColumn::make('kategoriInformasi.nama_kategori')
                    ->label('Kategori')
                    ->sortable()
                    ->badge()
                    ->color(fn($state) => match ($state) {
                        'Pemprov' => 'purple',
                        'Pemkab/Kota' => 'success',
                        'BUMD' => 'info',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                // Filter Kategori
                Tables\Filters\SelectFilter::make('kategori_informasi_id')
                    ->label('Filter Kategori')
                    ->relationship('kategoriInformasi', 'nama_kategori'),
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
            'index' => Pages\ListPerangkatDaerahs::route('/'),
            'create' => Pages\CreatePerangkatDaerah::route('/create'),
            'edit' => Pages\EditPerangkatDaerah::route('/{record}/edit'),
        ];
    }
}