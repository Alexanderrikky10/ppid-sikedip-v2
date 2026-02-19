<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RespondenResource\Pages;
use App\Models\Responden;
use App\Models\SurveyKualitas;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class RespondenResource extends Resource
{
    protected static ?string $model = Responden::class;

    protected static ?string $navigationGroup = 'MANAJEMEN SURVEY';

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';

    protected static ?string $navigationLabel = 'Survey Kualitas';

    protected static ?string $modelLabel = 'Survey Kualitas';

    protected static ?string $pluralModelLabel = 'Survey Kualitas';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Responden')
                    ->description('Data diri responden survey')
                    ->icon('heroicon-o-user')
                    ->schema([
                        Forms\Components\TextInput::make('nama_responden')
                            ->label('Nama Lengkap')
                            ->required()
                            ->maxLength(255)
                            ->columnSpan(2),

                        Forms\Components\TextInput::make('usia_responden')
                            ->label('Usia')
                            ->required()
                            ->numeric()
                            ->maxLength(3),

                        Forms\Components\Select::make('jenis_kelamin_responden')
                            ->label('Jenis Kelamin')
                            ->options([
                                'Laki-laki' => 'Laki-laki',
                                'Perempuan' => 'Perempuan',
                            ])
                            ->required(),

                        Forms\Components\Select::make('pendidikan_responden')
                            ->label('Pendidikan Terakhir')
                            ->options([
                                'SD/MI sederajat' => 'SD/MI sederajat',
                                'SMP/MTs sederajat' => 'SMP/MTs sederajat',
                                'SMA/SMK/MA sederajat' => 'SMA/SMK/MA sederajat',
                                'D1/D3' => 'D1/D3',
                                'D4/S1' => 'D4/S1',
                                'S2/S3' => 'S2/S3',
                                'lainya' => 'Lainnya',
                            ])
                            ->required(),

                        Forms\Components\Select::make('pekerjaan_responden')
                            ->label('Pekerjaan')
                            ->options([
                                'Pelajar/Mahasiswa' => 'Pelajar/Mahasiswa',
                                'Pegawai Negeri Sipil' => 'Pegawai Negeri Sipil',
                                'TNI/POLRI' => 'TNI/POLRI',
                                'Karyawan Swasta' => 'Karyawan Swasta',
                                'Wirausaha' => 'Wirausaha',
                                'Lainnya' => 'Lainnya',
                            ])
                            ->required(),

                        Forms\Components\TextInput::make('no_telp_responden')
                            ->label('No. Telepon/Kontak')
                            ->tel()
                            ->required()
                            ->maxLength(20)
                            ->placeholder('08xxxxxxxxxx'),
                    ])
                    ->columns(2)
                    ->collapsible(),
            ]);
    }

    public static function table(Table $table): Table
    {
        // Ambil semua pertanyaan survey untuk dijadikan kolom
        $surveys = SurveyKualitas::orderBy('created_at', 'asc')->get();

        $columns = [
            Tables\Columns\TextColumn::make('nama_responden')
                ->label('Nama Lengkap')
                ->searchable()
                ->sortable()
                ->wrap()
                ->weight('medium'),

            Tables\Columns\TextColumn::make('jenis_kelamin_responden')
                ->label('Jenis Kelamin')
                ->alignCenter()
                ->badge()
                ->color(fn(string $state): string => match ($state) {
                    'Laki-laki' => 'info',
                    'Perempuan' => 'danger',
                    default => 'gray',
                }),

            Tables\Columns\TextColumn::make('pendidikan_responden')
                ->label('Pendidikan')
                ->alignCenter()
                ->sortable()
                ->wrap(),

            Tables\Columns\TextColumn::make('pekerjaan_responden')
                ->label('Pekerjaan')
                ->wrap()
                ->searchable(),

            Tables\Columns\TextColumn::make('usia_responden')
                ->label('Usia')
                ->alignCenter()
                ->sortable()
                ->suffix(' th'),

            Tables\Columns\TextColumn::make('no_telp_responden')
                ->label('Kontak')
                ->searchable()
                ->copyable()
                ->copyMessage('Nomor tersalin!')
                ->icon('heroicon-o-phone'),

            Tables\Columns\TextColumn::make('created_at')
                ->label('Waktu')
                ->dateTime('Y-m-d H:i:s')
                ->sortable()
                ->wrap(),

            Tables\Columns\TextColumn::make('index_kepuasan')
                ->label('Index')
                ->alignCenter()
                ->badge()
                ->color(fn($state): string => match (true) {
                    $state >= 80 => 'success',
                    $state >= 60 => 'warning',
                    default => 'danger',
                })
                ->formatStateUsing(fn($state): string => number_format($state, 2))
                ->sortable(),
        ];

        // Tambahkan kolom untuk setiap pertanyaan (Q1, Q2, Q3, dst)
        foreach ($surveys as $index => $survey) {
            $questionNumber = $index + 1;

            $columns[] = Tables\Columns\TextColumn::make('jawaban_q' . $questionNumber)
                ->label('Q' . $questionNumber)
                ->alignCenter()
                ->badge()
                ->tooltip($survey->pertanyaan)
                ->color(fn($state): string => match ($state) {
                    'Sangat Setuju', '100' => 'success',
                    'Setuju', '80' => 'info',
                    'Cukup', '60' => 'warning',
                    'Tidak Setuju', '40' => 'danger',
                    'Sangat Tidak Setuju', '20' => 'gray',
                    default => 'gray',
                })
                ->formatStateUsing(function ($state) {
                    if (is_numeric($state)) {
                        return $state;
                    }
                    return match ($state) {
                        'Sangat Setuju' => '100',
                        'Setuju' => '80',
                        'Cukup' => '60',
                        'Tidak Setuju' => '40',
                        'Sangat Tidak Setuju' => '20',
                        default => '-',
                    };
                })
                ->getStateUsing(function ($record) use ($survey) {
                    $jawaban = $record->jawabanSurveys()
                        ->where('survey_id', $survey->id)
                        ->first();

                    return $jawaban ? $jawaban->nilai : null;
                });
        }

        return $table
            ->columns($columns)
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('jenis_kelamin_responden')
                    ->label('Jenis Kelamin')
                    ->options([
                        'Laki-laki' => 'Laki-laki',
                        'Perempuan' => 'Perempuan',
                    ]),

                Tables\Filters\SelectFilter::make('pendidikan_responden')
                    ->label('Pendidikan')
                    ->options([
                        'SD/MI sederajat' => 'SD/MI sederajat',
                        'SMP/MTs sederajat' => 'SMP/MTs sederajat',
                        'SMA/SMK/MA sederajat' => 'SMA/SMK/MA sederajat',
                        'D1/D3' => 'D1/D3',
                        'D4/S1' => 'D4/S1',
                        'S2/S3' => 'S2/S3',
                        'lainya' => 'Lainnya',
                    ]),

                Tables\Filters\SelectFilter::make('pekerjaan_responden')
                    ->label('Pekerjaan')
                    ->options([
                        'Pelajar/Mahasiswa' => 'Pelajar/Mahasiswa',
                        'Pegawai Negeri Sipil' => 'Pegawai Negeri Sipil',
                        'TNI/POLRI' => 'TNI/POLRI',
                        'Karyawan Swasta' => 'Karyawan Swasta',
                        'Wirausaha' => 'Wirausaha',
                        'Lainnya' => 'Lainnya',
                    ]),

                Tables\Filters\Filter::make('created_at')
                    ->form([
                        Forms\Components\DatePicker::make('created_from')
                            ->label('Dari Tanggal')
                            ->native(false),
                        Forms\Components\DatePicker::make('created_until')
                            ->label('Sampai Tanggal')
                            ->native(false),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'],
                                fn(Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['created_until'],
                                fn(Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    }),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make()
                        ->icon('heroicon-o-eye')
                        ->color('info'),

                    Tables\Actions\Action::make('lihat_detail')
                        ->label('Lihat Detail Jawaban')
                        ->icon('heroicon-o-document-text')
                        ->color('success')
                        ->modalHeading(fn($record) => 'Detail Jawaban Survey - ' . $record->nama_responden)
                        ->modalWidth('5xl')
                        ->modalContent(function ($record) {
                            $surveys = SurveyKualitas::orderBy('created_at', 'asc')->get();
                            $jawabanData = [];

                            foreach ($surveys as $index => $survey) {
                                $jawaban = $record->jawabanSurveys()
                                    ->where('survey_id', $survey->id)
                                    ->first();

                                $jawabanData['q' . ($index + 1)] = $jawaban ? $jawaban->jawaban : null;
                            }

                            return view('filament.pages.responden-jawaban', [
                                'record' => $record,
                                'surveys' => $surveys,
                                'jawaban' => $jawabanData,
                            ]);
                        })
                        ->modalSubmitAction(false)
                        ->modalCancelActionLabel('Tutup'),

                    Tables\Actions\EditAction::make()
                        ->icon('heroicon-o-pencil')
                        ->color('warning'),

                    Tables\Actions\DeleteAction::make()
                        ->icon('heroicon-o-trash')
                        ->color('danger'),
                ])
                    ->icon('heroicon-m-ellipsis-vertical')
                    ->tooltip('Aksi'),
            ])
            ->actionsPosition(Tables\Enums\ActionsPosition::BeforeColumns)
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListRespondens::route('/'),
            'create' => Pages\CreateResponden::route('/create'),
            'edit' => Pages\EditResponden::route('/{record}/edit'),
        ];
    }
}
