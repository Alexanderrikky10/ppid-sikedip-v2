{{-- resources/views/components/footer.blade.php --}}

<footer class="bg-gradient-to-b from-green-900 to-gray-900 text-white pt-16 pb-8 border-t border-green-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 mb-16">

            {{-- KOLOM KIRI: Identitas & Kontak --}}
            <div class="lg:col-span-5">
                <div class="flex items-center gap-3 mb-6">
                    {{-- Opsional: Jika ada logo putih, bisa ditaruh disini --}}
                    {{-- <img src="/path/to/logo-white.png" class="h-10" alt="Logo"> --}}
                    <h3 class="text-2xl font-bold leading-tight">
                        PPID Utama <br> Provinsi Kalimantan Barat
                    </h3>
                </div>

                <p class="text-green-100/80 mb-8 font-light leading-relaxed max-w-sm">
                    Jalan Ahmad Yani Gedung Pelayanan Terpadu Lt.6, Kalimantan Barat 78124.
                </p>

                {{-- Form Email Minimalis --}}
                <form action="#" class="relative max-w-sm mb-10 group">
                    <input type="email" placeholder="Alamat Email Anda"
                        class="w-full bg-transparent border-b border-green-700 text-white py-2 focus:outline-none focus:border-green-400 transition-colors placeholder-green-200/50">
                    <button type="submit"
                        class="absolute right-0 top-2 text-green-400 hover:text-white hover:translate-x-1 transition-all">
                        <i class="fas fa-arrow-right"></i>
                    </button>
                </form>

                {{-- Social Media Icons --}}
                <div class="flex space-x-6">
                    <a href="#" class="text-green-200 hover:text-white transition-colors"><i
                            class="fab fa-facebook-f text-xl"></i></a>
                    <a href="#" class="text-green-200 hover:text-white transition-colors"><i
                            class="fab fa-instagram text-xl"></i></a>
                    <a href="#" class="text-green-200 hover:text-white transition-colors"><i
                            class="fab fa-twitter text-xl"></i></a>
                    <a href="#" class="text-green-200 hover:text-white transition-colors"><i
                            class="fab fa-youtube text-xl"></i></a>
                </div>
            </div>

            {{-- KOLOM TENGAH: Link Navigasi (2 Kolom Kecil) --}}
            <div class="lg:col-span-4 flex flex-row gap-12 sm:gap-20">
                <ul class="space-y-4 text-sm text-green-100/70">
                    <li><a href="#" class="hover:text-green-400 transition-colors">Beranda</a></li>
                    <li><a href="#" class="hover:text-green-400 transition-colors">Tentang Kami</a></li>
                    <li><a href="#" class="hover:text-green-400 transition-colors">FAQ</a></li>
                    <li><a href="#" class="hover:text-green-400 transition-colors">Syarat & Ketentuan</a></li>
                    <li><a href="#" class="hover:text-green-400 transition-colors">Kebijakan Privasi</a></li>
                </ul>
                <ul class="space-y-4 text-sm text-green-100/70">
                    <li><a href="#" class="hover:text-green-400 transition-colors">Diskominfo Kalbar</a></li>
                    <li><a href="#" class="hover:text-green-400 transition-colors">Sikedip</a></li>
                    <li><a href="#" class="hover:text-green-400 transition-colors">PPID</a></li>
                    <li><a href="#" class="hover:text-green-400 transition-colors">Satu Data Kalbar</a></li>
                    <li><a href="#" class="hover:text-green-400 transition-colors">JDIH Kalbar</a></li>
                </ul>
            </div>

            {{-- KOLOM KANAN: Download Apps --}}
            <div class="lg:col-span-3">
                <h4 class="text-lg font-semibold mb-6 text-green-50">Dapatkan Aplikasinya</h4>
                <div class="flex flex-col gap-4">
                    {{-- Google Play Button --}}
                    <a href="#"
                        class="flex items-center bg-gray-900/50 border border-green-800 rounded-xl px-4 py-3 hover:bg-green-900/50 hover:border-green-500 transition-all w-fit min-w-[170px] group">
                        <i
                            class="fab fa-google-play text-2xl mr-4 text-gray-300 group-hover:text-green-400 transition-colors"></i>
                        <div class="text-left">
                            <div class="text-[10px] uppercase text-green-200/60">GET IT ON</div>
                            <div class="text-sm font-bold text-white">Google Play</div>
                        </div>
                    </a>

                    {{-- App Store Button --}}
                    <a href="#"
                        class="flex items-center bg-gray-900/50 border border-green-800 rounded-xl px-4 py-3 hover:bg-green-900/50 hover:border-green-500 transition-all w-fit min-w-[170px] group">
                        <i
                            class="fab fa-apple text-2xl mr-4 text-gray-300 group-hover:text-white transition-colors"></i>
                        <div class="text-left">
                            <div class="text-[10px] uppercase text-green-200/60">DOWNLOAD ON</div>
                            <div class="text-sm font-bold text-white">App Store</div>
                        </div>
                    </a>
                </div>
            </div>

        </div>

        {{-- Copyright --}}
        <div
            class="border-t border-green-800/50 pt-8 flex flex-col md:flex-row justify-between items-center text-xs text-green-200/40">
            <p>&copy; {{ date('Y') }} PPID Provinsi Kalimantan Barat. All Rights Reserved.</p>
            <p class="mt-2 md:mt-0">Designed for SIKEDIP</p>
        </div>
    </div>
</footer>