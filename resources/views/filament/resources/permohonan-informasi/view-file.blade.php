<div class="space-y-6">

    {{-- Cek Dokumen Scan Identitas --}}
    @if($record->scan_identitas)
        @php
            $extension = pathinfo($record->scan_identitas, PATHINFO_EXTENSION);
            $url = \Illuminate\Support\Facades\Storage::disk('minio')->temporaryUrl(
                $record->scan_identitas,
                now()->addMinutes(10)
            );
        @endphp

        <div class="border rounded-xl p-4 bg-gray-50 dark:bg-gray-800 shadow-sm">
            <div class="flex justify-between items-center mb-4">
                <h3 class="font-bold text-lg text-gray-700 dark:text-gray-200">Scan Identitas</h3>
                {{-- Tambahan: Tombol buka di tab baru untuk kenyamanan --}}
                <a href="{{ $url }}" target="_blank"
                    class="text-sm text-primary-600 hover:text-primary-500 hover:underline flex items-center gap-1">
                    <x-heroicon-m-arrow-top-right-on-square class="w-4 h-4" />
                    Buka Fullscreen
                </a>
            </div>

            @if(in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'webp']))
                <img src="{{ $url }}" class="w-full rounded-lg shadow-md border" alt="Scan Identitas">
            @elseif(strtolower($extension) === 'pdf')
                {{-- UBAH: h-[500px] menjadi h-[800px] agar lebih panjang ke bawah --}}
                <iframe src="{{ $url }}" class="w-full h-[800px] rounded-lg border shadow-md bg-white"></iframe>
            @else
                <div class="p-6 text-center border-2 border-dashed rounded-lg">
                    <a href="{{ $url }}" target="_blank" class="text-primary-600 underline font-semibold text-lg">
                        Download File ({{ $extension }})
                    </a>
                </div>
            @endif
        </div>
    @endif

    {{-- Cek Dokumen Tambahan --}}
    @if($record->dokumen_tambahan_path)
        @php
            $extTambahan = pathinfo($record->dokumen_tambahan_path, PATHINFO_EXTENSION);
            $urlTambahan = \Illuminate\Support\Facades\Storage::disk('minio')->temporaryUrl(
                $record->dokumen_tambahan_path,
                now()->addMinutes(10)
            );
        @endphp

        <div class="border rounded-xl p-4 bg-gray-50 dark:bg-gray-800 mt-4 shadow-sm">
            <div class="flex justify-between items-center mb-4">
                <h3 class="font-bold text-lg text-gray-700 dark:text-gray-200">Dokumen Pendukung</h3>
                <a href="{{ $urlTambahan }}" target="_blank"
                    class="text-sm text-primary-600 hover:text-primary-500 hover:underline flex items-center gap-1">
                    <x-heroicon-m-arrow-top-right-on-square class="w-4 h-4" />
                    Buka Fullscreen
                </a>
            </div>

            @if(strtolower($extTambahan) === 'pdf')
                {{-- UBAH: h-[500px] menjadi h-[800px] --}}
                <iframe src="{{ $urlTambahan }}" class="w-full h-[800px] rounded-lg border shadow-md bg-white"></iframe>
            @elseif(in_array(strtolower($extTambahan), ['jpg', 'jpeg', 'png']))
                <img src="{{ $urlTambahan }}" class="w-full rounded-lg shadow-md border" alt="Dokumen Tambahan">
            @else
                <div class="flex items-center gap-3 p-4 bg-white dark:bg-gray-700 rounded-lg border">
                    <x-heroicon-o-document class="w-8 h-8 text-gray-500" />
                    <div class="flex flex-col">
                        <span class="text-sm text-gray-500">File tidak dapat dipreview</span>
                        <a href="{{ $urlTambahan }}" target="_blank" class="text-primary-600 hover:underline font-bold">
                            Download / Buka File Pendukung
                        </a>
                    </div>
                </div>
            @endif
        </div>
    @endif

    @if(!$record->scan_identitas && !$record->dokumen_tambahan_path)
        <div class="text-center text-gray-500 py-12 bg-gray-50 rounded-lg border border-dashed">
            <x-heroicon-o-document-magnifying-glass class="w-12 h-12 mx-auto text-gray-400 mb-2" />
            <p>Tidak ada dokumen yang diunggah.</p>
        </div>
    @endif
</div>