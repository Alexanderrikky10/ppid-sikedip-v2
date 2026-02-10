  {{-- 2. FILTER SECTION (COLLAPSIBLE) --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-8" x-data="{ openFilter: false }">
                {{-- Header Filter (Tombol Toggle) --}}
                <div class="flex items-center justify-between p-6 cursor-pointer hover:bg-gray-50 transition-colors rounded-xl"
                    @click="openFilter = !openFilter">
                    <div class="flex items-center">
                        <div
                            class="w-10 h-10 rounded-lg bg-green-100 text-green-600 flex items-center justify-center mr-4 transition-colors group-hover:bg-green-200">
                            <i class="fa-solid fa-filter text-lg"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-gray-800 text-lg">Filter Pencarian Data</h3>
                            <p class="text-xs text-gray-500 mt-1">Klik untuk menampilkan/menyembunyikan filter</p>
                        </div>
                    </div>
                    {{-- Ikon Panah --}}
                    <div class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center text-gray-500 transition-transform duration-300"
                        :class="openFilter ? 'rotate-180 bg-green-100 text-green-600' : ''">
                        <i class="fa-solid fa-chevron-down"></i>
                    </div>
                </div>

                {{-- Body Filter (Hidden by Default) --}}
                <div x-show="openFilter" x-collapse x-cloak class="border-t border-gray-100">

                    <div class="p-6 bg-gray-50/30">
                        <form action="{{ url()->current() }}" method="GET">
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-12 gap-5">
                                {{-- Filter Tahun --}}
                                <div class="lg:col-span-2">
                                    <label class="block text-xs font-bold text-gray-600 mb-2 uppercase">Tahun</label>
                                
                                    @if(isset($isLocked) && $isLocked)
                                        {{-- TAMPILAN READ ONLY (Untuk Pemprov Controller) --}}
                                        <div class="relative">
                                            <input type="text" value="{{ $lockedYear }}"
                                                class="w-full px-4 py-2.5 rounded-lg border-gray-300 bg-gray-100 text-gray-500 text-sm font-bold cursor-not-allowed shadow-sm focus:ring-0 focus:border-gray-300"
                                                readonly>
                                            {{-- Input hidden agar nilai tetap terkirim saat submit filter lain --}}
                                            <input type="hidden" name="tahun" value="{{ $lockedYear }}">
                                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                                <i class="fa-solid fa-lock text-gray-400 text-xs"></i>
                                            </div>
                                        </div>
                                    @else
                                        {{-- TAMPILAN DROPDOWN BIASA (Untuk Controller Umum) --}}
                                        <select name="tahun"
                                            class="w-full px-4 py-2.5 rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500 shadow-sm bg-white text-sm text-gray-700">
                                            <option value="">Semua</option>
                                            @foreach($tahunList as $thn)
                                                <option value="{{ $thn }}" {{ request('tahun') == $thn ? 'selected' : '' }}>{{ $thn }}</option>
                                            @endforeach
                                        </select>
                                    @endif
                                </div>

                                {{-- Filter OPD Hierarki --}}
                    {{-- Filter OPD Hierarki --}}
                    <div class="lg:col-span-4">
                        <label class="block text-xs font-bold text-gray-600 mb-2 uppercase">Perangkat Daerah Penerbit</label>
                    
                        {{-- PRIORITAS 1: Locked Specific OPD (Hasil dari Slug / Controller 'pemprovList') --}}
                        @if(isset($lockedOpd))
                            <div class="relative">
                                {{-- Tampilan Visual (Nama Dinas) --}}
                                <input type="text" value="{{ $lockedOpd->nama_perangkat_daerah }}"
                                    class="w-full px-4 py-2.5 rounded-lg border-gray-300 bg-gray-100 text-gray-700 text-sm font-bold cursor-not-allowed shadow-sm focus:ring-0 focus:border-gray-300"
                                    readonly>

                                {{-- Input Hidden (PENTING: Agar ID tetap terkirim saat user filter tahun/keyword) --}}
                                <input type="hidden" name="opd" value="{{ $lockedOpd->id }}">

                                {{-- Icon Gembok --}}
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <i class="fa-solid fa-lock text-gray-500 text-xs"></i>
                                </div>
                            </div>

                            {{-- PRIORITAS 2: Locked Kategori Global (Misal: Controller Pemprov General) --}}
                        @elseif(isset($isLocked) && $isLocked)
                            <div class="relative">
                                <input type="text" value="{{ $namaKategoriUtama ?? 'Pemerintah Provinsi' }}"
                                    class="w-full px-4 py-2.5 rounded-lg border-gray-300 bg-gray-100 text-gray-500 text-sm font-bold cursor-not-allowed shadow-sm focus:ring-0 focus:border-gray-300"
                                    readonly>

                                {{-- Disini kita tidak kirim 'opd' name karena controller sudah filter by kategori --}}
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <i class="fa-solid fa-lock text-gray-400 text-xs"></i>
                                </div>
                            </div>

                            {{-- PRIORITAS 3: Mode Normal (Bisa Pilih OPD - Dropdown) --}}
                        @else
                            <select name="opd"
                                class="w-full px-4 py-2.5 rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500 shadow-sm bg-white text-sm text-gray-700">
                                <option value="">Semua Perangkat Daerah</option>

                                @foreach($opdList as $kategori)
                                    <option disabled class="font-extrabold text-black bg-gray-100">
                                        ⭐⭐ {{ strtoupper($kategori->nama_kategori) }}
                                    </option>

                                    @foreach($kategori->perangkatDaerahs as $parentOpd)
                                        <option value="{{ $parentOpd->id }}" {{ request('opd') == $parentOpd->id ? 'selected' : '' }}>
                                            &nbsp;&nbsp;★ {{ $parentOpd->nama_perangkat_daerah }}
                                        </option>

                                        @if($parentOpd->children->isNotEmpty())
                                            @foreach($parentOpd->children as $childOpd)
                                                <option value="{{ $childOpd->id }}" {{ request('opd') == $childOpd->id ? 'selected' : '' }}>
                                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;├─ {{ $childOpd->nama_perangkat_daerah }}
                                                </option>
                                            @endforeach
                                        @endif
                                    @endforeach
                                @endforeach
                            </select>
                        @endif
                    </div>

                                {{-- Filter Klasifikasi --}}
                                <div class="lg:col-span-3">
                                    <label class="block text-xs font-bold text-gray-600 mb-2 uppercase">Klasifikasi
                                        Informasi</label>
                                    <select name="klasifikasi_informasi_id"
                                        class="w-full px-4 py-2.5 rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500 shadow-sm bg-white text-sm text-gray-700">
                                        <option value="">Semua Klasifikasi</option>
                                        @foreach($klasifikasilist as $klasifikasi)
                                            <option value="{{ $klasifikasi->id }}" {{ request('klasifikasi_informasi_id') == $klasifikasi->id ? 'selected' : '' }}>
                                                {{ $klasifikasi->nama_klasifikasi }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- Filter Jenis --}}
                                <div class="lg:col-span-3">
                                    <label class="block text-xs font-bold text-gray-600 mb-2 uppercase">Jenis Informasi</label>
                                    <select name="kategori_jenis_informasi_id"
                                        class="w-full px-4 py-2.5 rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500 shadow-sm bg-white text-sm text-gray-700">
                                        <option value="">Semua Jenis</option>
                                        @foreach($kategoriList as $kat)
                                            <option value="{{ $kat->id }}" {{ request('kategori_jenis_informasi_id') == $kat->id ? 'selected' : '' }}>
                                                {{ $kat->nama_jenis_informasi ?? $kat->nama_kategori }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- Filter Keyword --}}
                                <div class="lg:col-span-12">
                                    <label class="block text-xs font-bold text-gray-600 mb-2 uppercase">Nama Informasi</label>
                                    <div class="relative">
                                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400"><i
                                                class="fa-solid fa-magnifying-glass"></i></span>
                                        <input type="text" name="keyword" value="{{ request('keyword') }}"
                                            placeholder="Ketik kata kunci judul informasi..."
                                            class="w-full pl-10 pr-4 py-2.5 rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500 shadow-sm transition-colors text-sm">
                                    </div>
                                </div>
                            </div>

                            <div class="flex justify-end gap-3 mt-6 pt-4 border-t border-gray-200">
                                <a href="{{ url()->current() }}"
                                    class="px-5 py-2.5 rounded-lg text-gray-600 bg-white border border-gray-300 hover:bg-gray-50 font-bold text-sm transition-colors flex items-center shadow-sm">
                                    <i class="fa-solid fa-rotate-left mr-2"></i> Reset
                                </a>
                                <button type="submit"
                                    class="px-6 py-2.5 rounded-lg text-white bg-green-600 hover:bg-green-700 font-bold text-sm shadow-md transition-colors flex items-center">
                                    <i class="fa-solid fa-filter mr-2"></i> Tampilkan Data
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            {{-- 3. TABLE SECTION --}}
            <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden mb-10">
                <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                    <h3 class="font-bold text-gray-800 text-lg">Hasil Pencarian</h3>
                    <span class="text-xs font-semibold bg-green-100 text-green-700 px-3 py-1 rounded-full border border-green-200">Total: {{ $informasis->total() }} Dokumen</span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-100 text-gray-600 uppercase text-xs tracking-wider font-bold">
                                <th class="px-6 py-4 w-16 text-center border-b border-gray-200">No</th>
                                <th class="px-6 py-4 min-w-[280px] border-b border-gray-200">Nama Informasi & Pejabat PJ</th>
                                <th class="px-6 py-4 min-w-[200px] border-b border-gray-200">Penanggung Jawab Pembuatan</th>
                                <th class="px-6 py-4 min-w-[180px] border-b border-gray-200">Waktu & Tempat</th>
                                <th class="px-6 py-4 min-w-[150px] border-b border-gray-200">Format</th>
                                <th class="px-6 py-4 min-w-[150px] border-b border-gray-200">Retensi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse ($informasis as $index => $info)
                                <tr onclick="showDetail({{ $info->id }})" class="hover:bg-green-50/40 transition-colors duration-200 group cursor-pointer">
                                    <td class="px-6 py-4 text-center text-sm font-semibold text-gray-500">{{ $informasis->firstItem() + $index }}</td>
                                    <td class="px-6 py-4">
                                        <div class="flex flex-col">
                                            <span class="text-sm font-bold text-gray-800 group-hover:text-green-700 transition-colors mb-1.5 leading-snug">{{ $info->judul_informasi }}</span>
                                            <div class="flex items-center text-xs text-gray-500 mb-2">
                                                <i class="fa-solid fa-user-tie mr-1.5 text-gray-400"></i> {{ $info->pejabat_pj ?? '-' }}
                                            </div>
                                            @if($info->klasifikasiInformasi)
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-blue-50 text-blue-600 border border-blue-100 uppercase tracking-wide w-fit">
                                                    <i class="fa-solid fa-tag mr-1 text-[9px]"></i> {{ $info->klasifikasiInformasi->nama_klasifikasi }}
                                                </span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-600">
                                        <div class="flex items-start">
                                            <i class="fa-solid fa-briefcase mt-1 mr-2 text-green-500/50"></i>
                                            <span class="font-medium">{{ $info->pj_penerbit_informasi }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-600">
                                        <div class="flex flex-col gap-1.5">
                                            <span class="flex items-center font-medium text-gray-700"><i class="fa-regular fa-calendar-check mr-2 text-orange-400"></i> {{ $info->tahun }}</span>
                                            <span class="flex items-center text-xs text-gray-500"><i class="fa-solid fa-map-pin mr-2.5 text-gray-400"></i> {{ $info->waktu_tempat }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex flex-wrap gap-2">
                                            @foreach ($info->format_array as $fmt)
                                                @php
        $fmt = trim($fmt);
        $upperFmt = strtoupper($fmt);
        $style = match ($upperFmt) { 'HARD COPY' => 'bg-orange-50 text-orange-700 border-orange-200', 'SOFT COPY' => 'bg-green-50 text-green-700 border-green-200', 'HARD COPY & SOFT COPY' => 'bg-blue-50 text-blue-700 border-blue-200', default => 'bg-gray-50 text-gray-600 border-gray-200'};
        $icon = match ($upperFmt) { 'HARD COPY' => 'fa-print', 'SOFT COPY' => 'fa-file-arrow-down', 'HARD COPY & SOFT COPY' => 'fa-folder-open', default => 'fa-file'};
                                                @endphp
                                                <span class="inline-flex items-center px-2 py-1 rounded-md text-[10px] font-bold border {{ $style }}"><i class="fa-solid {{ $icon }} mr-1"></i> {{ $fmt }}</span>
                                            @endforeach
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-600">
                                        <div class="flex items-center"><i class="fa-solid fa-clock-rotate-left mr-2 text-gray-400"></i> {{ $info->waktu_penyimpanan }}</div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center text-gray-500 bg-gray-50 border-b border-gray-200">
                                        <div class="flex flex-col items-center justify-center">
                                            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4 text-gray-300"><i class="fa-regular fa-folder-open text-3xl"></i></div>
                                            <p class="font-medium text-gray-600">Tidak ada data informasi publik yang ditemukan.</p>
                                            <p class="text-xs text-gray-400 mt-1">Coba sesuaikan filter pencarian Anda.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="p-4 border-t border-gray-100 bg-gray-50">{{ $informasis->links() }}</div>
            </div>