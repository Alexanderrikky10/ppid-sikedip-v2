<?php

namespace App\Filament\Pages\Informasi;

use Filament\Pages\Page;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use App\Models\Informasi;
use App\Models\KategoriInformasi;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Builder; 

class InformasiBumd extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-building-library';
    protected static ?string $navigationLabel = 'Informasi BUMD';
    protected static ?string $navigationGroup = 'INFORMASI DAN CETAK';
    protected static ?int $navigationSort = 4;
    protected static string $view = 'filament.pages.informasi.informasi-bumd';

    public ?array $data = [];
    public $informasiList = [];
    public bool $showForm = false; // State untuk sembunyikan form

    // ID Kategori BUMD (ID = 3)
    public int $kategoriInformasiId = 3;

    public function mount(): void
    {
        $this->resetForm();
        $this->loadInformasi();
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

    public function form(Form $form): Form
    {
        return $form
            ->model(Informasi::class)
            ->schema([
                Forms\Components\Hidden::make('kategori_informasi_id')
                    ->default(3),

                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make('Detail Informasi BUMD')
                            ->description('Informasi BUMD yang akan dipublikasikan')
                            ->icon('heroicon-o-document-text')
                            ->schema([
                                Forms\Components\TextInput::make('judul_informasi')
                                    ->label('Judul Informasi')
                                    ->required()
                                    ->maxLength(255)
                                    ->placeholder('Masukkan judul informasi yang jelas dan deskriptif')
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(fn($set, ?string $state) => $set('slug', Str::slug($state)))
                                    ->helperText('Judul akan otomatis menghasilkan slug URL'),

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

                                Forms\Components\FileUpload::make('media')
                                    ->label('Lampiran Media')
                                    ->disk('minio')
                                    ->directory('informasi-bumd')
                                    ->visibility('private')
                                    ->required()
                                    ->columnSpanFull(),
                            ])
                            ->columns(1),

                        Forms\Components\Section::make('Penanggung Jawab & Administrasi')
                            ->schema([
                                Forms\Components\TextInput::make('pj_penerbit_informasi')
                                    ->label('Penanggung Jawab Penerbit')
                                    ->required()
                                    ->datalist(fn() => Informasi::where('kategori_informasi_id', 3)->pluck('pj_penerbit_informasi')->unique()->toArray()),

                                Forms\Components\TextInput::make('pejabat_pj')
                                    ->label('Pejabat Penanggung Jawab')
                                    ->required()
                                    ->datalist(fn() => Informasi::where('kategori_informasi_id', 3)->pluck('pejabat_pj')->unique()->toArray()),

                                Forms\Components\TextInput::make('waktu_tempat')
                                    ->label('Waktu & Tempat')
                                    ->required(),

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
                                        $kategori = KategoriInformasi::find(2);
                                        return new \Illuminate\Support\HtmlString('
                                            <div class="flex items-center gap-2 p-3 bg-blue-50 dark:bg-blue-900/20 rounded-lg border border-blue-200 dark:border-blue-800">
                                                <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                <div>
                                                    <p class="text-sm font-semibold text-blue-900 dark:text-blue-100">Kategori: ' . ($kategori?->nama_kategori ?? 'BUMD') . '</p>
                                                    <p class="text-xs text-blue-700 dark:text-blue-300">Otomatis terisi untuk halaman ini</p>
                                                </div>
                                            </div>
                                        ');
                                    }),
                                Forms\Components\Select::make('tahun')
                                    ->options(array_combine(range(now()->year, 2017), range(now()->year, 2017)))
                                    ->required()
                                    ->default(now()->year),

                                // ✅ PERBAIKAN DI SINI:
                                // Menggunakan "Builder $query" agar Laravel mengenali tipe datanya
                                Forms\Components\Select::make('perangkat_daerah_id')
                                    ->label('BUMD Penerbit')
                                    ->relationship(
                                        name: 'perangkatDaerah',
                                        titleAttribute: 'nama_perangkat_daerah',
                                        modifyQueryUsing: fn(Builder $query) => $query->where('kategori_informasi_id', 3)
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
                            ])
                            ->columns(1),
                    ])
                    ->columnSpan(['lg' => 1]),
            ])
            ->columns(3)
            ->statePath('data');
    }

    public function create(): void
    {
        $data = $this->form->getState();
        $data['kategori_informasi_id'] = 3;

        try {
            Informasi::create($data);

            Notification::make()
                ->title('Berhasil Disimpan')
                ->success()
                ->body('Informasi BUMD berhasil ditambahkan.')
                ->send();

            $this->resetForm();
            $this->showForm = false; // Sembunyikan form kembali
            $this->loadInformasi();

        } catch (\Exception $e) {
            Notification::make()
                ->title('Gagal Menyimpan')
                ->danger()
                ->body('Terjadi kesalahan: ' . $e->getMessage())
                ->send();
        }
    }

    protected function loadInformasi(): void
    {
        $this->informasiList = Informasi::with([
            'perangkatDaerah',
            'klasifikasiInformasi',
            'kategoriJenisInformasi',
            'kategoriInformasi'
        ])
            ->where('kategori_informasi_id', 3)
            ->orderBy('tanggal_publikasi', 'desc')
            ->limit(10)
            ->get();
    }
}