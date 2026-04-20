<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\KategoriInformasi;
use App\Models\PerangkatDaerah;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Hash;
// Import tambahan untuk Notifikasi
use Filament\Notifications\Notification;

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
                                    ->disk('minio')
                                    ->directory('user-avatars')
                                    ->acceptedFileTypes(['image/jpeg', 'image/png'])
                                    ->image()
                                    ->imageEditor()
                                    ->maxSize(2048)
                                    ->helperText('Unggah foto profil pengguna (maks. 2MB).')
                                    ->columnSpanFull()
                                    ->visibility('private'),
                            ])
                            ->columns(2),
                    ])
                    ->columnSpan(['lg' => 2]),

                // GRUP KANAN - 1 Kolom
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

                                // DROPDOWN 1: KATEGORI FILTER - options dari KategoriInformasi, value = id
                                Forms\Components\Select::make(' ')
                                    ->label('Kategori Hak Akses')
                                    ->options(function (): array {
                                        return KategoriInformasi::pluck('nama_kategori', 'id')->all();
                                    })
                                    ->live()
                                    ->afterStateUpdated(fn(Set $set) => $set('perangkat_daerah_id', null))
                                    ->native(false)
                                    ->placeholder('Pilih Kategori Dahulu...')
                                    ->required(),

                                // DROPDOWN 2: PERANGKAT DAERAH - filter by kategori_informasi_id
                                Forms\Components\Select::make('perangkat_daerah_id')
                                    ->label('Perangkat Daerah')
                                    ->options(function (Get $get): array {
                                        $kategoriId = $get('hak_akses');

                                        if (!$kategoriId) {
                                            return [];
                                        }

                                        return PerangkatDaerah::where('kategori_informasi_id', $kategoriId)
                                            ->pluck('nama_perangkat_daerah', 'id')
                                            ->all();
                                    })
                                    ->searchable()
                                    ->native(false)
                                    ->required()
                                    ->afterStateHydrated(function (Set $set, ?User $record) {
                                        // Saat edit, set nilai hak_akses berdasarkan kategori dari perangkat daerah user
                                        if ($record && $record->perangkatDaerah) {
                                            $set('hak_akses', $record->perangkatDaerah->kategori_informasi_id);
                                        }
                                    }),

                                Forms\Components\TextInput::make('daerah')
                                    ->label('Daerah')
                                    ->maxLength(100)
                                    ->placeholder('Kabupaten Sintang, Kota Singkawang, dll.'),

                                Forms\Components\TextInput::make('biro')
                                    ->label('Biro')
                                    ->maxLength(100),

                            ])
                            ->columns(1),
                    ])
                    ->columnSpan(['lg' => 1]),
            ])
            ->columns(3);
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
                    ->label('Nama Lengkap')
                    ->searchable(),

                // Indikator Status (Opsional tapi membantu visual)
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Status')
                    ->boolean()
                    ->sortable(),

                Tables\Columns\TextColumn::make('nip')
                    ->label('NIP')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('email')
                    ->searchable(),

                Tables\Columns\TextColumn::make('role')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'admin' => 'danger',
                        'staff' => 'warning',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('perangkatDaerah.nama_perangkat_daerah')
                    ->label('Perangkat Daerah')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('perangkatDaerah.kategoriInformasi.nama_kategori')
                    ->label('Kategori')
                    ->sortable()
                    ->searchable()
                    ->badge()
                    ->color('info'),

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
                    ->searchable()
                    ->preload(),
            ])
            ->actions([
                // AKSI 1: KONFIRMASI (Hanya muncul jika is_active = false)
                Tables\Actions\Action::make('confirm')
                    ->label('Konfirmasi Akun')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn(User $record): bool => !$record->is_active)
                    ->requiresConfirmation()
                    ->action(function (User $record) {
                        $record->update(['is_active' => true]);
                        Notification::make()
                            ->title('Akun berhasil diaktifkan')
                            ->success()
                            ->send();
                    }),

                // AKSI 2: NONAKTIFKAN (Hanya muncul jika is_active = true)
                Tables\Actions\Action::make('deactivate')
                    ->label('Nonaktifkan Akun')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->visible(fn(User $record): bool => (bool) $record->is_active)
                    ->requiresConfirmation()
                    ->action(function (User $record) {
                        $record->update(['is_active' => false]);
                        Notification::make()
                            ->title('Akun berhasil dinonaktifkan')
                            ->warning()
                            ->send();
                    }),

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
        return [];
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