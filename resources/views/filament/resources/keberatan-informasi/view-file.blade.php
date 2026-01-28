{{-- resources/views/filament/resources/keberatan-informasi/view-keberatan.blade.php --}}
<div class="space-y-6">

    {{-- BAGIAN 1: DETAIL ALASAN & TUJUAN (Penting untuk verifikasi) --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div class="p-4 bg-gray-50 dark:bg-gray-800 rounded-xl border shadow-sm">
            <h3 class="font-bold text-lg text-gray-800 dark:text-gray-200 mb-2 border-b pb-2">
                Alasan Pengajuan Keberatan
            </h3>
            <ul class="list-disc list-inside space-y-1 text-gray-700 dark:text-gray-300">
                @if(is_array($record->alasan_keberatan))
                    @foreach($record->alasan_keberatan as $alasan)
                        <li>{{ $alasan }}</li>
                    @endforeach
                @else
                    <li class="italic text-gray-500">Tidak ada alasan spesifik (Data format lama)</li>
                @endif
            </ul>
        </div>

        <div class="p-4 bg-gray-50 dark:bg-gray-800 rounded-xl border shadow-sm">
            <h3 class="font-bold text-lg text-gray-800 dark:text-gray-200 mb-2 border-b pb-2">
                Tujuan Penggunaan Informasi
            </h3>
            <p class="text-gray-700 dark:text-gray-300 italic">
                "{{ $record->tujuan_penggunaan_informasi }}"
            </p>
        </div>
    </div>

    {{-- BAGIAN 2: FILE SURAT KUASA --}}
    <div class="border-t pt-4">
        <h3 class="font-bold text-xl text-gray-800 dark:text-gray-200 mb-4 flex items-center gap-2">
            <x-heroicon-o-document-text class="w-6 h-6" />
            Berkas Surat Kuasa
        </h3>

        @if($record->surat_kuasa)
            @php
                $extension = pathinfo($record->surat_kuasa, PATHINFO_EXTENSION);
                // Generate Signed URL MinIO (Valid 10 menit)
                $url = \Illuminate\Support\Facades\Storage::disk('minio')->temporaryUrl(
                    $record->surat_kuasa,
                    now()->addMinutes(10)
                );
            @endphp

            <div class="border rounded-xl p-4 bg-gray-50 dark:bg-gray-800 shadow-sm">
                {{-- Header File --}}
                <div class="flex justify-between items-center mb-4">
                    <div class="text-sm">
                        <span class="font-semibold">Kuasa Diberikan Kepada:</span>
                        <span class="text-gray-700 dark:text-gray-300">{{ $record->nama_kuasa }}</span>
                    </div>
                    <a href="{{ $url }}" target="_blank"
                        class="text-sm text-primary-600 hover:text-primary-500 hover:underline flex items-center gap-1">
                        <x-heroicon-m-arrow-top-right-on-square class="w-4 h-4" />
                        Buka Fullscreen
                    </a>
                </div>

                {{-- Preview File --}}
                @if(in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'webp']))
                    <img src="{{ $url }}" class="w-full rounded-lg shadow-md border" alt="Surat Kuasa">
                @elseif(strtolower($extension) === 'pdf')
                    {{-- Tinggi 800px agar nyaman dibaca --}}
                    <iframe src="{{ $url }}" class="w-full h-[800px] rounded-lg border shadow-md bg-white"></iframe>
                @else
                    <div class="p-12 text-center border-2 border-dashed rounded-lg bg-white dark:bg-gray-700">
                        <x-heroicon-o-document class="w-12 h-12 mx-auto text-gray-400 mb-2" />
                        <p class="mb-2">File format <b>.{{ $extension }}</b> tidak mendukung preview.</p>
                        <a href="{{ $url }}" target="_blank"
                            class="text-primary-600 underline font-semibold text-lg hover:text-primary-500">
                            Download Surat Kuasa
                        </a>
                    </div>
                @endif
            </div>

        @else
            {{-- Jika Tidak Ada Surat Kuasa --}}
            <div class="text-center text-gray-500 py-12 bg-gray-50 dark:bg-gray-800 rounded-xl border border-dashed">
                <x-heroicon-o-user-minus class="w-12 h-12 mx-auto text-gray-400 mb-2" />
                <p class="font-medium">Tidak Menggunakan Kuasa</p>
                <p class="text-sm">Pemohon mengajukan keberatan atas nama sendiri, sehingga tidak ada Surat Kuasa yang
                    diunggah.</p>
            </div>
        @endif
    </div>
</div>