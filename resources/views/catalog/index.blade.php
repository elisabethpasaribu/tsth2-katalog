@extends('layouts.app')

@section('title', $title ?? 'Katalog Tanaman Herbal TSTH2')

@section('content')

    @php
        $currentRoute = request()->routeIs('collection') ? 'collection' : 'home';
        $totalPlants = $plants instanceof \Illuminate\Pagination\LengthAwarePaginator ? $plants->total() : $plants->count();
        $displayedPlants = $plants->count();
        $totalCategories = isset($categories) ? $categories->count() : 0;
    @endphp

    {{-- 1. BAGIAN HEADER --}}
    <header class="mb-10 text-center">

        <!-- Judul Besar -->
        <h1 class="text-4xl font-extrabold tracking-tight text-emerald-900 sm:text-5xl md:text-6xl" style="font-family: 'Montserrat', sans-serif;">
            Katalog Tanaman
        </h1>
        <h2 class="mt-2 text-3xl font-bold italic text-yellow-600 sm:text-4xl md:text-5xl" style="font-family: serif;">
            Koleksi Greenhouse & Outdoor
        </h2>

        <!-- Kotak Deskripsi -->
        <div class="mx-auto mt-5 max-w-3xl rounded-full border border-gray-200 bg-white px-8 py-4 shadow-sm">
            <p class="text-xs font-bold leading-relaxed tracking-widest text-gray-600 uppercase">
                Hadirkan nuansa alam ke dalam lingkungan Anda. Dari tanaman rimpang hingga koleksi langka, setiap tanaman dirawat khusus untuk tumbuh sehat di TSTH2.
            </p>
        </div>

        <!-- Statistik -->
        <div class="mt-7 flex flex-wrap items-center justify-center gap-8 sm:gap-16">
            <div class="flex flex-col items-center">
                <span class="text-4xl font-bold text-emerald-900">{{ $totalPlants }}</span>
                <span class="mt-2 text-[10px] font-bold uppercase tracking-wider text-gray-500">JENIS TANAMAN</span>
            </div>
            <div class="flex flex-col items-center">
                <span class="text-4xl font-bold text-emerald-900">{{ $totalCategories }}</span>
                <span class="mt-2 text-[10px] font-bold uppercase tracking-wider text-gray-500">KATEGORI</span>
            </div>
        </div>
    </header>

    {{-- 2. LOKASI, PENCARIAN & FILTER --}}
    <section class="mb-9 rounded-[2rem] border border-gray-200 bg-white p-5 shadow-sm sm:p-7">
        <form method="GET" action="{{ route($currentRoute) }}">
            <input type="hidden" name="sort" value="{{ request('sort', 'default') }}">
            <!-- Search Box -->
            <div class="relative mb-3">
                <!-- Ikon Kaca Pembesar -->
                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-5">
                    <svg class="h-5 w-5 text-gray-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="11" cy="11" r="7"/><path d="m21 21-4.35-4.35"/></svg>
                </div>
                <!-- Input Teks -->
                <input type="search" name="search" value="{{ request('search') }}" placeholder="Cari nama tanaman (contoh: Monstera, Calathea...)" class="block w-full rounded-full border border-gray-200 bg-gray-50 py-3.5 pl-14 pr-6 text-sm text-gray-900 placeholder-gray-500 focus:border-emerald-900 focus:bg-white focus:outline-none focus:ring-1 focus:ring-emerald-900">
            </div>

            <!-- Lokasi utama -->
            <nav class="mb-3 flex flex-wrap gap-2" aria-label="Pilih lokasi utama">
                <a href="{{ route('home', request()->except('location')) }}" class="inline-flex items-center gap-2 rounded-full border px-5 py-2 text-sm font-bold transition {{ $currentRoute === 'home' ? 'border-[#003f32] bg-[#003f32] text-white shadow-sm' : 'border-gray-200 bg-white text-gray-700 hover:border-[#003f32]' }}">
                    <span>Greenhouse <span class="font-normal opacity-80">({{ $locationCounts->get('greenhouse', 0) }} Tanaman)</span></span>
                </a>
                <a href="{{ route('collection', request()->except('location')) }}" class="inline-flex items-center gap-2 rounded-full border px-5 py-2 text-sm font-bold transition {{ $currentRoute === 'collection' ? 'border-[#003f32] bg-[#003f32] text-white shadow-sm' : 'border-gray-200 bg-white text-gray-700 hover:border-[#003f32]' }}">
                    <span>Outdoor <span class="font-normal opacity-80">({{ $locationCounts->get('collection', 0) }} Tanaman)</span></span>
                </a>
            </nav>

            <!-- Tombol Filter Kategori -->
            <div class="flex flex-nowrap items-center gap-1.5 overflow-x-auto pb-1 scrollbar-none">
                <a href="{{ route($currentRoute, request()->except('category')) }}" class="inline-flex flex-shrink-0 items-center rounded-full border border-gray-200 bg-white px-3 py-1.5 text-[11px] font-bold text-gray-700 transition hover:bg-gray-50">
                    Semua
                </a>
                @foreach ($categories as $cat)
                    <label class="flex-shrink-0 cursor-pointer">
                        <input type="checkbox" name="category[]" value="{{ $cat->slug }}" class="peer sr-only" onchange="this.form.submit()" @checked(in_array($cat->slug, $selectedCategories, true))>
                        <span class="inline-flex items-center rounded-full border border-gray-200 bg-white px-3 py-1.5 text-[11px] font-bold text-gray-700 transition hover:bg-gray-50 peer-checked:border-yellow-600 peer-checked:bg-yellow-600 peer-checked:text-white">
                            {{ $cat->name }}
                        </span>
                    </label>
                @endforeach
                <a href="{{ route($currentRoute, request()->except('category')) }}" class="ml-auto flex-shrink-0 rounded-md border border-gray-300 bg-gray-50 px-3 py-1.5 text-[11px] font-bold text-gray-700 transition hover:bg-gray-100">Reset Filter</a>
            </div>
        </form>
    </section>

    {{-- 3. JUMLAH DAN GRID DAFTAR TANAMAN --}}
    <p class="mb-4 text-sm text-[#557067]">
        Menampilkan <span class="font-bold text-[#003f32]">{{ $displayedPlants }}</span> dari
        <span class="font-bold text-[#003f32]">{{ $totalPlants }}</span> tanaman
    </p>

    <section class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
        @forelse ($plants as $plant)
            <a href="{{ route('plant.show', $plant) }}" class="group flex flex-col overflow-hidden rounded-2xl bg-[#fafafa] border border-[#f0f0f0] transition duration-300 hover:shadow-md">
                <div class="relative h-[200px] w-full overflow-hidden bg-white">
                    @if ($plant->image)
                        <img src="{{ asset('storage/plants/' . $plant->image) }}" alt="{{ $plant->name }}" class="h-full w-full object-cover">
                    @else
                        <div class="flex h-full items-center justify-center text-[#477568]"><span class="text-4xl opacity-50">🌿</span></div>
                    @endif
                    <span class="absolute right-3 top-3 rounded bg-[#fef0c7] px-2.5 py-1 text-[9px] font-bold uppercase tracking-wider text-[#b54708] shadow-sm">{{ $plant->category?->name ?? 'Herbal' }}</span>
                </div>

                <div class="flex flex-1 flex-col p-5">
                    <h2 class="font-['Manrope'] text-lg font-extrabold text-[#111827]">{{ $plant->name }}</h2>
                    <p class="text-[11px] italic text-[#d97706]">{{ $plant->latin_name }}</p>
                    <p class="mt-1.5 text-[9px] font-bold uppercase tracking-wider text-[#6b7280]">FAMILI: {{ $plant->family }}</p>
                    <p class="mt-3 line-clamp-3 flex-1 text-[11px] leading-relaxed text-[#4b5563]">{{ $plant->description_short }}</p>

                    <span class="mt-5 flex h-10 w-full items-center justify-center rounded-lg bg-[#003f32] text-[11px] font-bold text-white transition group-hover:bg-[#002e24]">
                        Lihat Detail Tanaman <span class="ml-1.5">→</span>
                    </span>
                </div>
            </a>
        @empty
            <div class="col-span-full py-20 text-center text-sm text-[#557067]">Tidak ada tanaman yang cocok. <a href="{{ route($currentRoute) }}" class="font-bold text-[#003f32] underline">Reset filter</a></div>
        @endforelse
    </section>

    {{-- PAGINASI --}}
    @if ($plants->hasPages())
        <div class="mt-10 flex justify-center">{{ $plants->links() }}</div>
    @endif
@endsection