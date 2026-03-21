<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BerandaContentResource\Pages;
use App\Models\BerandaContent;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Section;

class BerandaContentResource extends Resource
{
    protected static ?string $model = BerandaContent::class;

    protected static ?int $navigationSort = 14;

    protected static ?string $navigationGroup = 'MANAJEMEN CONTENT';
    protected static ?string $navigationIcon = 'heroicon-o-presentation-chart-line';

    // Memberikan label yang lebih rapi di navigasi
    protected static ?string $navigationLabel = 'Konten Beranda';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Informasi Utama')
                    ->description('Kelola teks yang akan tampil di bagian Hero Slider.')
                    ->aside() // Membuat teks deskripsi berada di samping (layout modern)
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->label('Judul Utama')
                            ->placeholder('Contoh: Temukan Informasi Publik...')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\Textarea::make('description')
                            ->label('Sub-deskripsi')
                            ->rows(3)
                            ->required(),

                        Forms\Components\Toggle::make('is_active')
                            ->label('Status Aktif')
                            ->helperText('Matikan jika konten ini tidak ingin ditampilkan di beranda.')
                            ->default(true)
                            ->onColor('success')
                            ->offColor('danger'),
                    ]),

                Section::make('Media Slider')
                    ->description('Unggah gambar yang akan dijadikan background slider.')
                    ->aside()
                    ->schema([
                        Forms\Components\FileUpload::make('media')
                            ->label('Gambar Slider')
                            ->multiple() // Karena model Anda meng-cast media ke array
                            ->image()
                            ->imageEditor() // Admin bisa crop gambar langsung
                            ->directory('beranda-slider')
                            ->reorderable()
                            ->appendFiles()
                            ->disk('minio') // Pastikan ini sesuai dengan konfigurasi disk Anda
                            ->visibility('private')
                            ->required()
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // Menampilkan preview gambar pertama dari array media
                Tables\Columns\ImageColumn::make('media')
                    ->label('Preview')
                    ->circular()
                    ->disk('minio') // Pastikan ini sesuai dengan konfigurasi disk Anda
                    ->visibility('private')
                    ->stacked() // Jika ada banyak gambar akan tampil bertumpuk cantik
                    ->limit(3),

                Tables\Columns\TextColumn::make('title')
                    ->label('Judul')
                    ->searchable()
                    ->sortable()
                    ->description(fn(BerandaContent $record): string => \Illuminate\Support\Str::limit($record->description, 50)),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('Status')
                    ->boolean()
                    ->sortable(),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Terakhir Diperbarui')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Filter Status Aktif'),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->button() // Mengubah link edit menjadi tombol agar lebih terlihat
                    ->color('primary'),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateHeading('Belum ada konten beranda')
            ->emptyStateDescription('Mulai buat konten pertama Anda untuk mengisi slider di halaman depan.');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBerandaContents::route('/'),
            'create' => Pages\CreateBerandaContent::route('/create'),
            'edit' => Pages\EditBerandaContent::route('/{record}/edit'),
        ];
    }
}