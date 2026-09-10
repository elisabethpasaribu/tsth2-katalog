<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Katalog Tanaman Herbal TSTH2')</title>

    {{-- Load CSS & JS dari Vite --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Font yang digunakan pada desain katalog --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        body { font-family: 'DM Sans', sans-serif; }
    </style>
</head>
<body class="bg-gray-50 text-gray-800">

    {{-- Konten halaman --}}
    <main class="catalog-page min-h-screen px-4 py-12 sm:px-7 lg:px-10">
        <div class="mx-auto max-w-[1180px]">
        @yield('content')
        </div>
    </main>

    {{-- Footer --}}
    @include('layouts.footer')

</body>
</html>