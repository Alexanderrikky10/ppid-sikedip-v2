<?php

namespace App\Filament\Resources;

use App\Filament\Resources\WelcomePageResource\Pages;
use App\Models\WelcomePage;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Get;
use Filament\Notifications\Notification;

class WelcomePageResource extends Resource
{
    protected static ?string $model = WelcomePage::class;

    // Pengaturan Navigasi
    protected static ?string $navigationIcon = 'heroicon-o-presentation-chart-line';
    protected static ?string $navigationGroup = 'MANAJEMEN CONTENT';
    protected static ?int $navigationSort = 16;
    protected static ?string $navigationLabel = 'Welcome Page';
    protected static ?string $pluralModelLabel = 'Welcome Page';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Grid::make(3)
                    ->schema([
                        // --- KOLOM KIRI (Span 2) ---
                        Forms\Components\Grid::make(1)
                            ->columnSpan(2)
                            ->schema([
                                Forms\Components\Section::make('Informasi Teks')
                                    ->description('Konten teks yang akan tampil di sisi kiri halaman')
                                    ->icon('heroicon-o-document-text')
                                    ->schema([
                                        Forms\Components\TextInput::make('title')
                                            ->label('Judul Utama')
                                            ->placeholder('Contoh: SIKEDIP')
                                            ->required()
                                            ->maxLength(255)
                                            ->columnSpanFull(),

                                        Forms\Components\Textarea::make('sub_title')
                                            ->label('Sub Judul')
                                            ->placeholder('Contoh: SISTEM KELOLA DAFTAR INFORMASI PUBLIK')
                                            ->helperText('Gunakan Enter untuk membuat baris baru')
                                            ->required()
                                            ->rows(3)
                                            ->columnSpanFull(),

                                        Forms\Components\Textarea::make('description')
                                            ->label('Deskripsi')
                                            ->placeholder('Tuliskan deskripsi singkat tentang SIKEDIP')
                                            ->helperText('Gunakan Enter untuk membuat paragraf baru')
                                            ->rows(5)
                                            ->columnSpanFull(),
                                    ])
                                    ->columns(1),

                                Forms\Components\Section::make('Konten Slider Gambar')
                                    ->description('Upload 3-5 gambar untuk slider dengan teks masing-masing')
                                    ->icon('heroicon-o-photo')
                                    ->schema([
                                        Forms\Components\FileUpload::make('media')
                                            ->label('Upload Gambar Slider')
                                            ->disk('minio')
                                            ->directory('welcome-page/images')
                                            ->multiple()
                                            ->minFiles(3)
                                            ->maxFiles(5)
                                            ->image()
                                            ->imageEditor()
                                            ->imageEditorAspectRatios([
                                                '16:9',
                                                '4:3',
                                                null,
                                            ])
                                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                                            ->maxSize(5120) // 5MB
                                            ->required()
                                            ->visibility('private')
                                            ->helperText('📌 Upload 3-5 gambar. Rekomendasi: 1920x1080px, format JPG/PNG/WebP, max 5MB per file')
                                            ->panelLayout('grid')
                                            ->imagePreviewHeight('200')
                                            ->reorderable()
                                            ->columnSpanFull()
                                            ->live()
                                            ->afterStateUpdated(function (Get $get, Forms\Set $set, $state) {
                                                // Auto-adjust jumlah media_slides sesuai jumlah gambar
                                                $imageCount = is_array($state) ? count($state) : 0;
                                                $currentSlides = $get('media_slides') ?? [];
                                                $currentCount = count($currentSlides);

                                                if ($imageCount > 0 && $imageCount !== $currentCount) {
                                                    Notification::make()
                                                        ->title('Perhatian!')
                                                        ->body("Anda telah upload {$imageCount} gambar. Silakan isi {$imageCount} teks slide di bawah.")
                                                        ->warning()
                                                        ->send();
                                                }
                                            }),

                                        Forms\Components\Repeater::make('media_slides')
                                            ->label('Teks untuk Setiap Slide')
                                            ->helperText('⚠️ PENTING: Jumlah teks harus SAMA dengan jumlah gambar yang diupload (3-5). Urutan teks akan sesuai dengan urutan gambar.')
                                            ->schema([
                                                Forms\Components\Grid::make(2)
                                                    ->schema([
                                                        Forms\Components\TextInput::make('title')
                                                            ->label('Judul Slide')
                                                            ->placeholder('Contoh: Transparansi Informasi Publik')
                                                            ->required()
                                                            ->maxLength(100),

                                                        Forms\Components\TextInput::make('text')
                                                            ->label('Teks Slide')
                                                            ->placeholder('Contoh: Akses mudah ke informasi publik')
                                                            ->required()
                                                            ->maxLength(200),
                                                    ]),
                                            ])
                                            ->columns(1)
                                            ->collapsible()
                                            ->collapsed(false)
                                            ->itemLabel(
                                                fn(array $state): ?string =>
                                                ($state['title'] ?? null)
                                                ? '📝 ' . $state['title']
                                                : null
                                            )
                                            ->defaultItems(3)
                                            ->minItems(3)
                                            ->maxItems(5)
                                            ->reorderable()
                                            ->addActionLabel('+ Tambah Teks Slide')
                                            ->deleteAction( // <-- PERBAIKAN DI SINI
                                                fn(Forms\Components\Actions\Action $action) => $action->label('Hapus')
                                            )
                                            ->columnSpanFull()
                                            ->live(),
                                    ])
                                    ->columns(1),
                            ]),

                        // --- KOLOM KANAN (Span 1) ---
                        Forms\Components\Grid::make(1)
                            ->columnSpan(1)
                            ->schema([
                                Forms\Components\Section::make('Pengaturan')
                                    ->description('Status publikasi konten')
                                    ->icon('heroicon-o-cog-6-tooth')
                                    ->schema([
                                        Forms\Components\Toggle::make('is_active')
                                            ->label('Aktifkan Konten')
                                            ->helperText('Hanya satu konten yang boleh aktif. Jika diaktifkan, konten lain akan otomatis dinonaktifkan.')
                                            ->required()
                                            ->default(true)
                                            ->inline(false),

                                        Forms\Components\Placeholder::make('info')
                                            ->label('')
                                            ->content('ℹ️ Tips: Pastikan jumlah gambar dan teks slide sama agar tampilan sempurna.')
                                            ->columnSpanFull(),
                                    ]),

                                Forms\Components\Section::make('Panduan')
                                    ->description('Cara menggunakan form ini')
                                    ->icon('heroicon-o-question-mark-circle')
                                    ->collapsible()
                                    ->collapsed()
                                    ->schema([
                                        Forms\Components\Placeholder::make('guide')
                                            ->label('')
                                            ->content(new \Illuminate\Support\HtmlString('
                                                <div class="text-sm space-y-2">
                                                    <p class="font-semibold">Langkah-langkah:</p>
                                                    <ol class="list-decimal list-inside space-y-1">
                                                        <li>Isi Judul, Sub Judul, dan Deskripsi</li>
                                                        <li>Upload 3-5 gambar slider</li>
                                                        <li>Isi teks untuk setiap gambar (jumlah harus sama)</li>
                                                        <li>Aktifkan toggle jika ingin dipublikasi</li>
                                                        <li>Klik Simpan</li>
                                                    </ol>
                                                    <p class="mt-3 text-gray-600">
                                                        <strong>Catatan:</strong> Urutan teks akan mengikuti urutan gambar. 
                                                        Gambar pertama akan dipasangkan dengan teks pertama, dan seterusnya.
                                                    </p>
                                                </div>
                                            ')),
                                    ]),
                            ]),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('media')
                    ->label('Preview Gambar')
                    ->disk('minio')
                    ->stacked()
                    ->limit(3)
                    ->limitedRemainingText(isSeparate: true)
                    ->visibility('private')
                    ->circular(),

                Tables\Columns\TextColumn::make('title')
                    ->label('Judul Utama')
                    ->searchable()
                    ->weight('bold')
                    ->wrap()
                    ->description(fn($record) => \Illuminate\Support\Str::limit($record->sub_title, 50)),

                // Kolom 'media' (Jumlah Gambar) yang error sudah dihapus sesuai permintaan Anda.

                Tables\Columns\TextColumn::make('media_slides')
                    ->label('Jumlah Teks')
                    ->formatStateUsing(function ($state) {
                        // Cek apakah $state adalah array, jika ya, hitung. Jika tidak, anggap 0.
                        $count = is_array($state) ? count($state) : 0;
                        return $count . ' teks';
                    })
                    ->badge()
                    ->color('info')
                    ->icon('heroicon-o-document-text'),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('Status')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Diupdate')
                    ->dateTime('d M Y, H:i')
                    ->sortable()
                    ->since()
                    ->description(fn($record) => $record->updated_at->diffForHumans()),
            ])
            ->defaultSort('updated_at', 'desc')
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Status')
                    ->placeholder('Semua Status')
                    ->trueLabel('Hanya Aktif')
                    ->falseLabel('Hanya Tidak Aktif')
                    ->native(false),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->iconButton(),
                Tables\Actions\EditAction::make()
                    ->iconButton(),
                Tables\Actions\DeleteAction::make()
                    ->iconButton()
                    ->requiresConfirmation()
                    ->modalHeading('Hapus Content Page Awal')
                    ->modalDescription('Apakah Anda yakin ingin menghapus content ini? Aksi ini tidak dapat dibatalkan.')
                    ->modalSubmitActionLabel('Ya, Hapus'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->requiresConfirmation(),
                ]),
            ])
            ->emptyStateHeading('Belum ada Content Page Awal')
            ->emptyStateDescription('Klik tombol di bawah untuk membuat content pertama')
            ->emptyStateIcon('heroicon-o-presentation-chart-line');
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListWelcomePages::route('/'),
            'create' => Pages\CreateWelcomePage::route('/create'),
            'edit' => Pages\EditWelcomePage::route('/{record}/edit'),
        ];
    }


    public static function getNavigationBadgeColor(): ?string
    {
        $activeCount = static::getModel()::where('is_active', true)->count();
        return $activeCount > 0 ? 'success' : 'gray';
    }
}