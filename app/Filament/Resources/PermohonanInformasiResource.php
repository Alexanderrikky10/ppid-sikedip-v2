<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PermohonanInformasiResource\Pages;
use App\Models\PermohonanInformasi;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
class PermohonanInformasiResource extends Resource
{
    protected static ?string $model = PermohonanInformasi::class;

    protected static ?string $navigationGroup = 'PERMOHONAN INFORMASI DAN KEBERATAN INFORMASI';

    protected static ?string $navigationLabel = 'Permohonan Informasi';
    protected static ?string $pluralModelLabel = 'Permohonan Informasi';

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('status', 'diproses')->count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Kolom Kiri (2/3 width)
                Forms\Components\Group::make()
                    ->schema([
                        // Section 1: Informasi Perangkat Daerah & Jenis Permohonan
                        Forms\Components\Section::make('Informasi Permohonan')
                            ->description('Pilih perangkat daerah dan jenis permohonan')
                            ->icon('heroicon-o-document-text')
                            ->schema([
                                Forms\Components\Select::make('perangkat_daerah_id')
                                    ->label('Perangkat Daerah')
                                    ->searchable()
                                    ->relationship('perangkatDaerah', 'nama_perangkat_daerah')
                                    ->preload()
                                    ->required()
                                    ->placeholder('Pilih Perangkat Daerah'),

                                Forms\Components\Select::make('jenis_permohonan')
                                    ->label('Jenis Permohonan')
                                    ->required()
                                    ->options([
                                        'perorangan' => 'Perorangan',
                                        'badan_hukum' => 'Badan Hukum',
                                        'kelompok' => 'Kelompok',
                                    ])
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
                                        'Perempuan' => 'Perempuan',
                                    ])
                                    ->placeholder('Pilih Jenis Kelamin'),

                                Forms\Components\TextInput::make('no_identitas')
                                    ->label('Nomor Identitas (KTP/SIM)')
                                    ->required()
                                    ->maxLength(50)
                                    ->placeholder('Contoh: 1234567890123456')
                                    ->numeric()
                                    ->minLength(16)
                                    ->maxLength(16),

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
                                    ->maxSize(2048)
                                    ->imageEditorAspectRatios([
                                        null,
                                        '16:9',
                                        '4:3',
                                        '1:1',
                                    ])
                                    ->visibility('private')
                                    ->disk('minio')
                                    ->maxSize(2048)
                                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'application/pdf'])
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
                                    ->helperText('Upload dokumen pendukung jika ada ( PDF max 5MB)')
                                    ->visibility('private')
                                    ->disk('minio')
                                    ->acceptedFileTypes(['application/pdf'])
                                    ->directory('dokumen-tambahan')
                                    ->maxSize(5120)
                                    ->columnSpanFull(),

                                Forms\Components\TextInput::make('cara_penyampaian_informasi')
                                    ->label('Cara Penyampaian Informasi')
                                    ->required()
                                    ->placeholder('Pilih Cara Penyampaian')
                                    ->helperText('cara Penyampaian Informasi yang Diinginkan'),
                            ])
                            ->columns(1)
                            ->collapsible(),
                    ])
                    ->columnSpan(['lg' => 2]),

                // Kolom Kanan (1/3 width)
                Forms\Components\Group::make()
                    ->schema([
                        // Section 5: Cara Penyampaian & Status
                        Forms\Components\Section::make('Pengaturan Tindak Lanjut')
                            ->description('Pilih cara menerima informasi')
                            ->icon('heroicon-o-inbox-arrow-down')
                            ->schema([
                                Forms\Components\Select::make('tindak_lanjut')
                                    ->label('Tindak Lanjut')
                                    ->options([
                                        'Email' => 'Dikirim via Email',
                                        'WhatsApp' => 'Dikirim via WhatsApp',
                                        'whatsapp/email' => 'Dikirim via Whatsapp dan Email',
                                    ])
                                    ->placeholder('Pilih Tindak Lanjut'),
                            ])
                            ->columns(1),

                        // Section 6: Status (hanya untuk admin)
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
                                    ->helperText('Status akan diperbarui oleh admin'),

                                Forms\Components\Placeholder::make('created_at')
                                    ->label('Tanggal Dibuat')
                                    ->content(fn($record): string => $record?->created_at?->format('d/m/Y H:i') ?? '-'),

                                Forms\Components\Placeholder::make('updated_at')
                                    ->label('Terakhir Diperbarui')
                                    ->content(fn($record): string => $record?->updated_at?->format('d/m/Y H:i') ?? '-'),
                            ])
                            ->columns(1)
                            ->hidden(fn($operation) => $operation === 'create'),
                    ])
                    ->columnSpan(['lg' => 1]),
            ])
            ->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('no_registrasi')
                    ->label('No. Registrasi')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('nama_pemohon')
                    ->label('Nama Pemohon')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('perangkatDaerah.nama_perangkat_daerah')
                    ->label('Perangkat Daerah')
                    ->sortable()
                    ->searchable()
                    ->wrap(),

                Tables\Columns\TextColumn::make('jenis_permohonan')
                    ->label('Jenis')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'perorangan' => 'success',
                        'badan_hukum' => 'warning',
                        'kelompok' => 'info',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('nomor_whatsapp')
                    ->label('WhatsApp')
                    ->searchable()
                    ->icon('heroicon-o-phone'),

                Tables\Columns\TextColumn::make('alamat_email')
                    ->label('Email')
                    ->searchable()
                    ->icon('heroicon-o-envelope')
                    ->limit(30),

                Tables\Columns\TextColumn::make('tindak_lanjut')
                    ->label('Tindak Lanjut')
                    ->badge()
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

                Tables\Filters\SelectFilter::make('perangkat_daerah_id')
                    ->label('Perangkat Daerah')
                    ->relationship('perangkatDaerah', 'nama_perangkat_daerah')
                    ->searchable()
                    ->preload(),

                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\Action::make('lihat_dokumen')
                        ->label('Lihat Berkas')
                        ->icon('heroicon-o-paper-clip')
                        ->color('info')
                        ->modalContent(fn($record) => view('filament.resources.permohonan-informasi.view-file', ['record' => $record]))
                        ->modalSubmitAction(false) // Hilangkan tombol "Submit" karena hanya view
                        ->modalCancelActionLabel('Tutup')
                        ->modalWidth('4xl'), // Lebar modal agar PDF enak dibaca
                    Tables\Actions\ViewAction::make()
                        ->icon('heroicon-o-eye'),

                    Tables\Actions\EditAction::make()
                        ->icon('heroicon-o-pencil'),

                    Tables\Actions\Action::make('ubah_status')
                        ->label('Ubah Status & Kirim Notifikasi')
                        ->icon('heroicon-o-arrow-path')
                        ->color('warning')
                        ->form([
                            Forms\Components\Select::make('status')
                                ->label('Status Baru')
                                ->options([
                                    'diproses' => 'Diproses',
                                    'selesai' => 'Selesai',
                                    'ditolak' => 'Ditolak',
                                ])
                                ->default('diproses')
                                ->required()
                                ->native(false),

                            Forms\Components\FileUpload::make('dokumen_informasi')
                                ->label('Dokumen Informasi')
                                ->multiple()
                                ->directory('dokumen-informasi')
                                ->visibility('private')
                                ->disk('minio')
                                ->acceptedFileTypes(['application/pdf', 'image/jpeg', 'image/png'])
                                ->maxSize(5120)
                                ->helperText('Upload dokumen informasi jika ada (maksimal 5MB per file)')
                                ->columnSpanFull(),

                            Forms\Components\Textarea::make('catatan')
                                ->label('Catatan')
                                ->placeholder('Tambahkan catatan jika diperlukan')
                                ->rows(3)
                                ->columnSpanFull(),
                        ])
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

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
