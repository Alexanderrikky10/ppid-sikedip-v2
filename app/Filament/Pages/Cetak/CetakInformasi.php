<?php

namespace App\Filament\Pages\Cetak;

use App\Models\Informasi;
use App\Models\KategoriInformasi;
use App\Models\KategoriJenisInformasi;
use App\Models\KlasifikasiInformasi;
use App\Models\PerangkatDaerah;
use Filament\Forms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Pages\Page;
use Filament\Tables;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class CetakInformasi extends Page implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-printer';
    protected static ?int $navigationSort = 5;
    protected static ?string $navigationGroup = 'INFORMASI DAN CETAK';
    protected static ?string $title = 'Laporan Daftar Informasi';
    protected static string $view = 'filament.pages.cetak.cetak-informasi';

    public $tahun_awal;
    public $tahun_akhir;
    public $perangkat_daerah_id;
    public $klasifikasi_informasi_id;
    public $kategori_jenis_informasi_id;
    public $kategori_informasi_id;
    public $tempat;
    public $tanggal;

    protected function getFormSchema(): array
    {
        $years = range(now()->year, 2017);
        $yearOptions = array_combine($years, $years);

        return [
            Forms\Components\Grid::make(3)
                ->schema([
                    Forms\Components\Group::make()
                        ->schema([
                            Forms\Components\Section::make('Filter Utama Laporan')
                                ->schema([
                                    Forms\Components\Grid::make(2)->schema([
                                        Forms\Components\Select::make('tahun_awal')
                                            ->label('Dari Tahun')
                                            ->options($yearOptions)
                                            ->searchable()
                                            ->preload()
                                            ->live(),
                                        Forms\Components\Select::make('tahun_akhir')
                                            ->label('Sampai Tahun')
                                            ->options($yearOptions)
                                            ->searchable()
                                            ->preload()
                                            ->live()
                                            ->required(),
                                    ]),
                                    Forms\Components\Select::make('perangkat_daerah_id')
                                        ->label('Perangkat Daerah Penerbit')
                                        ->searchable()
                                        ->live()
                                        ->options(function (): array {
                                            // 1. Ambil SEMUA data dulu seperti kode awal Anda
                                            $groupedPerangkatDaerah = PerangkatDaerah::with('kategoriInformasi')
                                                ->whereNotNull('kategori_informasi_id')
                                                ->get()
                                                ->groupBy('kategoriInformasi.nama_kategori');

                                            return $groupedPerangkatDaerah->mapWithKeys(function ($perangkatDaerahs, $kategori) {
                                                $namaKategoriUpper = strtoupper($kategori ?? 'LAINNYA');
                                                $groupLabel = '★★ ' . $namaKategoriUpper;

                                                if (str_contains($namaKategoriUpper, 'PEMERINTAH') || str_contains($namaKategoriUpper, 'PEMKAB')) {

                                                    $perangkatDaerahs = $perangkatDaerahs->where('parent_id', null);

                                                }

                                                // 3. Mapping data menjadi opsi select
                                                $optionsInGroup = $perangkatDaerahs->mapWithKeys(function ($perangkatDaerah) {
                                                    // Opsional: Hapus simbol tree (|-★) jika ingin tampilan bersih
                                                    return [$perangkatDaerah->id => '|-★ ' . $perangkatDaerah->nama_perangkat_daerah];
                                                })->all();

                                                return [$groupLabel => $optionsInGroup];
                                            })->all();
                                        }),
                                ]),
                            Forms\Components\Section::make('Kategori & Klasifikasi')
                                ->schema([
                                    Forms\Components\Select::make('kategori_informasi_id')
                                        ->label('Kategori Informasi')
                                        ->options(KategoriInformasi::pluck('nama_kategori', 'id')->toArray())
                                        ->searchable()
                                        ->live(),
                                    Forms\Components\Select::make('klasifikasi_informasi_id')
                                        ->label('Klasifikasi Informasi Publik')
                                        ->options(KlasifikasiInformasi::pluck('nama_klasifikasi', 'id')->toArray())
                                        ->searchable()
                                        ->live(),
                                    Forms\Components\Select::make('kategori_jenis_informasi_id')
                                        ->label('Kategori Jenis Informasi')
                                        ->options(KategoriJenisInformasi::pluck('nama_kategori', 'id')->toArray())
                                        ->searchable()
                                        ->live(),
                                ])->columns(3),
                        ])->columnSpan(['lg' => 2]),

                    Forms\Components\Group::make()
                        ->schema([
                            Forms\Components\Section::make('Detail Pencetakan')
                                ->schema([
                                    Forms\Components\TextInput::make('tempat')
                                        ->label('Tempat Cetak Informasi')
                                        ->live()
                                        ->default('Pontianak')
                                        ->required(),
                                    Forms\Components\DatePicker::make('tanggal')
                                        ->label('Tanggal Cetak Informasi')
                                        ->live()
                                        ->default(now()),
                                ]),
                        ])->columnSpan(['lg' => 1]),
                ]),
        ];
    }

    protected function applyFilters(Builder $query): Builder
    {
        return $query
            ->when($this->tahun_awal, fn($q) => $q->where('tahun', '>=', $this->tahun_awal))
            ->when($this->tahun_akhir, fn($q) => $q->where('tahun', '<=', $this->tahun_akhir))
            ->when($this->perangkat_daerah_id, fn($q) => $q->where('perangkat_daerah_id', $this->perangkat_daerah_id))
            ->when($this->klasifikasi_informasi_id, fn($q) => $q->where('klasifikasi_informasi_id', $this->klasifikasi_informasi_id))
            ->when($this->kategori_jenis_informasi_id, fn($q) => $q->where('kategori_jenis_informasi_id', $this->kategori_jenis_informasi_id))
            ->when($this->kategori_informasi_id, fn($q) => $q->where('kategori_informasi_id', $this->kategori_informasi_id));
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(function () {
                return $this->applyFilters(Informasi::query()->with([
                    'perangkatDaerah',
                    'klasifikasiInformasi',
                    'kategoriJenisInformasi',
                    'kategoriInformasi'
                ]));
            })
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('tahun')
                    ->label('Tahun')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('judul_informasi')
                    ->label('Judul Informasi')
                    ->searchable()
                    ->sortable()
                    ->wrap()
                    ->limit(50),

                Tables\Columns\TextColumn::make('perangkatDaerah.nama_perangkat_daerah')
                    ->label('Perangkat Daerah')
                    ->searchable()
                    ->sortable()
                    ->wrap(),

                Tables\Columns\TextColumn::make('kategoriInformasi.nama_kategori')
                    ->label('Kategori')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('klasifikasiInformasi.nama_klasifikasi')
                    ->label('Klasifikasi')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('kategoriJenisInformasi.nama_kategori')
                    ->label('Jenis Informasi')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('waktu_tempat')
                    ->label('Waktu & Tempat')
                    ->wrap()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('format_informasi')
                    ->label('Format')
                    ->toggleable(),

                Tables\Columns\TextColumn::make('pj_penerbit_informasi')
                    ->label('PJ Penerbit')
                    ->wrap()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('waktu_penyimpanan')
                    ->label('Masa Penyimpanan')
                    ->toggleable(),

                Tables\Columns\TextColumn::make('tanggal_publikasi')
                    ->label('Tanggal Publikasi')
                    ->date('d/m/Y')
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal Input')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                // Filter tambahan bisa ditambahkan di sini
            ])
            ->headerActions([
                Tables\Actions\Action::make('print')
                    ->label('Cetak Laporan')
                    ->icon('heroicon-o-printer')
                    ->color('warning')
                    ->url(fn() => route('cetak.informasi.laporan', $this->getFilterData()))
                    ->openUrlInNewTab()
                    ->disabled($this->isActionDisabled()),

                Tables\Actions\Action::make('download_pdf')
                    ->label('Download PDF')
                    ->icon('heroicon-o-document-arrow-down')
                    ->color('danger')
                    ->url(fn() => route('cetak.informasi.pdf', $this->getFilterData()))
                    ->openUrlInNewTab()
                    ->disabled($this->isActionDisabled()),

                Tables\Actions\Action::make('download_excel')
                    ->label('Download Excel')
                    ->icon('heroicon-o-table-cells')
                    ->color('success')
                    ->url(fn() => route('cetak.informasi.excel', $this->getFilterData()))
                    ->openUrlInNewTab()
                    ->disabled($this->isActionDisabled()),

                Tables\Actions\Action::make('refresh_page')
                    ->label('Refresh Halaman')
                    ->icon('heroicon-o-arrow-path')
                    ->color('gray')
                    ->action(fn() => $this->dispatch('$refresh')),
            ])
            ->striped()
            ->paginated([10, 25, 50, 100, 'all']);
    }

    protected function getFilterData(): array
    {
        return [
            'tahun_awal' => $this->tahun_awal,
            'tahun_akhir' => $this->tahun_akhir,
            'perangkat_daerah_id' => $this->perangkat_daerah_id,
            'klasifikasi_informasi_id' => $this->klasifikasi_informasi_id,
            'kategori_jenis_informasi_id' => $this->kategori_jenis_informasi_id,
            'kategori_informasi_id' => $this->kategori_informasi_id,
            'tempat' => $this->tempat,
            'tanggal' => $this->tanggal,
        ];
    }

    protected function isActionDisabled(): bool
    {
        return !$this->tahun_akhir || !$this->tempat;
    }
}