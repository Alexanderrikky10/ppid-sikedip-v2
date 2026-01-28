<!DOCTYPE html>
<html lang="en" x-data="{ scrolled: false }" @scroll.window="scrolled = (window.pageYOffset > 20)">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIKEDIP - Sistem Kelola Daftar Informasi Publik</title>

    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    {{-- Icons --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    {{-- Scripts & Styles --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

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