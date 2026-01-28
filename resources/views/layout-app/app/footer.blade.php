{{-- resources/views/components/footer.blade.php --}}

<footer class="bg-gradient-to-b from-[#0f2052] to-[#020617] text-white pt-16 pb-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 mb-16">

            {{-- KOLOM KIRI: Identitas & Kontak --}}
            <div class="lg:col-span-5">
                <h3 class="text-2xl font-bold mb-6 leading-tight">
                    PPID Utama Provinsi Kalimantan Barat
                </h3>
                <p class="text-gray-300 mb-8 font-light leading-relaxed max-w-sm">
                    Jalan Ahmad Yani Gedung Pelayanan Terpadu Lt.6, Kalimantan Barat 78124.
                </p>

                {{-- Form Email Minimalis --}}
                <form action="#" class="relative max-w-sm mb-10 group">
                    <input type="email" placeholder="Alamat Email Anda"
                        class="w-full bg-transparent border-b border-gray-600 text-white py-2 focus:outline-none focus:border-white transition-colors placeholder-gray-400">
                    <button type="submit"
                        class="absolute right-0 top-2 text-white hover:translate-x-1 transition-transform">
                        <i class="fas fa-arrow-right"></i>
                    </button>
                </form>

                {{-- Social Media Icons --}}
                <div class="flex space-x-6">
                    <a href="#" class="text-white hover:text-blue-400 transition-colors"><i
                            class="fab fa-facebook-f text-xl"></i></a>
                    <a href="#" class="text-white hover:text-pink-500 transition-colors"><i
                            class="fab fa-instagram text-xl"></i></a>
                    <a href="#" class="text-white hover:text-blue-300 transition-colors"><i
                            class="fab fa-twitter text-xl"></i></a>
                    <a href="#" class="text-white hover:text-red-500 transition-colors"><i
                            class="fab fa-youtube text-xl"></i></a>
                </div>
            </div>

            {{-- KOLOM TENGAH: Link Navigasi (2 Kolom Kecil) --}}
            <div class="lg:col-span-4 flex flex-row gap-12 sm:gap-20">
                <ul class="space-y-4 text-sm text-gray-300">
                    <li><a href="#" class="hover:text-white transition-colors">Beranda</a></li>
                    <li><a href="#" class="hover:text-white transition-colors">About</a></li>
                    <li><a href="#" class="hover:text-white transition-colors">FAQ</a></li>
                    <li><a href="#" class="hover:text-white transition-colors">Terms of Use</a></li>
                    <li><a href="#" class="hover:text-white transition-colors">Privacy</a></li>
                </ul>
                <ul class="space-y-4 text-sm text-gray-300">
                    <li><a href="#" class="hover:text-white transition-colors">Diskominfo Kalbar</a></li>
                    <li><a href="#" class="hover:text-white transition-colors">Sikedip</a></li>
                    <li><a href="#" class="hover:text-white transition-colors">PPID</a></li>
                    <li><a href="#" class="hover:text-white transition-colors">Satu Data Kalbar</a></li>
                    <li><a href="#" class="hover:text-white transition-colors">Dijih Kalbar</a></li>
                </ul>
            </div>

            {{-- KOLOM KANAN: Download Apps --}}
            <div class="lg:col-span-3">
                <h4 class="text-lg font-semibold mb-6">Dapatkan Aplikasinya</h4>
                <div class="flex flex-col gap-4">
                    {{-- Google Play Button --}}
                    <a href="#"
                        class="flex items-center bg-gray-800/80 border border-gray-700 rounded-lg px-4 py-2 hover:bg-gray-700 transition-all w-fit min-w-[160px]">
                        <i
                            class="fab fa-google-play text-2xl mr-3 bg-gradient-to-r from-blue-400 to-green-400 bg-clip-text text-transparent"></i>
                        <div class="text-left">
                            <div class="text-[10px] uppercase text-gray-400">GET IT ON</div>
                            <div class="text-sm font-bold">Google Play</div>
                        </div>
                    </a>

                    {{-- App Store Button --}}
                    <a href="#"
                        class="flex items-center bg-gray-800/80 border border-gray-700 rounded-lg px-4 py-2 hover:bg-gray-700 transition-all w-fit min-w-[160px]">
                        <i class="fab fa-apple text-2xl mr-3 text-white"></i>
                        <div class="text-left">
                            <div class="text-[10px] uppercase text-gray-400">DOWNLOAD ON</div>
                            <div class="text-sm font-bold">App Store</div>
                        </div>
                    </a>
                </div>
            </div>

        </div>

        {{-- Copyright --}}
        <div class="border-t border-gray-800 pt-8 text-xs text-gray-500">
            &copy; 2025 PPID Kalbar. All Rights Reserved.
        </div>
    </div>
</footer>