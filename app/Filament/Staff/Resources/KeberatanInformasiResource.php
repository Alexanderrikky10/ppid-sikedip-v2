<?php

namespace App\Filament\Staff\Resources;

use App\Filament\Staff\Resources\KeberatanInformasiResource\Pages;
use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use App\Models\KeberatanInformasi;
use App\Models\PermohonanInformasi;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class KeberatanInformasiResource extends Resource
{
    protected static ?string $model = KeberatanInformasi::class;
    protected static ?string $navigationGroup = 'PERMOHONAN INFORMASI DAN KEBERATAN INFORMASI';
    protected static ?string $navigationLabel = 'Keberatan Informasi';
    protected static ?string $pluralModelLabel = 'Keberatan Informasi';
    protected static ?int $navigationSort = 6;

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

        if (!$user)
            return false;
        if ($user->role !== 'staff')
            return false;
        if (!$user->perangkat_daerah_id)
            return false;
        if (!$user->is_active)
            return false;

        return true;
    }

    // =========================================================
    // Helper: validasi apakah record keberatan
    // terkait dengan OPD staff yang login
    // Relasi: keberatan -> permohonan -> perangkat_daerah_id
    // =========================================================
    private static function isRecordMilikOPD($record): bool
    {
        $user = static::currentUser();

        if (!$user || !$user->perangkat_daerah_id)
            return false;

        // Cek melalui relasi permohonanInformasi
        $perangkatDaerahId = $record->permohonanInformasi?->perangkat_daerah_id;

        return (int) $perangkatDaerahId === (int) $user->perangkat_daerah_id;
    }

    // =========================================================
    // Cek akses ke resource
    // =========================================================
    public static function canAccess(): bool
    {
        return static::isValidStaff();
    }

    public static function canCreate(): bool
    {
        return static::isValidStaff();
    }

    public static function canView($record): bool
    {
        if (!static::isValidStaff())
            return false;
        return static::isRecordMilikOPD($record);
    }

    public static function canEdit($record): bool
    {
        if (!static::isValidStaff())
            return false;
        return static::isRecordMilikOPD($record);
    }

    public static function canDelete($record): bool
    {
        if (!static::isValidStaff())
            return false;
        return static::isRecordMilikOPD($record);
    }

    // =========================================================
    // Query utama: filter berdasarkan OPD staff
    // Melalui relasi permohonanInformasi
    // =========================================================
    public static function getEloquentQuery(): Builder
    {
        $user = static::currentUser();

        $query = parent::getEloquentQuery()
            ->withoutGlobalScopes([SoftDeletingScope::class]);

        // Jika bukan staff valid, kembalikan query kosong
        if (!static::isValidStaff()) {
            return $query->whereRaw('1 = 0');
        }

        // Filter WAJIB: hanya keberatan yang terkait
        // dengan permohonan OPD milik staff ini
        $query->whereHas('permohonanInformasi', function (Builder $q) use ($user) {
            $q->where('perangkat_daerah_id', $user->perangkat_daerah_id);
        });

        return $query;
    }

    // =========================================================
    // Badge navigasi
    // =========================================================
    public static function getNavigationBadge(): ?string
    {
        if (!static::isValidStaff())
            return null;

        $user = static::currentUser();

        $count = KeberatanInformasi::whereHas('permohonanInformasi', function (Builder $q) use ($user) {
            $q->where('perangkat_daerah_id', $user->perangkat_daerah_id);
        })
            ->where('status', 'pending')
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
                Forms\Components\Wizard::make([

                    // =============================================
                    // Step 1: Data Permohonan
                    // =============================================
                    Forms\Components\Wizard\Step::make('Data Permohonan')
                        ->icon('heroicon-o-document-text')
                        ->schema([
                            Forms\Components\Section::make('Cari Permohonan yang Diajukan Keberatan')
                                ->description('Masukkan NIK pemohon untuk mencari data permohonan terkait OPD Anda.')
                                ->icon('heroicon-o-magnifying-glass')
                                ->schema([
                                    // Info OPD Staff
                                    Forms\Components\Placeholder::make('info_opd_staff')
                                        ->label('OPD Anda')
                                        ->content(
                                            fn() => static::currentUser()
                                                ?->perangkatDaerah
                                                    ?->nama_perangkat_daerah
                                            ?? '⚠️ OPD belum diatur'
                                        )
                                        ->columnSpanFull(),

                                    // Input NIK
                                    Forms\Components\TextInput::make('nik_pemohon')
                                        ->label('NIK Pemohon')
                                        ->required()
                                        ->numeric()
                                        ->minLength(16)
                                        ->maxLength(16)
                                        ->helperText('Masukkan 16 digit NIK pemohon.')
                                        ->live(onBlur: true)
                                        ->afterStateUpdated(function (Set $set) {
                                            // Reset pilihan saat NIK berubah
                                            $set('permohonan_informasi_id', null);
                                            $set('nama_pemohon', null);
                                            $set('alamat_pemohon', null);
                                            $set('telepon_pemohon', null);
                                            $set('pekerjaan', null);
                                        }),

                                    // Select No. Registrasi
                                    // HANYA tampilkan permohonan dari OPD staff ini
                                    Forms\Components\Select::make('permohonan_informasi_id')
                                        ->label('Pilih No. Registrasi Permohonan')
                                        ->required()
                                        ->options(function (Get $get) use ($user): array {
                                            $nik = $get('nik_pemohon');

                                            if (empty($nik) || strlen((string) $nik) !== 16) {
                                                return [];
                                            }

                                            // Filter: NIK + OPD staff yang login
                                            return PermohonanInformasi::where('no_identitas', $nik)
                                                ->where(
                                                    'perangkat_daerah_id',
                                                    $user?->perangkat_daerah_id
                                                )
                                                ->orderBy('created_at', 'desc')
                                                ->pluck('no_registrasi', 'id')
                                                ->toArray();
                                        })
                                        ->searchable()
                                        ->native(false)
                                        ->placeholder('Pilih No. Registrasi setelah mengisi NIK')
                                        ->helperText(
                                            '⚠️ Hanya menampilkan permohonan yang ditujukan ke OPD Anda.'
                                        )
                                        ->live()
                                        ->afterStateUpdated(function (Set $set, Get $get, ?string $state): void {
                                            if (blank($state)) {
                                                $set('nama_pemohon', null);
                                                $set('alamat_pemohon', null);
                                                $set('telepon_pemohon', null);
                                                $set('pekerjaan', null);
                                                return;
                                            }

                                            $permohonan = PermohonanInformasi::find($state);

                                            if (!$permohonan) {
                                                \Filament\Notifications\Notification::make()
                                                    ->title('Data tidak ditemukan')
                                                    ->warning()
                                                    ->body('Permohonan tidak ditemukan.')
                                                    ->send();
                                                return;
                                            }

                                            // Double check OPD
                                            $user = static::currentUser();
                                            if (
                                                $user?->perangkat_daerah_id &&
                                                (int) $permohonan->perangkat_daerah_id
                                                !== (int) $user->perangkat_daerah_id
                                            ) {
                                                \Filament\Notifications\Notification::make()
                                                    ->title('Akses Ditolak')
                                                    ->danger()
                                                    ->body('Permohonan ini bukan milik OPD Anda.')
                                                    ->send();

                                                $set('permohonan_informasi_id', null);
                                                return;
                                            }

                                            // Isi otomatis
                                            $set('nama_pemohon', $permohonan->nama_pemohon ?? '');
                                            $set('alamat_pemohon', $permohonan->alamat_lengkap ?? '');
                                            $set('telepon_pemohon', $permohonan->nomor_whatsapp ?? '');
                                            $set('pekerjaan', $permohonan->pekerjaan ?? '');
                                        }),
                                ])->columns(2),

                            Forms\Components\Section::make('Tujuan Penggunaan Informasi')
                                ->icon('heroicon-o-information-circle')
                                ->schema([
                                    Forms\Components\Textarea::make('tujuan_penggunaan_informasi')
                                        ->label('Tujuan Penggunaan Informasi (untuk Keberatan)')
                                        ->required()
                                        ->rows(3)
                                        ->columnSpanFull(),
                                ]),

                            Forms\Components\Section::make('Identitas Pemohon (Otomatis Terisi)')
                                ->icon('heroicon-o-user')
                                ->schema([
                                    Forms\Components\TextInput::make('nama_pemohon')
                                        ->label('Nama Pemohon')
                                        ->required()
                                        ->readonly(),

                                    Forms\Components\TextInput::make('pekerjaan')
                                        ->label('Pekerjaan')
                                        ->readonly(),

                                    Forms\Components\Textarea::make('alamat_pemohon')
                                        ->label('Alamat Pemohon')
                                        ->required()
                                        ->rows(2)
                                        ->columnSpanFull()
                                        ->readonly(),

                                    Forms\Components\TextInput::make('telepon_pemohon')
                                        ->label('Nomor Telepon Pemohon')
                                        ->tel()
                                        ->readonly(),
                                ])->columns(2),
                        ]),

                    // =============================================
                    // Step 2: Data Kuasa Pemohon
                    // =============================================
                    Forms\Components\Wizard\Step::make('Data Kuasa Pemohon')
                        ->icon('heroicon-o-user-group')
                        ->schema([
                            Forms\Components\Section::make('Identitas Kuasa Pemohon (Opsional)')
                                ->description('Isi jika pengajuan keberatan dikuasakan kepada orang lain.')
                                ->icon('heroicon-o-identification')
                                ->schema([
                                    Forms\Components\TextInput::make('nama_kuasa')
                                        ->label('Nama Kuasa'),

                                    Forms\Components\TextInput::make('telepon_kuasa')
                                        ->label('Telepon Kuasa')
                                        ->tel(),

                                    Forms\Components\Textarea::make('alamat_kuasa')
                                        ->label('Alamat Kuasa')
                                        ->rows(2)
                                        ->columnSpanFull(),

                                    Forms\Components\FileUpload::make('surat_kuasa')
                                        ->label('Upload Surat Kuasa')
                                        ->directory('keberatan-informasi/surat-kuasa')
                                        ->acceptedFileTypes([
                                            'application/pdf',
                                            'image/jpeg',
                                            'image/png',
                                        ])
                                        ->maxSize(2048)
                                        ->visibility('private')
                                        ->disk('minio')
                                        ->helperText('Upload surat kuasa jika dikuasakan (maks. 2MB)'),
                                ])->columns(2),
                        ]),

                    // =============================================
                    // Step 3: Alasan Keberatan
                    // =============================================
                    Forms\Components\Wizard\Step::make('Alasan Keberatan')
                        ->icon('heroicon-o-exclamation-triangle')
                        ->schema([
                            Forms\Components\Section::make('Pilih Alasan Pengajuan Keberatan')
                                ->icon('heroicon-o-list-bullet')
                                ->schema([
                                    Forms\Components\CheckboxList::make('alasan_keberatan')
                                        ->label('Pilih satu atau lebih alasan:')
                                        ->options([
                                            'Permohonan Informasi Ditolak'
                                            => 'Permohonan Informasi Ditolak',
                                            'Informasi Berkala tidak disediakan'
                                            => 'Informasi Berkala tidak disediakan',
                                            'Permintaan informasi tidak ditanggani'
                                            => 'Permintaan informasi tidak ditanggani',
                                            'Informasi Disampaikan Melebihi Jangka waktu'
                                            => 'Informasi Disampaikan Melebihi Jangka waktu',
                                            'Informasi Ditanggapi tidak sebagaimana diminta'
                                            => 'Informasi Ditanggapi tidak sebagaimana diminta',
                                            'Biaya yang dikenakan tidak wajar'
                                            => 'Biaya yang dikenakan tidak wajar',
                                        ])
                                        ->required()
                                        ->columns(2)
                                        ->columnSpanFull(),
                                ]),

                            Forms\Components\Section::make('Status Keberatan')
                                ->icon('heroicon-o-clipboard-document-check')
                                ->schema([
                                    Forms\Components\Select::make('status')
                                        ->label('Status')
                                        ->options([
                                            'pending' => 'Pending',
                                            'diproses' => 'Diproses',
                                            'selesai' => 'Selesai',
                                            'ditolak' => 'Ditolak',
                                        ])
                                        ->default('pending')
                                        ->required()
                                        ->native(false),

                                    Forms\Components\Textarea::make('catatan')
                                        ->label('Catatan Staff')
                                        ->rows(3)
                                        ->placeholder('Tambahkan catatan jika diperlukan')
                                        ->columnSpanFull(),
                                ]),
                        ]),

                ])->columnSpanFull(),
            ]);
    }

    // =========================================================
    // TABLE
    // =========================================================
    public static function table(Table $table): Table
    {
        $user = static::currentUser();

        return $table
            ->columns([
                Tables\Columns\TextColumn::make('permohonanInformasi.no_registrasi')
                    ->label('No. Permohonan')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->copyable()
                    ->copyMessage('Nomor permohonan disalin')
                    ->placeholder('Tidak ada'),

                Tables\Columns\TextColumn::make('permohonanInformasi.perangkatDaerah.nama_perangkat_daerah')
                    ->label('Perangkat Daerah')
                    ->searchable()
                    ->sortable()
                    ->wrap()
                    // Sembunyikan karena staff sudah pasti OPD-nya sendiri
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('nama_pemohon')
                    ->label('Nama Pemohon')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->description(
                        fn($record): string => $record->nik_pemohon ?? '-'
                    ),

                Tables\Columns\TextColumn::make('pekerjaan')
                    ->label('Pekerjaan')
                    ->searchable()
                    ->badge()
                    ->color('info')
                    ->toggleable()
                    ->placeholder('Tidak diisi'),

                Tables\Columns\TextColumn::make('telepon_pemohon')
                    ->label('Telepon')
                    ->searchable()
                    ->icon('heroicon-o-phone')
                    ->copyable()
                    ->placeholder('-'),

                Tables\Columns\TextColumn::make('alasan_keberatan')
                    ->label('Alasan Keberatan')
                    ->searchable()
                    ->badge()
                    ->color(fn(?string $state): string => match ($state) {
                        'Permohonan Informasi Ditolak' => 'danger',
                        'Informasi Berkala tidak disediakan' => 'warning',
                        'Permintaan informasi tidak ditanggani' => 'warning',
                        'Informasi Disampaikan Melebihi Jangka waktu' => 'danger',
                        'Informasi Ditanggapi tidak sebagaimana diminta' => 'warning',
                        'Biaya yang dikenakan tidak wajar' => 'danger',
                        default => 'gray',
                    })
                    ->wrap()
                    ->limit(40),

                Tables\Columns\IconColumn::make('nama_kuasa')
                    ->label('Menggunakan Kuasa')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('gray')
                    ->toggleable(),

                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn(string $state): string => match (strtolower($state)) {
                        'pending' => 'warning',
                        'diproses' => 'info',
                        'selesai', 'diterima' => 'success',
                        'ditolak' => 'danger',
                        default => 'gray',
                    })
                    ->icon(fn(string $state): string => match (strtolower($state)) {
                        'pending' => 'heroicon-o-clock',
                        'diproses' => 'heroicon-o-arrow-path',
                        'selesai', 'diterima' => 'heroicon-o-check-circle',
                        'ditolak' => 'heroicon-o-x-circle',
                        default => 'heroicon-o-question-mark-circle',
                    })
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal Pengajuan')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Terakhir Update')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')

            // Header tabel
            ->heading(
                '📋 Keberatan Informasi OPD: ' .
                ($user?->perangkatDaerah?->nama_perangkat_daerah ?? '-')
            )
            ->description(
                'Menampilkan keberatan informasi khusus untuk perangkat daerah Anda. ' .
                'Staff lain tidak dapat melihat data ini.'
            )

            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'pending' => 'Pending',
                        'diproses' => 'Diproses',
                        'selesai' => 'Selesai',
                        'ditolak' => 'Ditolak',
                    ])
                    ->multiple(),

                Tables\Filters\SelectFilter::make('alasan_keberatan')
                    ->label('Alasan Keberatan')
                    ->options([
                        'Permohonan Informasi Ditolak'
                        => 'Permohonan Informasi Ditolak',
                        'Informasi Berkala tidak disediakan'
                        => 'Informasi Berkala tidak disediakan',
                        'Permintaan informasi tidak ditanggani'
                        => 'Permintaan informasi tidak ditanggani',
                        'Informasi Disampaikan Melebihi Jangka waktu'
                        => 'Informasi Disampaikan Melebihi Jangka waktu',
                        'Informasi Ditanggapi tidak sebagaimana diminta'
                        => 'Informasi Ditanggapi tidak sebagaimana diminta',
                        'Biaya yang dikenakan tidak wajar'
                        => 'Biaya yang dikenakan tidak wajar',
                    ])
                    ->multiple(),

                Tables\Filters\Filter::make('menggunakan_kuasa')
                    ->label('Menggunakan Kuasa')
                    ->query(
                        fn(Builder $query): Builder =>
                        $query->whereNotNull('nama_kuasa')
                    ),

                Tables\Filters\Filter::make('created_at')
                    ->form([
                        Forms\Components\DatePicker::make('created_from')
                            ->label('Dari Tanggal')
                            ->native(false)
                            ->displayFormat('d/m/Y'),
                        Forms\Components\DatePicker::make('created_until')
                            ->label('Sampai Tanggal')
                            ->native(false)
                            ->displayFormat('d/m/Y'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'] ?? null,
                                fn(Builder $q, $date): Builder =>
                                $q->whereDate('created_at', '>=', $date)
                            )
                            ->when(
                                $data['created_until'] ?? null,
                                fn(Builder $q, $date): Builder =>
                                $q->whereDate('created_at', '<=', $date)
                            );
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];
                        if ($data['created_from'] ?? null) {
                            $indicators[] = 'Dari: ' . $data['created_from'];
                        }
                        if ($data['created_until'] ?? null) {
                            $indicators[] = 'Sampai: ' . $data['created_until'];
                        }
                        return $indicators;
                    }),

                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([

                    // Lihat Berkas
                    Tables\Actions\Action::make('lihat_detail')
                        ->label('Lihat Berkas & Detail')
                        ->icon('heroicon-o-document-text')
                        ->color('info')
                        ->modalContent(
                            fn($record) => view(
                                'filament.resources.keberatan-informasi.view-file',
                                ['record' => $record]
                            )
                        )
                        ->modalSubmitAction(false)
                        ->modalCancelActionLabel('Tutup')
                        ->modalWidth('5xl')
                        ->visible(fn($record): bool => static::canView($record)),

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
                        ->label('Ubah Status')
                        ->icon('heroicon-o-arrow-path')
                        ->color('warning')
                        ->visible(fn($record): bool => static::canEdit($record))
                        ->form([
                            Forms\Components\Placeholder::make('info_pemohon')
                                ->label('Pemohon')
                                ->content(
                                    fn($record): string =>
                                    ($record->nama_pemohon ?? '-') .
                                    ' — ' .
                                    ($record->permohonanInformasi?->no_registrasi ?? '-')
                                )
                                ->columnSpanFull(),

                            Forms\Components\Select::make('status')
                                ->label('Status Baru')
                                ->options([
                                    'pending' => 'Pending',
                                    'diproses' => 'Diproses',
                                    'selesai' => 'Selesai',
                                    'ditolak' => 'Ditolak',
                                ])
                                ->default(fn($record): string => $record->status ?? 'pending')
                                ->required()
                                ->native(false),

                            Forms\Components\Textarea::make('catatan')
                                ->label('Catatan')
                                ->placeholder('Tambahkan catatan jika diperlukan')
                                ->rows(3)
                                ->columnSpanFull(),
                        ])
                        ->action(function ($record, array $data): void {
                            // Double check OPD sebelum update
                            if (!static::isRecordMilikOPD($record)) {
                                \Filament\Notifications\Notification::make()
                                    ->title('Akses Ditolak')
                                    ->body('Anda tidak memiliki akses untuk mengubah keberatan ini.')
                                    ->danger()
                                    ->send();
                                return;
                            }

                            $record->update([
                                'status' => $data['status'],
                                'catatan' => $data['catatan'] ?? $record->catatan,
                            ]);

                            \Filament\Notifications\Notification::make()
                                ->title('Status Berhasil Diperbarui')
                                ->body(
                                    'Status keberatan ' .
                                    ($record->permohonanInformasi?->no_registrasi ?? '') .
                                    ' diubah menjadi ' . strtoupper($data['status']) . '.'
                                )
                                ->success()
                                ->send();
                        }),

                    // Delete
                    Tables\Actions\DeleteAction::make()
                        ->icon('heroicon-o-trash')
                        ->visible(fn($record): bool => static::canDelete($record)),
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

                    // Bulk Ubah Status
                    Tables\Actions\BulkAction::make('ubah_status_bulk')
                        ->label('Ubah Status Terpilih')
                        ->icon('heroicon-o-arrow-path')
                        ->color('warning')
                        ->form([
                            Forms\Components\Select::make('status')
                                ->label('Status Baru')
                                ->options([
                                    'pending' => 'Pending',
                                    'diproses' => 'Diproses',
                                    'selesai' => 'Selesai',
                                    'ditolak' => 'Ditolak',
                                ])
                                ->required()
                                ->native(false),
                        ])
                        ->action(function ($records, array $data): void {
                            $user = static::currentUser();
                            $updated = 0;
                            $skipped = 0;

                            $records->each(function ($record) use ($data, $user, &$updated, &$skipped) {
                                // Pastikan hanya update record OPD staff ini
                                if (!static::isRecordMilikOPD($record)) {
                                    $skipped++;
                                    return;
                                }
                                $record->update(['status' => $data['status']]);
                                $updated++;
                            });

                            $message = "{$updated} keberatan berhasil diperbarui menjadi: " . strtoupper($data['status']) . ".";

                            if ($skipped > 0) {
                                $message .= " {$skipped} keberatan dilewati karena bukan milik OPD Anda.";
                            }

                            \Filament\Notifications\Notification::make()
                                ->title('Status Berhasil Diperbarui')
                                ->body($message)
                                ->success()
                                ->send();
                        })
                        ->deselectRecordsAfterCompletion(),
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
            'index' => Pages\ListKeberatanInformasis::route('/'),
            'create' => Pages\CreateKeberatanInformasi::route('/create'),
            'edit' => Pages\EditKeberatanInformasi::route('/{record}/edit'),
        ];
    }
}