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
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Manrope:wght@400;500;600;700;800&family=Montserrat:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        body { font-family: 'DM Sans', sans-serif; }
    </style>
</head>
<body class="bg-gray-50 text-gray-800">

    {{-- Header & Navigasi Publik dengan Dropdown Katalog --}}
    <header class="border-b border-gray-200 bg-white sticky top-0 z-40 shadow-sm">
        <div class="mx-auto flex max-w-[1180px] items-center justify-between px-4 py-4 sm:px-7 lg:px-10">
            <!-- Logo / Judul Web -->
            <a href="{{ route('home') }}" class="font-['Manrope'] text-lg font-extrabold text-[#003f32]">
                TSTH2 <span class="font-normal text-gray-500">Katalog Herbal</span>
            </a>

            <!-- Menu Navigasi -->
            <nav class="flex items-center gap-4 sm:gap-6">
                <!-- Dropdown Menu Katalog -->
                <div class="relative hidden group md:block">
                    <button class="flex items-center gap-1 text-xs font-bold text-[#38584d] hover:text-[#003f32] py-2">
                        Katalog
                        <svg class="h-4 w-4 transition-transform group-hover:rotate-180" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <!-- Kotak Dropdown -->
                    <div class="absolute left-0 mt-0 hidden w-48 rounded-lg bg-white shadow-lg ring-1 ring-black ring-opacity-5 group-hover:block z-50">
                        <div class="py-1">
                            <a href="{{ route('home') }}" class="block px-4 py-2.5 text-xs font-semibold text-gray-700 hover:bg-[#f2fbf4] hover:text-[#003f32]">
                                Greenhouse
                            </a>
                            <a href="{{ route('collection') }}" class="block px-4 py-2.5 text-xs font-semibold text-gray-700 hover:bg-[#f2fbf4] hover:text-[#003f32]">
                                Outdoor
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Menu Katalog Mobile -->
                <details class="relative md:hidden">
                    <summary class="flex cursor-pointer list-none items-center gap-1 py-2 text-xs font-bold text-[#38584d] hover:text-[#003f32]">
                        Katalog
                        <svg class="h-4 w-4 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                        </svg>
                    </summary>
                    <div class="absolute right-0 top-full z-50 mt-2 w-52 rounded-lg bg-white p-1 shadow-lg ring-1 ring-black/5">
                        <a href="{{ route('home') }}" class="block rounded-md px-4 py-2.5 text-xs font-semibold text-gray-700 hover:bg-[#f2fbf4] hover:text-[#003f32]">
                            Greenhouse
                        </a>
                        <a href="{{ route('collection') }}" class="block rounded-md px-4 py-2.5 text-xs font-semibold text-gray-700 hover:bg-[#f2fbf4] hover:text-[#003f32]">
                            Outdoor
                        </a>
                    </div>
                </details>

                <!-- Tombol Akses Login Admin (Opsional jika dibutuhkan) -->
                <a href="{{ route('admin.login') }}" class="text-xs font-bold text-gray-500 hover:text-[#003f32]">
                    Admin Login
                </a>
            </nav>
        </div>
    </header>

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