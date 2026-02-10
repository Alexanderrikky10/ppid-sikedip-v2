<?php

namespace App\Filament\Pages\Informasi;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Pages\Page;
use App\Models\Informasi;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use App\Models\KategoriInformasi;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Contracts\HasTable;
use Filament\Notifications\Notification;
use Filament\Forms\Components\FileUpload;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Enums\ActionsPosition;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Concerns\InteractsWithTable;

class InformasiPemkabKota extends Page implements HasForms, HasTable
{
    use InteractsWithForms, InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-building-office';
    protected static ?string $navigationLabel = 'Informasi Pemkab/Kota';
    protected static ?string $navigationGroup = 'INFORMASI DAN CETAK';
    protected static ?int $navigationSort = 2;
    protected static string $view = 'filament.pages.informasi.informasi-pemkab-kota';

    // Properti Data Form
    public ?array $data = [];

    // State untuk menyembunyikan/menampilkan form
    public bool $showForm = false;

    // ID Khusus Pemkab/Kota (ID = 2)
    public int $kategoriInformasiId = 2;

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
                // Filter hanya data Pemkab/Kota (ID 2)
                Informasi::query()->where('kategori_informasi_id', $this->kategoriInformasiId)
            )
            ->actionsPosition(ActionsPosition::BeforeColumns)

            ->columns([
                // Kolom 1: Judul
                Tables\Columns\TextColumn::make('judul_informasi')
                    ->label('Judul Informasi')
                    ->searchable()
                    ->description(fn(Informasi $record) => 'Tahun: ' . $record->tahun)
                    ->weight('bold')
                    ->wrap(),

                // Kolom 2: Perangkat Daerah / Pemkab (Badge Biru)
                Tables\Columns\TextColumn::make('perangkatDaerah.nama_perangkat_daerah')
                    ->label('Pemkab/Kota')
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
                        ->form($this->getFormSchema()) // Reuse schema form
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
                    Forms\Components\Section::make('Detail Informasi Pemkab/Kota')
                        ->description('Informasi Pemkab/Kota yang akan dipublikasikan')
                        ->icon('heroicon-o-document-text')
                        ->schema([
                            Forms\Components\TextInput::make('judul_informasi')
                                ->label('Judul Informasi')
                                ->required()
                                ->maxLength(255)
                                ->placeholder('Masukkan judul informasi yang jelas dan deskriptif')
                                ->live(onBlur: true)
                                ->afterStateUpdated(fn($set, ?string $state) => $set('slug', Str::slug($state))),

                            Forms\Components\Textarea::make('ringkasan')
                                ->label('Ringkasan')
                                ->required()
                                ->rows(4)
                                ->placeholder('Tulis ringkasan singkat tentang informasi ini')
                                ->columnSpanFull(),

                            Forms\Components\MarkdownEditor::make('penjelasan')
                                ->label('Penjelasan Lengkap')
                                ->required()
                                ->columnSpanFull(),

                            FileUpload::make('media')
                                ->label('Lampiran Media')
                                ->directory('informasi-pemkab-kota')
                                ->visibility('private')
                                ->disk('minio')
                                ->maxSize(10240) // 10 MB
                                ->columnSpanFull(),
                        ])
                        ->columns(1),

                    Forms\Components\Section::make('Penanggung Jawab & Administrasi')
                        ->schema([
                            Forms\Components\TextInput::make('pj_penerbit_informasi')
                                ->label('Penanggung Jawab Penerbit')
                                ->required()
                                ->datalist(fn() => Informasi::where('kategori_informasi_id', $this->kategoriInformasiId)->pluck('pj_penerbit_informasi')->unique()->toArray()),

                            Forms\Components\TextInput::make('pejabat_pj')
                                ->label('Pejabat Penanggung Jawab')
                                ->required()
                                ->datalist(fn() => Informasi::where('kategori_informasi_id', $this->kategoriInformasiId)->pluck('pejabat_pj')->unique()->toArray()),

                            Forms\Components\TextInput::make('waktu_tempat')
                                ->label('Waktu & Tempat')
                                ->required()
                                ->placeholder('Contoh: Setiap hari kerja, 08.00-16.00 WIB'),

                            Forms\Components\Select::make('format_informasi')
                                ->label('Format Informasi')
                                ->required()
                                ->options([
                                    'Hard Copy' => 'Hard Copy',
                                    'Soft Copy' => 'Soft Copy',
                                    'Hard Copy & Soft Copy' => 'Hard Copy & Soft Copy',
                                ]),

                            Forms\Components\TextInput::make('waktu_penyimpanan')
                                ->label('Jangka Waktu Penyimpanan')
                                ->required(),

                            Forms\Components\TextInput::make('slug')
                                ->label('Slug URL')
                                ->required()
                                ->readOnly()
                                ->columnSpanFull(),
                        ])
                        ->columns(2),
                ])
                ->columnSpan(['lg' => 2]),

            Forms\Components\Group::make()
                ->schema([
                    Forms\Components\Section::make('Klasifikasi & Metadata')
                        ->schema([
                            Forms\Components\Placeholder::make('kategori_info')
                                ->label(false)
                                ->content(function () {
                                    $kategori = KategoriInformasi::find($this->kategoriInformasiId);
                                    return new \Illuminate\Support\HtmlString('
                                        <div class="flex items-center gap-2 p-3 bg-blue-50 dark:bg-blue-900/20 rounded-lg border border-blue-200 dark:border-blue-800">
                                            <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            <div>
                                                <p class="text-sm font-semibold text-blue-900 dark:text-blue-100">Kategori: ' . ($kategori?->nama_kategori ?? 'Pemkab/Kota') . '</p>
                                                <p class="text-xs text-blue-700 dark:text-blue-300">Otomatis terisi untuk halaman ini</p>
                                            </div>
                                        </div>
                                    ');
                                }),

                            Forms\Components\Select::make('tahun')
                                ->options(array_combine(range(now()->year, 2017), range(now()->year, 2017)))
                                ->required()
                                ->default(now()->year),

                            Forms\Components\Select::make('perangkat_daerah_id')
                                ->label('OPD Penerbit')
                                ->relationship(
                                    name: 'perangkatDaerah',
                                    titleAttribute: 'nama_perangkat_daerah',
                                    // ✅ Filter khusus ID 2 (Pemkab/Kota)
                                    modifyQueryUsing: fn(Builder $query) => $query->where('kategori_informasi_id', $this->kategoriInformasiId)
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
                ->body('Informasi Pemkab/Kota berhasil ditambahkan.')
                ->send();

            $this->resetForm();
            $this->showForm = false; // Kembali ke tampilan tabel
        } catch (\Exception $e) {
            Notification::make()
                ->title('Gagal Menyimpan')
                ->danger()
                ->body('Terjadi kesalahan: ' . $e->getMessage())
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