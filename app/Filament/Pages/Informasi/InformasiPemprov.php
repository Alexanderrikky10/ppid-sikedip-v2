<?php

namespace App\Filament\Pages\Informasi;

use Filament\Pages\Page;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Enums\ActionsPosition;
use Filament\Notifications\Notification;
use App\Models\Informasi;
use App\Models\KategoriInformasi;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Builder;

class InformasiPemprov extends Page implements HasForms, HasTable
{
    use InteractsWithForms, InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-building-office-2';
    protected static ?string $navigationLabel = 'Informasi Pemprov';
    protected static ?string $navigationGroup = 'INFORMASI DAN CETAK';
    protected static ?int $navigationSort = 1;
    protected static string $view = 'filament.pages.informasi.informasi-pemprov';

    // Properti Data Form
    public ?array $data = [];

    // State untuk menyembunyikan/menampilkan form
    public bool $showForm = false;

    // ID Khusus Pemprov (ID = 1)
    public int $kategoriInformasiId = 1;

    public function mount(): void
    {
        $this->resetForm();
    }

    // ==========================================
    // 1. KONFIGURASI TABEL
    // ==========================================
    public function table(Table $table): Table
    {
        return $table
            ->query(
                // Filter hanya data Pemprov
                Informasi::query()->where('kategori_informasi_id', $this->kategoriInformasiId)
            )
            // 👇 MEMINDAHKAN AKSI KE KIRI (Before Columns)
            ->actionsPosition(ActionsPosition::BeforeColumns)

            ->columns([
                // Kolom 1: Judul
                Tables\Columns\TextColumn::make('judul_informasi')
                    ->label('Judul Informasi')
                    ->searchable()
                    ->description(fn(Informasi $record) => 'Tahun: ' . $record->tahun)
                    ->weight('bold')
                    ->wrap(),

                // Kolom 2: Perangkat Daerah (Badge Biru)
                Tables\Columns\TextColumn::make('perangkatDaerah.nama_perangkat_daerah')
                    ->label('Perangkat Daerah')
                    ->badge()
                    ->color('info')
                    ->searchable()
                    ->wrap(),

                // Kolom 3: Klasifikasi (Badge Abu)
                Tables\Columns\TextColumn::make('klasifikasiInformasi.nama_klasifikasi')
                    ->label('Klasifikasi')
                    ->badge()
                    ->color('gray')
                    ->wrap(),

                // Kolom 4: Jenis Informasi (Badge Kuning)
                Tables\Columns\TextColumn::make('kategoriJenisInformasi.nama_kategori')
                    ->label('Jenis')
                    ->badge()
                    ->color('warning')
                    ->wrap(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('tahun')
                    ->options(array_combine(range(now()->year, 2017), range(now()->year, 2017))),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\EditAction::make()
                        ->label('Ubah Data')
                        ->form($this->getFormSchema()) // Reuse schema
                        ->modalWidth('4xl'),

                    Tables\Actions\DeleteAction::make()
                        ->label('Hapus'),
                ])
                    ->link()
                    ->icon('heroicon-m-ellipsis-vertical')
                    ->label(''),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->headerActions([
                // Tombol Tambah di Atas Tabel
                Tables\Actions\Action::make('create')
                    ->label('Tambah Informasi')
                    ->icon('heroicon-o-plus')
                    ->action(fn() => $this->openForm())
                    ->button(),
            ]);
    }

    // ==========================================
    // 2. SCHEMA FORM (Reusable)
    // ==========================================
    public function getFormSchema(): array
    {
        return [
            Forms\Components\Hidden::make('kategori_informasi_id')
                ->default($this->kategoriInformasiId),

            Forms\Components\Group::make()
                ->schema([
                    Forms\Components\Section::make('Detail Informasi Pemprov')
                        ->schema([
                            Forms\Components\TextInput::make('judul_informasi')
                                ->label('Judul Informasi')
                                ->required()
                                ->maxLength(255)
                                ->live(onBlur: true)
                                ->afterStateUpdated(fn($set, ?string $state) => $set('slug', Str::slug($state))),

                            Forms\Components\Textarea::make('ringkasan')
                                ->label('Ringkasan')
                                ->required()
                                ->rows(3)
                                ->columnSpanFull(),

                            Forms\Components\MarkdownEditor::make('penjelasan')
                                ->label('Penjelasan Lengkap')
                                ->required()
                                ->columnSpanFull(),

                            Forms\Components\FileUpload::make('media')
                                ->label('Lampiran Media')
                                ->disk('public')
                                ->directory('informasi-pemprov')
                                ->visibility('private')
                                ->required()
                                ->columnSpanFull(),
                        ])
                        ->columns(1),

                    Forms\Components\Section::make('Penanggung Jawab')
                        ->schema([
                            Forms\Components\TextInput::make('pj_penerbit_informasi')
                                ->label('PJ Penerbit')
                                ->required(),
                            Forms\Components\TextInput::make('pejabat_pj')
                                ->label('Pejabat PJ')
                                ->required(),
                            Forms\Components\TextInput::make('waktu_tempat')
                                ->label('Waktu & Tempat')
                                ->placeholder('Contoh: Hari Kerja, 08.00 - 16.00')
                                ->required(),
                            Forms\Components\Select::make('format_informasi')
                                ->options([
                                    'Hard Copy' => 'Hard Copy',
                                    'Soft Copy' => 'Soft Copy',
                                    'Hard Copy & Soft Copy' => 'Hard Copy & Soft Copy'
                                ])
                                ->required(),
                            Forms\Components\TextInput::make('waktu_penyimpanan')
                                ->label('Penyimpanan')
                                ->placeholder('Contoh: Selama Berlaku')
                                ->required(),
                            Forms\Components\TextInput::make('slug')
                                ->readOnly()
                                ->columnSpanFull(),
                        ])
                        ->columns(2),
                ])
                ->columnSpan(['lg' => 2]),

            Forms\Components\Group::make()
                ->schema([
                    Forms\Components\Section::make('Klasifikasi')
                        ->schema([
                            Forms\Components\Select::make('tahun')
                                ->options(array_combine(range(now()->year, 2017), range(now()->year, 2017)))
                                ->required()
                                ->default(now()->year),

                            Forms\Components\Select::make('perangkat_daerah_id')
                                ->label('OPD')
                                ->relationship(
                                    name: 'perangkatDaerah',
                                    titleAttribute: 'nama_perangkat_daerah',
                                    modifyQueryUsing: fn(Builder $query) => $query->where('kategori_informasi_id', 1)
                                )
                                ->searchable()
                                ->preload()
                                ->required(),

                            Forms\Components\Select::make('klasifikasi_informasi_id')
                                ->relationship('klasifikasiInformasi', 'nama_klasifikasi')
                                ->searchable()
                                ->preload()
                                ->required(),

                            Forms\Components\Select::make('kategori_jenis_informasi_id')
                                ->relationship('kategoriJenisInformasi', 'nama_kategori')
                                ->searchable()
                                ->preload()
                                ->required(),

                            Forms\Components\DateTimePicker::make('tanggal_publikasi')
                                ->required()
                                ->default(now()),
                        ]),
                ])
                ->columnSpan(['lg' => 1]),
        ];
    }

    // ==========================================
    // 3. LOGIKA PAGE & FORM HANDLER
    // ==========================================

    public function form(Form $form): Form
    {
        return $form
            ->model(Informasi::class)
            ->schema($this->getFormSchema())
            ->columns(3)
            ->statePath('data');
    }

    public function create(): void
    {
        $data = $this->form->getState();
        $data['kategori_informasi_id'] = $this->kategoriInformasiId;

        try {
            Informasi::create($data);

            Notification::make()
                ->title('Berhasil Disimpan')
                ->success()
                ->body('Informasi berhasil ditambahkan.')
                ->send();

            $this->resetForm();
            $this->showForm = false;
        } catch (\Exception $e) {
            Notification::make()
                ->title('Gagal Menyimpan')
                ->danger()
                ->body('Error: ' . $e->getMessage())
                ->send();
        }
    }

    public function resetForm(): void
    {
        $this->form->fill([
            'kategori_informasi_id' => $this->kategoriInformasiId,
            'tahun' => now()->year,
            'tanggal_publikasi' => now(),
        ]);
    }

    public function openForm(): void
    {
        $this->resetForm();
        $this->showForm = true;
    }

    public function closeForm(): void
    {
        $this->showForm = false;
    }
}