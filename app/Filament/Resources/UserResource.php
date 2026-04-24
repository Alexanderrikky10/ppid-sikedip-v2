<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
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
                // =============================================
                // GRUP KIRI - 2 Kolom
                // =============================================
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make('Detail Pengguna')
                            ->description('Informasi dasar dan kredensial untuk pengguna.')
                            ->icon('heroicon-o-user-circle')
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
                                    ->dehydrateStateUsing(
                                        fn(string $state): string => Hash::make($state)
                                    )
                                    ->dehydrated(
                                        fn(?string $state): bool => filled($state)
                                    )
                                    ->required(
                                        fn(string $operation): bool => $operation === 'create'
                                    )
                                    ->maxLength(255)
                                    ->helperText(
                                        'Kosongkan jika tidak ingin mengubah password saat mengedit.'
                                    ),

                                Forms\Components\TextInput::make('daerah')
                                    ->label('Daerah')
                                    ->maxLength(100)
                                    ->placeholder('Kabupaten Sintang, Kota Singkawang, dll.'),

                                Forms\Components\TextInput::make('biro')
                                    ->label('Biro')
                                    ->maxLength(100),

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

                // =============================================
                // GRUP KANAN - 1 Kolom
                // =============================================
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make('Hak Akses & Organisasi')
                            ->description('Pengaturan peran dan organisasi pengguna.')
                            ->icon('heroicon-o-shield-check')
                            ->schema([
                                // Role
                                Forms\Components\Select::make('role')
                                    ->label('Peran (Role)')
                                    ->required()
                                    ->options([
                                        'admin' => 'Administrator',
                                        'staff' => 'Staff',
                                    ])
                                    ->native(false)
                                    ->default('staff'),

                                // Status Aktif
                                Forms\Components\Toggle::make('is_active')
                                    ->label('Status Akun Aktif')
                                    ->default(false)
                                    ->helperText('Aktifkan akun agar pengguna dapat login.')
                                    ->onColor('success')
                                    ->offColor('danger'),

                                // =============================================
                                // PERBAIKAN UTAMA:
                                // Nama field diubah dari ' ' (spasi) 
                                // menjadi 'kategori_filter'
                                // =============================================
                                Forms\Components\Select::make('kategori_filter')
                                    ->label('Kategori Hak Akses')
                                    ->options([
                                        'Pemprov' => 'Pemprov',
                                        'Kab/Kota' => 'Kab/Kota',
                                        'BUMD' => 'BUMD',
                                    ])
                                    ->live()
                                    ->native(false)
                                    ->placeholder('Pilih Kategori Dahulu...')
                                    ->required()
                                    // Reset perangkat_daerah_id saat kategori berubah
                                    ->afterStateUpdated(function (Set $set) {
                                        $set('perangkat_daerah_id', null);
                                    })
                                    // Saat edit: isi otomatis dari kategori OPD user
                                    ->afterStateHydrated(function (Set $set, ?User $record) {
                                        if ($record && $record->perangkatDaerah) {
                                            // Ambil nama kategori dari relasi
                                            $set(
                                                'kategori_filter',
                                                $record->perangkatDaerah->kategoriInformasi?->nama_kategori
                                            );
                                        }
                                    })
                                    // Field ini tidak disimpan ke database
                                    ->dehydrated(false),

                                // =============================================
                                // Perangkat Daerah
                                // Filter berdasarkan kategori_filter
                                // =============================================
                                Forms\Components\Select::make('perangkat_daerah_id')
                                    ->label('Perangkat Daerah')
                                    ->options(function (Get $get): array {
                                        // Ambil nilai dari field 'kategori_filter'
                                        $kategori = $get('kategori_filter');

                                        if (blank($kategori)) {
                                            return [];
                                        }

                                        // Filter OPD berdasarkan nama kategori
                                        return PerangkatDaerah::whereHas(
                                            'kategoriInformasi',
                                            fn($query) => $query->where('nama_kategori', $kategori)
                                        )
                                            ->orderBy('nama_perangkat_daerah')
                                            ->pluck('nama_perangkat_daerah', 'id')
                                            ->toArray();
                                    })
                                    ->searchable()
                                    ->native(false)
                                    ->live()
                                    ->placeholder(
                                        fn(Get $get): string => blank($get('kategori_filter'))
                                        ? 'Pilih kategori terlebih dahulu...'
                                        : 'Pilih Perangkat Daerah...'
                                    )
                                    ->helperText(
                                        fn(Get $get): ?string => blank($get('kategori_filter'))
                                        ? '⚠️ Pilih kategori hak akses terlebih dahulu'
                                        : null
                                    ),

                                // Hak Akses (array fitur yang bisa diakses)
                                // Forms\Components\CheckboxList::make('hak_akses')
                                //     ->label('Hak Akses Fitur')
                                //     ->options([
                                //         'permohonan_informasi' => 'Permohonan Informasi',
                                //         'keberatan_informasi' => 'Keberatan Informasi',
                                //         'laporan' => 'Laporan',
                                //         'pengumuman' => 'Pengumuman',
                                //     ])
                                //     ->columns(1)
                                //     ->helperText('Centang fitur yang dapat diakses oleh staff ini.'),
                            ])
                            ->columns(1),    
                    ])
                    ->columnSpan(['lg' => 1]),
            ])
            ->columns(3);
    }

    // =========================================================
    // TABLE
    // =========================================================
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image')
                    ->label('Foto')
                    ->disk('minio')
                    ->visibility('private')
                    ->circular(),

                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Lengkap')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->description(fn(User $record): string => $record->nip ?? '-'),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('Status')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger')
                    ->sortable(),

                Tables\Columns\TextColumn::make('email')
                    ->searchable()
                    ->icon('heroicon-o-envelope'),

                Tables\Columns\TextColumn::make('role')
                    ->badge()
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'admin' => 'Administrator',
                        'staff' => 'Staff',
                        default => $state,
                    })
                    ->color(fn(string $state): string => match ($state) {
                        'admin' => 'danger',
                        'staff' => 'warning',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('perangkatDaerah.nama_perangkat_daerah')
                    ->label('Perangkat Daerah')
                    ->sortable()
                    ->searchable()
                    ->wrap(),

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
                    ->label('Dibuat')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('role')
                    ->label('Peran')
                    ->options([
                        'admin' => 'Administrator',
                        'staff' => 'Staff',
                    ]),

                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Status Akun')
                    ->trueLabel('Aktif')
                    ->falseLabel('Tidak Aktif')
                    ->placeholder('Semua'),

                Tables\Filters\SelectFilter::make('perangkat_daerah_id')
                    ->label('Perangkat Daerah')
                    ->relationship('perangkatDaerah', 'nama_perangkat_daerah')
                    ->searchable()
                    ->preload(),
            ])
            ->actions([
                // Konfirmasi Akun (hanya jika belum aktif)
                Tables\Actions\Action::make('confirm')
                    ->label('Aktifkan')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn(User $record): bool => !$record->is_active)
                    ->requiresConfirmation()
                    ->modalHeading('Aktifkan Akun')
                    ->modalDescription(
                        fn(User $record) => 'Apakah Anda yakin ingin mengaktifkan akun ' . $record->name . '?'
                    )
                    ->modalSubmitActionLabel('Ya, Aktifkan')
                    ->action(function (User $record) {
                        $record->update(['is_active' => true]);
                        Notification::make()
                            ->title('Akun Berhasil Diaktifkan')
                            ->body($record->name . ' sekarang dapat login ke sistem.')
                            ->success()
                            ->send();
                    }),

                // Nonaktifkan Akun (hanya jika sudah aktif)
                Tables\Actions\Action::make('deactivate')
                    ->label('Nonaktifkan')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->visible(fn(User $record): bool => (bool) $record->is_active)
                    ->requiresConfirmation()
                    ->modalHeading('Nonaktifkan Akun')
                    ->modalDescription(
                        fn(User $record) => 'Apakah Anda yakin ingin menonaktifkan akun ' . $record->name . '?'
                    )
                    ->modalSubmitActionLabel('Ya, Nonaktifkan')
                    ->action(function (User $record) {
                        $record->update(['is_active' => false]);
                        Notification::make()
                            ->title('Akun Berhasil Dinonaktifkan')
                            ->body($record->name . ' tidak dapat login ke sistem.')
                            ->warning()
                            ->send();
                    }),

                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),

                    // Bulk Aktifkan
                    Tables\Actions\BulkAction::make('bulk_activate')
                        ->label('Aktifkan Terpilih')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->requiresConfirmation()
                        ->modalHeading('Aktifkan Akun Terpilih')
                        ->modalDescription('Apakah Anda yakin ingin mengaktifkan semua akun yang dipilih?')
                        ->modalSubmitActionLabel('Ya, Aktifkan Semua')
                        ->action(function ($records) {
                            $records->each(fn(User $record) => $record->update(['is_active' => true]));
                            Notification::make()
                                ->title('Akun Berhasil Diaktifkan')
                                ->body($records->count() . ' akun berhasil diaktifkan.')
                                ->success()
                                ->send();
                        }),

                    // Bulk Nonaktifkan
                    Tables\Actions\BulkAction::make('bulk_deactivate')
                        ->label('Nonaktifkan Terpilih')
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->requiresConfirmation()
                        ->modalHeading('Nonaktifkan Akun Terpilih')
                        ->modalDescription('Apakah Anda yakin ingin menonaktifkan semua akun yang dipilih?')
                        ->modalSubmitActionLabel('Ya, Nonaktifkan Semua')
                        ->action(function ($records) {
                            $records->each(fn(User $record) => $record->update(['is_active' => false]));
                            Notification::make()
                                ->title('Akun Berhasil Dinonaktifkan')
                                ->body($records->count() . ' akun berhasil dinonaktifkan.')
                                ->warning()
                                ->send();
                        }),
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