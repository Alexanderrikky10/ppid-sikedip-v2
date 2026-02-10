<div id="infoModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog"
    aria-modal="true">

    {{-- OVERLAY DENGAN BLUR EFFECT --}}
    <div class="fixed inset-0 bg-black/30 backdrop-blur-lg transition-opacity" onclick="closeModal()"></div>

    {{-- Modal Panel --}}
    <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
        <div
            class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-2xl border-t-8 border-green-600 scale-100 opacity-100">

            {{-- Header --}}
            <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4 border-b border-gray-100">
                <div class="flex items-start justify-between">
                    <div class="flex items-center gap-4">
                        <div
                            class="flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-green-50 sm:mx-0 sm:h-12 sm:w-12 border border-green-100">
                            <i class="fa-regular fa-file-lines text-green-600 text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold leading-6 text-gray-900" id="modal-judul">Loading...</h3>
                            <p class="text-sm text-gray-500 mt-1">Detail Informasi Publik</p>
                        </div>
                    </div>
                    <button type="button" onclick="closeModal()"
                        class="text-gray-400 hover:text-red-500 transition-colors rounded-full p-2 hover:bg-red-50">
                        <i class="fa-solid fa-xmark text-2xl"></i>
                    </button>
                </div>
            </div>

            {{-- Content --}}
            <div class="bg-white px-6 py-6">
                <div class="grid grid-cols-1 gap-y-4 text-sm">
                    <div class="grid grid-cols-3 gap-4 border-b border-dashed border-gray-200 pb-2 items-center">
                        <span class="text-gray-500 font-medium">Nomor Dokumen</span>
                        <span class="col-span-2 text-gray-900 font-bold bg-gray-50 px-3 py-1 rounded w-fit"
                            id="modal-nomor">-</span>
                    </div>
                    <div class="grid grid-cols-3 gap-4 border-b border-dashed border-gray-200 pb-2">
                        <span class="text-gray-500 font-medium">Tanggal Publikasi</span>
                        <span class="col-span-2 text-gray-800" id="modal-tanggal">-</span>
                    </div>
                    <div class="grid grid-cols-3 gap-4 border-b border-dashed border-gray-200 pb-2">
                        <span class="text-gray-500 font-medium">Jenis Informasi</span>
                        <span class="col-span-2 text-gray-800" id="modal-jenis">-</span>
                    </div>
                    <div class="grid grid-cols-3 gap-4 border-b border-dashed border-gray-200 pb-2">
                        <span class="text-gray-500 font-medium">Klasifikasi Informasi</span>
                        <span class="col-span-2 text-gray-800" id="modal-klasifikasi">-</span>
                    </div>
                    <div class="grid grid-cols-3 gap-4 border-b border-dashed border-gray-200 pb-2">
                        <span class="text-gray-500 font-medium">Tipe Dokumen</span>
                        <span class="col-span-2 text-gray-800 font-bold uppercase" id="modal-tipe">-</span>
                    </div>
                    <div class="grid grid-cols-3 gap-4 border-b border-dashed border-gray-200 pb-2">
                        <span class="text-gray-500 font-medium">Ukuran Berkas</span>
                        <span class="col-span-2 text-gray-800" id="modal-ukuran">-</span>
                    </div>
                    <div class="grid grid-cols-3 gap-4">
                        <span class="text-gray-500 font-medium">Penerbit</span>
                        <span class="col-span-2 text-gray-800" id="modal-penerbit">-</span>
                    </div>
                </div>
            </div>

            {{-- Footer --}}
            {{-- Footer --}}
            <div class="bg-gray-50 px-6 py-4 sm:flex sm:flex-row-reverse gap-2">
                {{-- Tombol Download (Existing) --}}
                <a id="modal-download-btn" href="#" target="_blank"
                    class="inline-flex w-full justify-center rounded-lg bg-green-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-green-700 sm:w-auto items-center gap-2 transition-all">
                    <i class="fa-solid fa-download"></i> Unduh
                </a>

                {{-- TOMBOL BARU: LIHAT DETAIL --}}
                <a id="modal-detail-btn" href="#"
                    class="inline-flex w-full justify-center rounded-lg bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-blue-700 sm:w-auto items-center gap-2 transition-all">
                    <i class="fa-solid fa-circle-info"></i> Lihat Detail
                </a>

                {{-- Tombol Tutup (Existing) --}}
                <button type="button" onclick="closeModal()"
                    class="mt-3 inline-flex w-full justify-center rounded-lg bg-white px-5 py-2.5 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto transition-all">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>