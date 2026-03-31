<!DOCTYPE html>
<html lang="en" x-data="{ scrolled: false }" @scroll.window="scrolled = (window.pageYOffset > 20)">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIKEDIP - Sistem Kelola Daftar Informasi Publik</title>

    {{-- Semua sudah di-bundle oleh Vite --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        .fade-enter-active,
        .fade-leave-active {
            transition: opacity 1s ease;
        }

        .fade-enter,
        .fade-leave-to {
            opacity: 0;
        }
    </style>
</head>

<body class="antialiased bg-gray-50">

    {{-- Include Navbar (Path sesuai permintaan) --}}
    @include('layout-app.app.navbar')

    {{-- Main Content --}}
    <main>
        @yield('content')
    </main>

</body>

</html>