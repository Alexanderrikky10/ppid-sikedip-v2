<?php

namespace App\Filament\Staff\Resources;

use App\Filament\Staff\Resources\PermohonanInformasiResource\Pages;
use App\Models\PermohonanInformasi;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class PermohonanInformasiResource extends Resource
{
    protected static ?string $model = PermohonanInformasi::class;
    protected static ?int $navigationSort = 5;
    protected static ?string $navigationGroup = 'PERMOHONAN INFORMASI DAN KEBERATAN INFORMASI';
    protected static ?string $navigationLabel = 'Permohonan Informasi';
    protected static ?string $pluralModelLabel = 'Permohonan Informasi';

    // =========================================================
    // Helper: ambil user yang sedang login
    // =========================================================
    private static function currentUser(): ?\App\Models\User
    {
        return Auth::user();
    }

    // =========================================================
    // Helper: validasi apakah user adalah staff
    // dengan perangkat daerah yang valid
    // =========================================================
    private static function isValidStaff(): bool
    {
        $user = static::currentUser();

        // Harus login
        if (!$user) return false;

        // Harus role staff
        if ($user->role !== 'staff') return false;

        // Harus punya perangkat daerah
        if (!$user->perangkat_daerah_id) return false;

        // Harus akun aktif
        if (!$user->is_active) return false;

        return true;
    }

    // =========================================================
    // Helper: validasi apakah record milik OPD staff ini
    // =========================================================
    private static function isRecordMilikOPD($record): bool
    {
        $user = static::currentUser();

        if (!$user || !$user->perangkat_daerah_id) return false;

        return (int) $record->perangkat_daerah_id === (int) $user->perangkat_daerah_id;
    }

    // =========================================================
    // Cek akses ke resource ini
    // Hanya staff aktif dengan OPD yang boleh masuk
    // =========================================================
    public static function canAccess(): bool
    {
        return static::isValidStaff();
    }

    // =========================================================
    // Cek akses create
    // =========================================================
    public static function canCreate(): bool
    {
        return static::isValidStaff();
    }

    // =========================================================
    // Cek akses view record
    // =========================================================
    public static function canView($record): bool
    {
        if (!static::isValidStaff()) return false;

        return static::isRecordMilikOPD($record);
    }

    // =========================================================
    // Cek akses edit record
    // =========================================================
    public static function canEdit($record): bool
    {
        if (!static::isValidStaff()) return false;

        return static::isRecordMilikOPD($record);
    }

    // =========================================================
    // Cek akses delete record
    // =========================================================
    public static function canDelete($record): bool
    {
        if (!static::isValidStaff()) return false;

        return static::isRecordMilikOPD($record);
    }

    // =========================================================
    // Query utama: HANYA tampilkan data OPD staff yang login
    // Ini adalah filter utama & paling penting
    // =========================================================
    public static function getEloquentQuery(): Builder
    {
        $user = static::currentUser();

        $query = parent::getEloquentQuery()
            ->withoutGlobalScopes([SoftDeletingScope::class]);

        // Jika bukan staff valid, kembalikan query kosong
        // sehingga tidak ada data yang tampil sama sekali
        if (!static::isValidStaff()) {
            return $query->whereRaw('1 = 0');
        }

        // Filter WAJIB: hanya data OPD milik staff ini
        $query->where('perangkat_daerah_id', $user->perangkat_daerah_id);

        return $query;
    }

    // =========================================================
    // Badge navigasi: hitung permohonan "diproses" OPD staff
    // =========================================================
    public static function getNavigationBadge(): ?string
    {
        if (!static::isValidStaff()) return null;

        $user = static::currentUser();

        $count = PermohonanInformasi::where('status', 'diproses')
            ->where('perangkat_daerah_id', $user->perangkat_daerah_id)
            ->count();

        return $count > 0 ? (string) $count : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        $count = (int) static::getNavigationBadge();
        return $count > 0 ? 'warning' : 'success';
    }

    // =========================================================
    // FORM
    // =========================================================
    public static function form(Form $form): Form
    {
        $user = static::currentUser();

        return $form
            ->schema([
                // =============================================
                // Kolom Kiri (2/3 width)
                // =============================================
                Forms\Components\Group::make()
                    ->schema([

                        // Section 1: Informasi Permohonan
                        Forms\Components\Section::make('Informasi Permohonan')
                            ->description('Pilih perangkat daerah dan jenis permohonan')
                            ->icon('heroicon-o-document-text')
                            ->schema([
                                Forms\Components\Select::make('perangkat_daerah_id')
                                    ->label('Perangkat Daerah')
                                    ->relationship('perangkatDaerah', 'nama_perangkat_daerah')
                                    ->searchable()
                                    ->preload()
                                    ->required()
                                    ->default($user?->perangkat_daerah_id)
                                    // Dikunci ke OPD staff, tidak bisa diubah
                                    ->disabled(true)
                                    ->dehydrated(true)
                                    ->helperText('⚠️ Perangkat daerah dikunci sesuai akun Anda'),

                                Forms\Components\Select::make('jenis_permohonan')
                                    ->label('Jenis Permohonan')
                                    ->required()
                                    ->options([
                                        'perorangan'  => 'Perorangan',
                                        'badan_hukum' => 'Badan Hukum',
                                        'kelompok'    => 'Kelompok',
                                    ])
                                    ->native(false)
                                    ->placeholder('Pilih Jenis Permohonan'),
                            ])
                            ->columns(2)
                            ->collapsible(),

                        // Section 2: Data Pemohon
                        Forms\Components\Section::make('Data Pemohon')
                            ->description('Lengkapi data diri pemohon informasi')
                            ->icon('heroicon-o-user')
                            ->schema([
                                Forms\Components\TextInput::make('nama_pemohon')
                                    ->label('Nama Lengkap')
                                    ->required()
                                    ->maxLength(255)
                                    ->placeholder('Masukkan nama lengkap'),

                                Forms\Components\Select::make('jenis_kelamin')
                                    ->label('Jenis Kelamin')
                                    ->required()
                                    ->options([
                                        'Laki-laki' => 'Laki-laki',
                                        'Perempuan'  => 'Perempuan',
                                    ])
                                    ->native(false)
                                    ->placeholder('Pilih Jenis Kelamin'),

                                Forms\Components\TextInput::make('no_identitas')
                                    ->label('Nomor Identitas (KTP/SIM)')
                                    ->required()
                                    ->numeric()
                                    ->minLength(16)
                                    ->maxLength(16)
                                    ->placeholder('Contoh: 1234567890123456'),

                                Forms\Components\DatePicker::make('tanggal_lahir')
                                    ->label('Tanggal Lahir')
                                    ->required()
                                    ->native(false)
                                    ->displayFormat('d/m/Y')
                                    ->placeholder('Pilih Tanggal Lahir'),

                                Forms\Components\FileUpload::make('scan_identitas')
                                    ->label('Scan Identitas (KTP/SIM)')
                                    ->image()
                                    ->directory('scan-identitas')
                                    ->imageEditor()
                                    ->visibility('private')
                                    ->disk('minio')
                                    ->maxSize(2048)
                                    ->acceptedFileTypes([
                                        'image/jpeg',
                                        'image/png',
                                        'application/pdf',
                                    ])
                                    ->helperText('Upload scan KTP/SIM (max 2MB, format: JPG, PNG, PDF)')
                                    ->columnSpanFull(),

                                Forms\Components\Textarea::make('alamat_lengkap')
                                    ->label('Alamat Lengkap')
                                    ->required()
                                    ->rows(3)
                                    ->placeholder('Masukkan alamat lengkap sesuai KTP')
                                    ->columnSpanFull(),
                            ])
                            ->columns(2)
                            ->collapsible(),

                        // Section 3: Informasi Kontak
                        Forms\Components\Section::make('Informasi Kontak')
                            ->description('Data kontak untuk keperluan komunikasi')
                            ->icon('heroicon-o-phone')
                            ->schema([
                                Forms\Components\TextInput::make('nomor_whatsapp')
                                    ->label('Nomor WhatsApp')
                                    ->required()
                                    ->tel()
                                    ->maxLength(20)
                                    ->placeholder('Contoh: 081234567890')
                                    ->prefix('+62'),

                                Forms\Components\TextInput::make('alamat_email')
                                    ->label('Alamat Email')
                                    ->email()
                                    ->required()
                                    ->maxLength(150)
                                    ->placeholder('contoh@email.com'),

                                Forms\Components\TextInput::make('nomor_fax')
                                    ->label('Nomor Fax (Opsional)')
                                    ->maxLength(50)
                                    ->placeholder('Contoh: 021-1234567'),
                            ])
                            ->columns(3)
                            ->collapsible(),

                        // Section 4: Detail Permohonan
                        Forms\Components\Section::make('Detail Permohonan Informasi')
                            ->description('Jelaskan informasi yang diminta dan alasan permohonan')
                            ->icon('heroicon-o-document-magnifying-glass')
                            ->schema([
                                Forms\Components\Textarea::make('informasi_diminta')
                                    ->label('Informasi yang Diminta')
                                    ->required()
                                    ->rows(4)
                                    ->placeholder('Jelaskan secara detail informasi apa yang Anda minta')
                                    ->helperText('Jelaskan dengan lengkap dan jelas informasi yang ingin Anda dapatkan')
                                    ->columnSpanFull(),

                                Forms\Components\Textarea::make('alasan_permintaan')
                                    ->label('Alasan Permintaan Informasi')
                                    ->required()
                                    ->rows(4)
                                    ->placeholder('Jelaskan alasan mengapa Anda membutuhkan informasi tersebut')
                                    ->helperText('Jelaskan tujuan dan alasan Anda mengajukan permohonan informasi ini')
                                    ->columnSpanFull(),

                                Forms\Components\FileUpload::make('dokumen_tambahan_path')
                                    ->label('Dokumen Pendukung (Opsional)')
                                    ->helperText('Upload dokumen pendukung jika ada (PDF max 5MB)')
                                    ->visibility('private')
                                    ->disk('minio')
                                    ->acceptedFileTypes(['application/pdf'])
                                    ->directory('dokumen-tambahan')
                                    ->maxSize(5120)
                                    ->columnSpanFull(),

                                Forms\Components\Select::make('cara_penyampaian_informasi')
                                    ->label('Cara Penyampaian Informasi')
                                    ->required()
                                    ->options([
                                        'langsung'   => 'Langsung / Tatap Muka',
                                        'email'      => 'Via Email',
                                        'whatsapp'   => 'Via WhatsApp',
                                        'pos'        => 'Via Pos / Kurir',
                                    ])
                                    ->native(false)
                                    ->placeholder('Pilih Cara Penyampaian'),
                            ])
                            ->columns(1)
                            ->collapsible(),
                    ])
                    ->columnSpan(['lg' => 2]),

                // =============================================
                // Kolom Kanan (1/3 width)
                // =============================================
                Forms\Components\Group::make()
                    ->schema([

                        // Info Staff yang Login
                        Forms\Components\Section::make('Informasi Akun Staff')
                            ->description('Detail akun staff yang memproses')
                            ->icon('heroicon-o-identification')
                            ->schema([
                                Forms\Components\Placeholder::make('staff_nama')
                                    ->label('Nama Staff')
                                    ->content(fn() => static::currentUser()?->name ?? '-'),

                                Forms\Components\Placeholder::make('staff_nip')
                                    ->label('NIP')
                                    ->content(fn() => static::currentUser()?->nip ?? '-'),

                                Forms\Components\Placeholder::make('staff_opd')
                                    ->label('Perangkat Daerah')
                                    ->content(
                                        fn() => static::currentUser()
                                            ?->perangkatDaerah
                                            ?->nama_perangkat_daerah
                                            ?? '⚠️ OPD belum diatur'
                                    ),
                            ])
                            ->columns(1),

                        // Tindak Lanjut
                        Forms\Components\Section::make('Pengaturan Tindak Lanjut')
                            ->description('Pilih cara pengiriman informasi ke pemohon')
                            ->icon('heroicon-o-inbox-arrow-down')
                            ->schema([
                                Forms\Components\Select::make('tindak_lanjut')
                                    ->label('Tindak Lanjut')
                                    ->options([
                                        'Email'          => 'Dikirim via Email',
                                        'WhatsApp'       => 'Dikirim via WhatsApp',
                                        'whatsapp/email' => 'Dikirim via WhatsApp dan Email',
                                    ])
                                    ->native(false)
                                    ->placeholder('Pilih Tindak Lanjut'),
                            ])
                            ->columns(1),

                        // Status & Catatan (hanya tampil saat edit)
                        Forms\Components\Section::make('Status & Catatan')
                            ->description('Informasi status permohonan')
                            ->icon('heroicon-o-clipboard-document-check')
                            ->schema([
                                Forms\Components\Select::make('status')
                                    ->label('Status Permohonan')
                                    ->options([
                                        'diproses' => 'Diproses',
                                        'selesai' => 'Selesai',
                                        'ditolak' => 'Ditolak',
                                    ])
                                    ->default('diproses')
                                    ->required()
                                    ->native(false)
                                    ->helperText('Perbarui status sesuai tindak lanjut permohonan.'),

                                Forms\Components\Placeholder::make('created_at')
                                    ->label('Tanggal Dibuat')
                                    ->content(
                                        fn($record): string =>
                                        $record?->created_at?->format('d/m/Y H:i') ?? '-'
                                    ),

                                Forms\Components\Placeholder::make('updated_at')
                                    ->label('Terakhir Diperbarui')
                                    ->content(
                                        fn($record): string =>
                                        $record?->updated_at?->format('d/m/Y H:i') ?? '-'
                                    ),
                            ])
                            ->columns(1)
                            ->hidden(fn($operation) => $operation === 'create'),
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
        $user = static::currentUser();

        return $table
            ->columns([
                Tables\Columns\TextColumn::make('no_registrasi')
                    ->label('No. Registrasi')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->copyable()
                    ->copyMessage('No. Registrasi disalin!')
                    ->tooltip('Klik untuk menyalin'),

                Tables\Columns\TextColumn::make('nama_pemohon')
                    ->label('Nama Pemohon')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->description(
                        fn($record): string =>
                        ($record->jenis_kelamin ?? '-') . ' • ' . ($record->no_identitas ?? '-')
                    ),

                // Kolom OPD disembunyikan karena staff
                // sudah pasti hanya lihat OPD-nya sendiri
                Tables\Columns\TextColumn::make('perangkatDaerah.nama_perangkat_daerah')
                    ->label('Perangkat Daerah')
                    ->sortable()
                    ->searchable()
                    ->wrap()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('jenis_permohonan')
                    ->label('Jenis')
                    ->badge()
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'perorangan' => 'Perorangan',
                        'badan_hukum' => 'Badan Hukum',
                        'kelompok' => 'Kelompok',
                        default => $state,
                    })
                    ->color(fn(string $state): string => match ($state) {
                        'perorangan' => 'success',
                        'badan_hukum' => 'warning',
                        'kelompok' => 'info',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('nomor_whatsapp')
                    ->label('WhatsApp')
                    ->searchable()
                    ->icon('heroicon-o-phone')
                    ->url(
                        fn($record): string =>
                        'https://wa.me/62' . ltrim($record->nomor_whatsapp ?? '', '0')
                    )
                    ->openUrlInNewTab()
                    ->color('success'),

                Tables\Columns\TextColumn::make('alamat_email')
                    ->label('Email')
                    ->searchable()
                    ->icon('heroicon-o-envelope')
                    ->limit(30)
                    ->tooltip(fn($record): string => $record->alamat_email ?? '-'),

                Tables\Columns\TextColumn::make('tindak_lanjut')
                    ->label('Tindak Lanjut')
                    ->badge()
                    ->color('info')
                    ->searchable(),

                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'diproses' => 'warning',
                        'selesai' => 'success',
                        'ditolak' => 'danger',
                        default => 'gray',
                    })
                    ->icon(fn(string $state): string => match ($state) {
                        'diproses' => 'heroicon-o-clock',
                        'selesai' => 'heroicon-o-check-circle',
                        'ditolak' => 'heroicon-o-x-circle',
                        default => 'heroicon-o-question-mark-circle',
                    })
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal Dibuat')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),
            ])
            ->defaultSort('created_at', 'desc')

            // Header tabel menampilkan nama OPD staff
            ->heading(
                '📋 Permohonan OPD: ' .
                ($user?->perangkatDaerah?->nama_perangkat_daerah ?? '-')
            )
            ->description(
                'Menampilkan permohonan informasi khusus untuk perangkat daerah Anda. ' .
                'Staff lain tidak dapat melihat data ini.'
            )

            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'diproses' => 'Diproses',
                        'selesai' => 'Selesai',
                        'ditolak' => 'Ditolak',
                    ]),

                Tables\Filters\SelectFilter::make('jenis_permohonan')
                    ->label('Jenis Permohonan')
                    ->options([
                        'perorangan' => 'Perorangan',
                        'badan_hukum' => 'Badan Hukum',
                        'kelompok' => 'Kelompok',
                    ]),

                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([

                    // Lihat Berkas/Dokumen
                    Tables\Actions\Action::make('lihat_dokumen')
                        ->label('Lihat Berkas')
                        ->icon('heroicon-o-paper-clip')
                        ->color('info')
                        ->modalContent(
                            fn($record) => view(
                                'filament.resources.permohonan-informasi.view-file',
                                ['record' => $record]
                            )
                        )
                        ->modalSubmitAction(false)
                        ->modalCancelActionLabel('Tutup')
                        ->modalWidth('4xl')
                        // Hanya tampil jika record milik OPD staff ini
                        ->visible(fn($record): bool => static::isRecordMilikOPD($record)),

                    // View
                    Tables\Actions\ViewAction::make()
                        ->icon('heroicon-o-eye')
                        ->visible(fn($record): bool => static::canView($record)),

                    // Edit
                    Tables\Actions\EditAction::make()
                        ->icon('heroicon-o-pencil')
                        ->visible(fn($record): bool => static::canEdit($record)),

                    // Ubah Status
                    Tables\Actions\Action::make('ubah_status')
                        ->label('Ubah Status & Kirim Notifikasi')
                        ->icon('heroicon-o-arrow-path')
                        ->color('warning')
                        ->visible(fn($record): bool => static::canEdit($record))
                        ->form([
                            Forms\Components\Placeholder::make('info_pemohon')
                                ->label('Pemohon')
                                ->content(
                                    fn($record): string =>
                                    ($record->nama_pemohon ?? '-') .
                                    ' (' . ($record->no_registrasi ?? '-') . ')'
                                )
                                ->columnSpanFull(),

                            Forms\Components\Placeholder::make('info_opd')
                                ->label('Perangkat Daerah')
                                ->content(
                                    fn($record): string =>
                                    $record->perangkatDaerah?->nama_perangkat_daerah ?? '-'
                                )
                                ->columnSpanFull(),

                            Forms\Components\Select::make('status')
                                ->label('Status Baru')
                                ->options([
                                    'diproses' => 'Diproses',
                                    'selesai' => 'Selesai',
                                    'ditolak' => 'Ditolak',
                                ])
                                ->default(fn($record): string => $record->status ?? 'diproses')
                                ->required()
                                ->native(false),

                            Forms\Components\FileUpload::make('dokumen_informasi')
                                ->label('Dokumen Informasi (Opsional)')
                                ->multiple()
                                ->directory('dokumen-informasi')
                                ->visibility('private')
                                ->disk('minio')
                                ->acceptedFileTypes([
                                    'application/pdf',
                                    'image/jpeg',
                                    'image/png',
                                ])
                                ->maxSize(5120)
                                ->helperText('Upload dokumen informasi jika ada (maks. 5MB per file)')
                                ->columnSpanFull(),

                            Forms\Components\Textarea::make('catatan')
                                ->label('Catatan')
                                ->placeholder('Tambahkan catatan jika diperlukan')
                                ->rows(3)
                                ->columnSpanFull(),
                        ])
                        ->action(function ($record, array $data): void {
                            $user = static::currentUser();

                            // Double check: pastikan record milik OPD staff ini
                            if (!static::isRecordMilikOPD($record)) {
                                \Filament\Notifications\Notification::make()
                                    ->title('Akses Ditolak')
                                    ->body('Anda tidak memiliki akses untuk mengubah permohonan ini.')
                                    ->danger()
                                    ->send();
                                return;
                            }

                            // Update status
                            $record->update([
                                'status' => $data['status'],
                            ]);

                            \Filament\Notifications\Notification::make()
                                ->title('Status Berhasil Diperbarui')
                                ->body(
                                    'Permohonan ' . $record->no_registrasi .
                                    ' diubah menjadi ' . strtoupper($data['status']) . '.'
                                )
                                ->success()
                                ->send();
                        }),
                ])
                    ->icon('heroicon-m-ellipsis-vertical')
                    ->tooltip('Aksi'),
            ])
            ->actionsPosition(Tables\Enums\ActionsPosition::BeforeColumns)
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
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
            'index' => Pages\ListPermohonanInformasis::route('/'),
            'create' => Pages\CreatePermohonanInformasi::route('/create'),
            'edit' => Pages\EditPermohonanInformasi::route('/{record}/edit'),
        ];
    }
}