<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use App\Filament\Resources\UserResource\Pages;
use Filament\Forms\Get;
use Filament\Forms\Set;
use App\Models\User;
use App\Models\PerangkatDaerah;
use Illuminate\Support\Facades\Hash;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?int $navigationSort = 13;
    protected static ?string $navigationGroup = 'MANAJEMEN USER';
    protected static ?string $navigationLabel = 'User';
    protected static ?string $pluralModelLabel = 'User';
    protected static ?string $navigationIcon = 'heroicon-o-users';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // GRUP UTAMA (KIRI) - 2 Kolom
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make('Detail Pengguna')
                            ->description('Informasi dasar dan kredensial untuk pengguna.')
                            ->schema([
                                Forms\Components\TextInput::make('name')
                                    ->label('Nama Lengkap')
                                    ->required()
                                    ->maxLength(255)
                                    ->placeholder('Nama yang akan ditampilkan di antarmuka.'),

                                Forms\Components\TextInput::make('nip')
                                    ->label('NIP')
                                    ->required()
                                    ->placeholder('Masukkan NIP tanpa spasi atau karakter khusus')
                                    ->maxLength(255),


                                Forms\Components\TextInput::make('email')
                                    ->email()
                                    ->required()
                                    ->unique(ignoreRecord: true)
                                    ->placeholder('Masukan Email Yang Valid')
                                    ->maxLength(100),

                                Forms\Components\TextInput::make('password')
                                    ->password()
                                    ->dehydrateStateUsing(fn(string $state): string => Hash::make($state))
                                    ->dehydrated(fn(?string $state): bool => filled($state))
                                    ->required(fn(string $operation): bool => $operation === 'create')
                                    ->maxLength(255)
                                    ->helperText('Kosongkan jika tidak ingin mengubah password saat mengedit.'),

                                Forms\Components\FileUpload::make('image')
                                    ->label('Foto Profil')
                                    ->disk('minio') // Simpan di disk 'minio'
                                    ->directory('user-avatars')
                                    ->acceptedFileTypes(['image/jpeg', 'image/png']) // Hanya izinkan JPG dan PNG
                                    ->image()
                                    ->imageEditor()
                                    ->maxSize(2048)
                                    ->helperText('Unggah foto profil pengguna (maks. 2MB).')
                                    ->columnSpanFull()
                                    ->visibility('private'),
                            ])
                            ->columns(2), // Bagian dalam section ini dibagi 2 kolom
                    ])
                    ->columnSpan(['lg' => 2]), // Grup utama ini mengambil 2 dari 3 kolom grid

                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make('Hak Akses & Organisasi')
                            ->description('Pengaturan peran dan organisasi pengguna.')
                            ->schema([
                                Forms\Components\Select::make('role')
                                    ->label('Peran (Role)')
                                    ->required()
                                    ->options([
                                        'admin' => 'Administrator',
                                        'staff' => 'Staff', 
                                    ])
                                    ->native(false)
                                    ->default('staff'),


                                // DROPDOWN 1: KATEGORI FILTER (VIRTUAL)
                                Forms\Components\Select::make('hak_akses') 
                                    ->label('Kategori Hak Akses')
                                    ->options([
                                        'Pemprov' => 'Pemprov',
                                        'Kab/Kota' => 'Kab/Kota',
                                        'Perangkat daerah' => 'Perangkat daerah',
                                        'BUMD' => 'BUMD',
                                    ])
                                    ->live() 
                                    ->afterStateUpdated(fn(Set $set) => $set('perangkat_daerah_id', null)) // Kosongkan pilihan PD jika kategori ganti
                                    ->native(false)
                                    ->placeholder('Pilih Kategori Dahulu...'),

                                Forms\Components\Select::make('perangkat_daerah_id')
                                    ->label('Perangkat Daerah')
                                    ->options(function (Get $get): array {
                                        $kategori = $get('hak_akses');
                                        if (!$kategori) {
                                        }
                                        return PerangkatDaerah::where('kategori_perangkat_daerah', $kategori)
                                            ->pluck('nama_perangkat_daerah', 'id')
                                            ->all();
                                    })
                                    ->searchable()
                                    ->native(false)
                                    ->required() 
                                    ->afterStateHydrated(function (Set $set, ?User $record) {
                                        // Jika ada record (mode edit) dan user punya relasi perangkatDaerah
                                        if ($record && $record->perangkatDaerah) {
                                            // Set nilai 'kategori_filter' berdasarkan data dari relasi
                                            $set('kategori_filter', $record->perangkatDaerah->kategori_perangkat_daerah);
                                        }
                                    }),


                                Forms\Components\TextInput::make('daerah')
                                    ->label('Daerah')
                                    ->maxLength(100)
                                    ->placeholder(' Kabupaten Sintang, Kota singkawang, dll.')
                                    ->required(),

                                Forms\Components\TextInput::make('biro')
                                    ->label('Biro')
                                    ->maxLength(100)
                                    ->required(),
                            ])
                            ->columns(1), 
                    ])
                    ->columnSpan(['lg' => 1]), 
            ])
        ->columns(3); // Seluruh form menggunakan grid 3 kolom
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image')
                    ->label('Foto')
                    ->disk('minio')
                    ->visibility('private'),
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Panggilan')
                    ->searchable(),

                Tables\Columns\TextColumn::make('email')
                    ->searchable(),

                Tables\Columns\TextColumn::make('role')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'admin' => 'danger',
                        'staff' => 'warning', // Disesuaikan dengan form
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('perangkatDaerah.nama_perangkat_daerah') // Tampilkan nama dari relasi
                    ->label('Perangkat Daerah')
                    ->sortable()
                    ->searchable(),

                // Menampilkan Kategori di tabel (Opsional tapi bagus)
                Tables\Columns\TextColumn::make('perangkatDaerah.kategori_perangkat_daerah')
                    ->label('Kategori PD')
                    ->sortable()
                    ->searchable()
                    ->badge(),

                Tables\Columns\TextColumn::make('daerah')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('biro')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

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
                Tables\Filters\SelectFilter::make('role')
                    ->options([
                        'admin' => 'Administrator',
                        'staff' => 'Staff',
                    ]),
                Tables\Filters\SelectFilter::make('perangkat_daerah_id')
                    ->label('Perangkat Daerah')
                    ->relationship('perangkatDaerah', 'nama_perangkat_daerah')
                    ->searchable() // Tambahkan searchable di filter
                    ->preload(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}