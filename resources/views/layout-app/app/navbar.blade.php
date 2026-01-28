<nav x-data="{ scrolled: false, mobileMenuOpen: false }" @scroll.window="scrolled = (window.pageYOffset > 20)"
    :class="{ 'bg-black/50 backdrop-blur-md shadow-md': scrolled, 'bg-transparent': !scrolled }"
    class="fixed top-0 left-0 right-0 z-50 w-full transition-all duration-300 border-b border-white/10">

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-20">

            {{-- LOGO SECTION --}}
            <div class="flex-shrink-0 flex items-center gap-3">
                <a href="#" class="flex items-center gap-3">
                    {{-- Pastikan file gambar ini ada di public/assets/images/ --}}
                    <img class="h-10 w-auto" src="{{ asset('images/logo.png') }}" alt="Logo">
                    <div class="flex flex-col">
                        <span class="text-white font-bold text-xl leading-none tracking-wide">SIKEDIP</span>
                        <span class="text-gray-300 text-xs font-light tracking-wider">Sistem Kelola Informasi
                            Publik</span>
                    </div>
                </a>
            </div>

            {{-- DESKTOP MENU --}}
            <div class="hidden md:block">
                <div class="ml-10 flex items-baseline space-x-6">

                    {{-- 1. MENU BERANDA --}}
                    <div class="relative group" x-data="{ open: false }" @mouseenter="open = true"
                        @mouseleave="open = false">
                        <button
                            class="text-white hover:text-green-400 px-3 py-2 rounded-md text-sm font-medium transition-colors flex items-center gap-1 group-hover:text-green-400 focus:outline-none">
                            Beranda
                            <i class="fas fa-chevron-down text-[10px] opacity-70 transition-transform duration-300"
                                :class="{'rotate-180': open}"></i>
                        </button>
                        {{-- Dropdown --}}
                        <div x-show="open" x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 translate-y-2"
                            x-transition:enter-end="opacity-100 translate-y-0"
                            x-transition:leave="transition ease-in duration-150"
                            x-transition:leave-start="opacity-100 translate-y-0"
                            x-transition:leave-end="opacity-0 translate-y-2"
                            class="absolute left-0 mt-0 w-56 bg-white rounded-xl shadow-xl py-2 z-50 ring-1 ring-black ring-opacity-5 origin-top-left"
                            style="display: none;">

                            <a href="{{ route('beranda.index') }}"
                                class="block px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 hover:text-blue-600 transition-colors">Beranda</a>
                            <a href="#" target="_blank"
                                class="block px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 hover:text-blue-600 transition-colors">Satu
                                Data Kalbar</a>
                            <a href="#" target="_blank"
                                class="block px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 hover:text-blue-600 transition-colors">JDIH
                                Kalbar</a>
                            <div class="border-t border-gray-100 my-1"></div>
                            @guest
                                <a href="#"
                                    class="block px-4 py-2.5 text-sm font-medium text-gray-800 hover:bg-gray-50 hover:text-blue-600 transition-colors">Sign
                                    in</a>
                            @endguest
                        </div>
                    </div>

                    {{-- 2. MENU LAYANAN INFORMASI --}}
                    <div class="relative group" x-data="{ open: false }" @mouseenter="open = true"
                        @mouseleave="open = false">
                        <button
                            class="text-gray-300 hover:text-white px-3 py-2 rounded-md text-sm font-medium transition-colors flex items-center gap-1 focus:outline-none">
                            Layanan Informasi
                            <i class="fas fa-chevron-down text-[10px] opacity-70 transition-transform duration-300"
                                :class="{'rotate-180': open}"></i>
                        </button>
                        {{-- Dropdown --}}
                        <div x-show="open" x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 translate-y-2"
                            x-transition:enter-end="opacity-100 translate-y-0"
                            x-transition:leave="transition ease-in duration-150"
                            x-transition:leave-start="opacity-100 translate-y-0"
                            x-transition:leave-end="opacity-0 translate-y-2"
                            class="absolute left-0 mt-0 w-64 bg-white rounded-xl shadow-xl py-2 z-50 ring-1 ring-black ring-opacity-5 origin-top-left"
                            style="display: none;">

                            <a href="{{ route('permohonan-informasi') }}"
                                class="block px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 hover:text-blue-600 transition-colors">Permohonan
                                Informasi</a>
                            <a href="{{ route('keberatan-informasi') }}"
                                class="block px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 hover:text-blue-600 transition-colors">Keberatan
                                Informasi</a>
                            <a href="{{ route('tata-cara-layanan-informasi') }}"
                                class="block px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 hover:text-blue-600 transition-colors">Tata
                                Cara Memperoleh Informasi</a>
                            <a href="#"
                                class="block px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 hover:text-blue-600 transition-colors">Survey
                                Akses & Kualitas</a>
                            <a href="{{ route('cetak-informasi') }}"
                                class="block px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 hover:text-blue-600 transition-colors">Cetak
                                Informasi</a>
                        </div>
                    </div>

                    {{-- 3. MENU INSTANSI PEMERINTAH --}}
                    <div class="relative group" x-data="{ open: false }" @mouseenter="open = true"
                        @mouseleave="open = false">
                        <button
                            class="text-gray-300 hover:text-white px-3 py-2 rounded-md text-sm font-medium transition-colors flex items-center gap-1 focus:outline-none">
                            Instansi Pemerintah
                            <i class="fas fa-chevron-down text-[10px] opacity-70 transition-transform duration-300"
                                :class="{'rotate-180': open}"></i>
                        </button>
                        {{-- Dropdown --}}
                        <div x-show="open" x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 translate-y-2"
                            x-transition:enter-end="opacity-100 translate-y-0"
                            x-transition:leave="transition ease-in duration-150"
                            x-transition:leave-start="opacity-100 translate-y-0"
                            x-transition:leave-end="opacity-0 translate-y-2"
                            class="absolute left-0 mt-0 w-64 bg-white rounded-xl shadow-xl py-2 z-50 ring-1 ring-black ring-opacity-5 origin-top-left"
                            style="display: none;">

                            {{-- onclick tetap dibiarkan untuk memicu modal, route dihapus --}}
                            <button onclick="openYearModal()"
                                class="block w-full text-left px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 hover:text-blue-600 transition-colors">Pemprov
                                Kalbar</button>
                            <a href="#"
                                class="block px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 hover:text-blue-600 transition-colors">Perangkat
                                Daerah Pemprov</a>
                            <a href="#"
                                class="block px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 hover:text-blue-600 transition-colors">Pemkab/Kota</a>
                            <a href="#"
                                class="block px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 hover:text-blue-600 transition-colors">BUMD</a>
                        </div>
                    </div>

                    {{-- 4. MENU LAPORAN & ANALISIS --}}
                    <div class="relative group" x-data="{ open: false }" @mouseenter="open = true"
                        @mouseleave="open = false">
                        <button
                            class="text-gray-300 hover:text-white px-3 py-2 rounded-md text-sm font-medium transition-colors flex items-center gap-1 focus:outline-none">
                            Laporan & Analisis
                            <i class="fas fa-chevron-down text-[10px] opacity-70 transition-transform duration-300"
                                :class="{'rotate-180': open}"></i>
                        </button>
                        {{-- Dropdown --}}
                        <div x-show="open" x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 translate-y-2"
                            x-transition:enter-end="opacity-100 translate-y-0"
                            x-transition:leave="transition ease-in duration-150"
                            x-transition:leave-start="opacity-100 translate-y-0"
                            x-transition:leave-end="opacity-0 translate-y-2"
                            class="absolute right-0 mt-0 w-64 bg-white rounded-xl shadow-xl py-2 z-50 ring-1 ring-black ring-opacity-5 origin-top-left"
                            style="display: none;">

                            <a href="#"
                                class="block px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 hover:text-blue-600 transition-colors">Panduan
                                Penyusunan DIP</a>
                            <a href="#"
                                class="block px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 hover:text-blue-600 transition-colors">Grafik
                                Perangkat Daerah Pemprov</a>
                            <a href="#"
                                class="block px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 hover:text-blue-600 transition-colors">Grafik
                                Perangkat Daerah Kab/Kota</a>
                            <a href="#"
                                class="block px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 hover:text-blue-600 transition-colors">Grafik
                                Perangkat Daerah BUMD</a>
                        </div>
                    </div>

                </div>
            </div>

            {{-- 5. USER PROFILE (DESKTOP) --}}
            <div class="hidden md:flex items-center ml-4">
                @auth
                    {{-- Storage logic dihapus, ganti dengan placeholder image --}}
                    <div class="relative group" x-data="{ open: false }" @mouseenter="open = true"
                        @mouseleave="open = false">
                        <button class="flex items-center text-sm font-medium text-gray-700 focus:outline-none">
                            {{-- Placeholder Avatar --}}
                            <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=random"
                                alt="Profile" class="h-9 w-9 rounded-full object-cover border-2 border-white/50">
                        </button>
                        <div x-show="open" x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 translate-y-2"
                            x-transition:enter-end="opacity-100 translate-y-0"
                            class="absolute right-0 mt-2 w-56 rounded-md shadow-lg bg-white z-50 ring-1 ring-black ring-opacity-5"
                            style="display: none;">
                            <div class="py-1">
                                <div class="px-4 py-3 border-b">
                                    <p class="text-sm font-semibold text-gray-800">{{ Auth::user()->name }}</p>
                                    <p class="text-xs text-gray-500 truncate">{{ Auth::user()->email }}</p>
                                </div>
                                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Dashboard</a>
                                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Inbox</a>
                                <form method="POST" action="">
                                    @csrf
                                    <a href="#" onclick="event.preventDefault(); this.closest('form').submit();"
                                        class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Logout</a>
                                </form>
                            </div>
                        </div>
                    </div>
                @endauth
            </div>

            {{-- MOBILE MENU BUTTON --}}
            <div class="-mr-2 flex md:hidden">
                <button @click="mobileMenuOpen = !mobileMenuOpen" type="button"
                    class="bg-gray-800/80 inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-white hover:bg-gray-700 focus:outline-none">
                    <span class="sr-only">Open main menu</span>
                    <i class="fas" :class="mobileMenuOpen ? 'fa-times' : 'fa-bars'"></i>
                </button>
            </div>
        </div>
    </div>

    {{-- MOBILE MENU DROPDOWN (FULL WIDTH) --}}
    <div x-show="mobileMenuOpen" x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 -translate-y-2"
        class="md:hidden bg-white border-t border-gray-200 absolute w-full shadow-2xl max-h-[80vh] overflow-y-auto"
        style="display: none;">

        <div class="px-4 py-3 space-y-2">

            {{-- Mobile: Beranda --}}
            <div x-data="{ expanded: false }">
                <button @click="expanded = !expanded"
                    class="flex items-center justify-between w-full px-4 py-3 text-gray-700 hover:bg-blue-50 rounded-lg transition-colors">
                    <span class="font-medium">Beranda</span>
                    <i class="fas fa-chevron-down text-xs transition-transform duration-200"
                        :class="{'rotate-180': expanded}"></i>
                </button>
                <div x-show="expanded" class="ml-4 mt-1 space-y-1 border-l-2 border-blue-100 pl-4">
                    <a href="#" class="block px-2 py-2 text-sm text-gray-600 hover:text-blue-700">Beranda</a>
                    <a href="#" class="block px-2 py-2 text-sm text-gray-600 hover:text-blue-700">Satu Data Kalbar</a>
                    <a href="#" class="block px-2 py-2 text-sm text-gray-600 hover:text-blue-700">JDIH Kalbar</a>
                    @guest
                        <a href="#" class="block px-2 py-2 text-sm text-gray-600 hover:text-blue-700 font-semibold">Sign
                            in</a>
                    @endguest
                </div>
            </div>

            {{-- Mobile: Layanan Informasi --}}
            <div x-data="{ expanded: false }">
                <button @click="expanded = !expanded"
                    class="flex items-center justify-between w-full px-4 py-3 text-gray-700 hover:bg-blue-50 rounded-lg transition-colors">
                    <span class="font-medium">Layanan Informasi</span>
                    <i class="fas fa-chevron-down text-xs transition-transform duration-200"
                        :class="{'rotate-180': expanded}"></i>
                </button>
                <div x-show="expanded" class="ml-4 mt-1 space-y-1 border-l-2 border-blue-100 pl-4">
                    <a href="#" class="block px-2 py-2 text-sm text-gray-600 hover:text-blue-700">Permohonan
                        Informasi</a>
                    <a href="#" class="block px-2 py-2 text-sm text-gray-600 hover:text-blue-700">Keberatan
                        Informasi</a>
                    <a href="#" class="block px-2 py-2 text-sm text-gray-600 hover:text-blue-700">Tata Cara Memperoleh
                        Informasi</a>
                    <a href="#" class="block px-2 py-2 text-sm text-gray-600 hover:text-blue-700">Survey Akses &
                        Kualitas</a>
                    <a href="#" class="block px-2 py-2 text-sm text-gray-600 hover:text-blue-700">Cetak Informasi</a>
                </div>
            </div>

            {{-- Mobile: Instansi Pemerintah --}}
            <div x-data="{ expanded: false }">
                <button @click="expanded = !expanded"
                    class="flex items-center justify-between w-full px-4 py-3 text-gray-700 hover:bg-blue-50 rounded-lg transition-colors">
                    <span class="font-medium">Instansi Pemerintah</span>
                    <i class="fas fa-chevron-down text-xs transition-transform duration-200"
                        :class="{'rotate-180': expanded}"></i>
                </button>
                <div x-show="expanded" class="ml-4 mt-1 space-y-1 border-l-2 border-blue-100 pl-4">
                    <button onclick="openYearModal()"
                        class="block w-full text-left px-2 py-2 text-sm text-gray-600 hover:text-blue-700">Pemprov
                        Kalbar</button>
                    <a href="#" class="block px-2 py-2 text-sm text-gray-600 hover:text-blue-700">Perangkat Daerah
                        Pemprov</a>
                    <a href="#" class="block px-2 py-2 text-sm text-gray-600 hover:text-blue-700">Pemkab/Kota</a>
                    <a href="#" class="block px-2 py-2 text-sm text-gray-600 hover:text-blue-700">BUMD</a>
                </div>
            </div>

            {{-- Mobile: Laporan & Analisis --}}
            <div x-data="{ expanded: false }">
                <button @click="expanded = !expanded"
                    class="flex items-center justify-between w-full px-4 py-3 text-gray-700 hover:bg-blue-50 rounded-lg transition-colors">
                    <span class="font-medium">Laporan & Analisis</span>
                    <i class="fas fa-chevron-down text-xs transition-transform duration-200"
                        :class="{'rotate-180': expanded}"></i>
                </button>
                <div x-show="expanded" class="ml-4 mt-1 space-y-1 border-l-2 border-blue-100 pl-4">
                    <a href="#" class="block px-2 py-2 text-sm text-gray-600 hover:text-blue-700">Panduan Penyusunan
                        DIP</a>
                    <a href="#" class="block px-2 py-2 text-sm text-gray-600 hover:text-blue-700">Grafik Perangkat
                        Daerah Pemprov</a>
                    <a href="#" class="block px-2 py-2 text-sm text-gray-600 hover:text-blue-700">Grafik Perangkat
                        Daerah Kab/Kota</a>
                    <a href="#" class="block px-2 py-2 text-sm text-gray-600 hover:text-blue-700">Grafik Perangkat
                        Daerah BUMD</a>
                </div>
            </div>

            {{-- Mobile: User Profile --}}
            @auth
                <div class="border-t border-gray-200 pt-4 mt-4">
                    <div class="flex items-center px-4 mb-3">
                        {{-- Placeholder Avatar Mobile --}}
                        <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=random"
                            alt="Profile" class="h-10 w-10 rounded-full object-cover mr-3">
                        <div>
                            <p class="text-sm font-semibold text-gray-800">{{ Auth::user()->name }}</p>
                            <p class="text-xs text-gray-500">{{ Auth::user()->email }}</p>
                        </div>
                    </div>
                    <div class="space-y-1">
                        <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Dashboard</a>
                        <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Inbox</a>
                        <form method="POST" action="">
                            @csrf
                            <button type="submit"
                                class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50">Logout</button>
                        </form>
                    </div>
                </div>
            @endauth

        </div>
    </div>
</nav>

{{-- Component Year Modal --}}
{{-- @include('components.year_modal') --}}