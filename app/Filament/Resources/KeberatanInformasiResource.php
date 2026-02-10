<?php

namespace App\Filament\Resources;

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
use App\Filament\Resources\KeberatanInformasiResource\Pages;


class KeberatanInformasiResource extends Resource
{
    protected static ?string $model = KeberatanInformasi::class;

    protected static ?string $navigationGroup = 'PERMOHONAN INFORMASI DAN KEBERATAN INFORMASI';
    protected static ?string $navigationLabel = 'Keberatan Informasi';
    protected static ?string $pluralModelLabel = 'Keberatan Informasi';

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Wizard::make([
                    Forms\Components\Wizard\Step::make('Data Permohonan')
                        ->schema([
                            Forms\Components\Section::make('Cari Permohonan yang Diajukan Keberatan')
                                ->schema([
                                    // 1. Input NIK
                                    Forms\Components\TextInput::make('nik_pemohon')
                                        ->label('NIK Pemohon')
                                        ->required()
                                        ->numeric()
                                        ->minLength(16)
                                        ->maxLength(16)
                                        ->helperText('Masukkan 16 digit NIK pemohon untuk mencari data permohonan.')
                                        ->live(onBlur: true),

                                    // 2. Select untuk No. Registrasi
                                    Forms\Components\Select::make('permohonan_informasi_id')
                                        ->label('Pilih No. Registrasi Permohonan')
                                        ->required()
                                        ->options(function (Get $get): array {
                                            $nik = $get('nik_pemohon');

                                            // Validasi NIK
                                            if (empty($nik) || strlen($nik) !== 16) {
                                                return [];
                                            }

                                            // Cari permohonan berdasarkan NIK
                                            return PermohonanInformasi::where('no_identitas', $nik)
                                                ->orderBy('created_at', 'desc')
                                                ->pluck('no_registrasi', 'id')
                                                ->toArray();
                                        })
                                        ->searchable()
                                        ->placeholder('Pilih No. Registrasi setelah mengisi NIK')
                                        ->live()
                                        // 3. PERBAIKAN DI SINI - Tambahkan type hint dan null check
                                        ->afterStateUpdated(function (Set $set, Get $get, ?string $state): void {
                                            // Jika pilihan dikosongkan
                                            if (blank($state)) {
                                                $set('nama_pemohon', null);
                                                $set('alamat_pemohon', null);
                                                $set('telepon_pemohon', null);
                                                $set('pekerjaan', null);
                                                return;
                                            }

                                            // Cari permohonan dengan null safety
                                            $permohonan = PermohonanInformasi::find($state);

                                            // Cek apakah data ditemukan
                                            if (!$permohonan) {
                                                // Notifikasi jika data tidak ditemukan
                                                \Filament\Notifications\Notification::make()
                                                    ->title('Data tidak ditemukan')
                                                    ->warning()
                                                    ->body('Permohonan dengan ID tersebut tidak ditemukan.')
                                                    ->send();
                                                return;
                                            }

                                            // Isi otomatis field-field dengan null coalescing
                                            $set('nama_pemohon', $permohonan->nama_pemohon ?? '');
                                            $set('alamat_pemohon', $permohonan->alamat_lengkap ?? '');
                                            $set('telepon_pemohon', $permohonan->nomor_whatsapp ?? '');
                                            $set('pekerjaan', $permohonan->pekerjaan ?? '');
                                        }),
                                ])->columns(2),

                            Forms\Components\Section::make('Tujuan Penggunaan Informasi')
                                ->schema([
                                    Forms\Components\Textarea::make('tujuan_penggunaan_informasi')
                                        ->label('Tujuan Penggunaan Informasi (untuk Keberatan)')
                                        ->required()
                                        ->rows(3)
                                        ->columnSpanFull(),
                                ]),

                            Forms\Components\Section::make('Identitas Pemohon (Otomatis Terisi)')
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

                    Forms\Components\Wizard\Step::make('Data Kuasa Pemohon')
                        ->schema([
                            Forms\Components\Section::make('Identitas Kuasa Pemohon (Opsional)')
                                ->description('Isi jika pengajuan keberatan dikuasakan kepada orang lain.')
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
                                        ->directory('keberatan-informasi')
                                        ->acceptedFileTypes(['application/pdf', 'image/jpeg', 'image/png'])
                                        ->maxSize(2048)
                                        ->visibility('private')
                                        ->disk('minio')
                                        ->requiredIf('nama_kuasa', 'filled'),
                                ])->columns(2),
                        ]),

                    Forms\Components\Wizard\Step::make('Alasan Keberatan')
                        ->schema([
                            Forms\Components\Section::make('Pilih Alasan Pengajuan Keberatan')
                                ->schema([
                                    Forms\Components\CheckboxList::make('alasan_keberatan')
                                        ->label('Pilih satu atau lebih alasan:')
                                        ->options([
                                            'Permohonan Informasi Ditolak' => 'Permohonan Informasi Ditolak',
                                            'Informasi Berkala tidak disediakan' => 'Informasi Berkala tidak disediakan',
                                            'Permintaan informasi tidak ditanggani' => 'Permintaan informasi tidak ditanggani',
                                            'Informasi Disampaikan Melebihi Jangka waktu' => 'Informasi Disampaikan Melebihi Jangka waktu',
                                            'Informasi Ditanggapi tidak sebagaimana diminta' => 'Informasi Ditanggapi tidak sebagaimana diminta',
                                            'Biaya yang dikenakan tidak wajar' => 'Biaya yang dikenakan tidak wajar',
                                        ])
                                        ->required()
                                        ->columns(2),
                                ]),
                            Forms\Components\Section::make('Status Keberatan')
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
                                        ->required(),
                                    Forms\Components\Textarea::make('catatan')
                                        ->label('Catatan Admin')
                                        ->rows(3)
                                        ->columnSpanFull(),
                                ]),
                        ]),

                ])->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
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

                Tables\Columns\TextColumn::make('nama_pemohon')
                    ->label('Nama Pemohon')
                    ->searchable()
                    ->sortable()
                    ->description(fn($record): string => $record->nik_pemohon ?? '-'),

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
                    ->limit(30),

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
                        'pending', 'menunggu' => 'warning',
                        'diproses' => 'info',
                        'diterima', 'selesai' => 'success',
                        'ditolak' => 'danger',
                        default => 'gray',
                    })
                    ->icon(fn(string $state): string => match (strtolower($state)) {
                        'pending', 'menunggu' => 'heroicon-o-clock',
                        'diproses' => 'heroicon-o-arrow-path',
                        'diterima', 'selesai' => 'heroicon-o-check-circle',
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

                Tables\Columns\TextColumn::make('deleted_at')
                    ->label('Dihapus')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
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
                        'Permohonan Informasi Ditolak' => 'Permohonan Informasi Ditolak',
                        'Informasi Berkala tidak disediakan' => 'Informasi Berkala tidak disediakan',
                        'Permintaan informasi tidak ditanggani' => 'Permintaan informasi tidak ditanggani',
                        'Informasi Disampaikan Melebihi Jangka waktu' => 'Informasi Disampaikan Melebihi Jangka waktu',
                        'Informasi Ditanggapi tidak sebagaimana diminta' => 'Informasi Ditanggapi tidak sebagaimana diminta',
                        'Biaya yang dikenakan tidak wajar' => 'Biaya yang dikenakan tidak wajar',
                    ])
                    ->multiple(),

                Tables\Filters\Filter::make('menggunakan_kuasa')
                    ->label('Menggunakan Kuasa')
                    ->query(fn(Builder $query): Builder => $query->whereNotNull('nama_kuasa')),

                Tables\Filters\Filter::make('created_at')
                    ->form([
                        Forms\Components\DatePicker::make('created_from')
                            ->label('Dari Tanggal'),
                        Forms\Components\DatePicker::make('created_until')
                            ->label('Sampai Tanggal'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'] ?? null,
                                fn(Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['created_until'] ?? null,
                                fn(Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    }),

                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\Action::make('lihat_detail')
                        ->label('Lihat Berkas & Detail')
                        ->icon('heroicon-o-document-text') // Icon dokumen
                        ->color('info')
                        ->modalContent(fn($record) => view('filament.resources.keberatan-informasi.view-file', ['record' => $record]))
                        ->modalSubmitAction(false) // Read only
                        ->modalCancelActionLabel('Tutup')
                        ->modalWidth('5xl'), // Lebar ekstra karena ada info text + file
                    Tables\Actions\ViewAction::make()
                        ->icon('heroicon-o-eye'),

                    Tables\Actions\EditAction::make()
                        ->icon('heroicon-o-pencil'),

                    Tables\Actions\Action::make('ubah_status')
                        ->label('Ubah Status')
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
                                ->required(),

                            Forms\Components\Textarea::make('catatan')
                                ->label('Catatan')
                                ->placeholder('Tambahkan catatan jika diperlukan')
                                ->rows(3),
                        ])
                        ->action(function ($record, array $data): void {
                            $record->update([
                                'status' => $data['status'],
                                'catatan' => $data['catatan'] ?? $record->catatan,
                            ]);

                            \Filament\Notifications\Notification::make()
                                ->title('Status berhasil diperbarui')
                                ->success()
                                ->body("Status keberatan diubah menjadi: {$data['status']}")
                                ->send();
                        }),

                    Tables\Actions\DeleteAction::make()
                        ->icon('heroicon-o-trash'),
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

                    Tables\Actions\BulkAction::make('ubah_status_bulk')
                        ->label('Ubah Status')
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
                                ->required(),
                        ])
                        ->action(function ($records, array $data): void {
                            $records->each->update(['status' => $data['status']]);

                            \Filament\Notifications\Notification::make()
                                ->title('Status berhasil diperbarui')
                                ->success()
                                ->body(count($records) . " keberatan berhasil diperbarui menjadi: {$data['status']}")
                                ->send();
                        })
                        ->deselectRecordsAfterCompletion(),
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
            'index' => Pages\ListKeberatanInformasis::route('/'),
            'create' => Pages\CreateKeberatanInformasi::route('/create'),
            'edit' => Pages\EditKeberatanInformasi::route('/{record}/edit'),
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
