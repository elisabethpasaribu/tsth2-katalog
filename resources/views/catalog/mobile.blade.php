@extends('layouts.app')

@section('title', $title ?? 'Katalog Tanaman Herbal TSTH2')

@section('content')
    <header class="mb-4 text-left">
        <h1 class="font-['Manrope'] text-xl font-extrabold tracking-tight text-[#003f32]">{{ $title ?? 'Koleksi Tanaman Herbal' }}</h1>
        <p class="mt-0.5 text-[11px] leading-relaxed text-[#49645b]">Pindai & Jelajahi Koleksi Plasma Nutfah Tanaman Obat Indonesia.</p>
    </header>

    @php
        $currentRoute = request()->routeIs('collection') ? 'collection' : 'home';
    @endphp

    <nav class="mb-4 flex rounded-full bg-gray-100 p-1" aria-label="Pilih lokasi utama">
        <a href="{{ route('home', request()->except('location')) }}" class="flex-1 rounded-full px-3 py-2 text-center text-[11px] font-bold transition {{ $currentRoute === 'home' ? 'bg-[#003f32] text-white shadow-sm' : 'text-[#38584d]' }}">Greenhouse</a>
        <a href="{{ route('collection', request()->except('location')) }}" class="flex-1 rounded-full px-3 py-2 text-center text-[11px] font-bold transition {{ $currentRoute === 'collection' ? 'bg-[#003f32] text-white shadow-sm' : 'text-[#38584d]' }}">Outdoor</a>
    </nav>

    <section class="mb-4 flex flex-col gap-2.5">
        <form method="GET" action="{{ route($currentRoute) }}" class="relative w-full">
            <svg class="absolute left-3.5 top-1/2 h-4 w-4 -translate-y-1/2 text-[#164f43]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="7"/><path d="m20 20-4-4"/></svg>
            <input type="search" name="search" value="{{ request('search') }}" placeholder="Cari nama tanaman atau khasiat..." class="h-10 w-full rounded-full border border-[#e0e9e1] bg-white pl-10 pr-4 text-xs text-[#123b32] shadow-sm focus:border-[#00634e] focus:outline-none">
        
        <nav class="flex flex-nowrap items-center gap-1.5 overflow-x-auto pb-1.5 scrollbar-none" aria-label="Filter kategori">
            @php
                $categoryIconPaths = [
                    'rimpang-umbi' => '<path stroke-linecap="round" stroke-linejoin="round" d="M12 3v18m0-14c-3 0-5 1.5-6 4m6-1c3 0 5 1.5 6 4M9 21c-2.5-1-4-2.8-4-5m11 5c2.5-1 4-2.8 4-5M8 7c-1.5-1.5-2.5-2-4-2m12 2c1.5-1.5 2.5-2 4-2" />',
                    'daun-herbal' => '<path stroke-linecap="round" stroke-linejoin="round" d="M20 4C10 4 4 9 4 17c0 1.1.9 2 2 2 8 0 13-6 14-15ZM4 19c2.5-4 5.5-6.5 10-9" />',
                    'bunga-biji' => '<path stroke-linecap="round" stroke-linejoin="round" d="M12 13v8m0-8c-3 0-5-2-5-4.5S9 4 12 7c3-3 5-1 5 1.5S15 13 12 13Zm0-6C9 4 7 1 5 3c-2 2 1 5 4 6m3-5c3-3 5-3 7-1 2 2-1 5-4 6" />',
                    'kayu-kulit' => '<path stroke-linecap="round" stroke-linejoin="round" d="M12 21V8m0 3c-3 0-5-2-6-5m6 7c3 0 5-2 6-5M12 8c-2-2-2-4 0-6 2 2 2 4 0 6Z" />',
                    'akar' => '<path stroke-linecap="round" stroke-linejoin="round" d="M12 3v8m0 0c-2 2-5 3-7 2m7-2c2 2 5 3 7 2M12 11c-1 4-3 6-6 8m6-8c1 4 3 6 6 8" />',
                    'batang' => '<path stroke-linecap="round" stroke-linejoin="round" d="M12 21V3m0 6c-3 0-5-1-6-4m6 8c3 0 5-1 6-4M12 6c-2-2-2-3 0-4 2 1 2 2 0 4Z" />',
                ];
            @endphp
            @foreach ($categories as $cat)
                <label class="flex-shrink-0 cursor-pointer">
                    <input type="checkbox" name="category[]" value="{{ $cat->slug }}" class="peer sr-only" onchange="this.form.submit()" @checked(in_array($cat->slug, $selectedCategories, true))>
                    <span style="white-space: nowrap;" class="inline-flex items-center gap-1 rounded-full border border-[#e0e9e1] bg-white px-3 py-1 text-[10px] font-semibold text-[#38584d] transition peer-checked:border-[#003f32] peer-checked:bg-[#003f32] peer-checked:text-white">
                    <svg class="h-3 w-3 opacity-80" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        {!! $categoryIconPaths[$cat->slug] ?? '<path stroke-linecap="round" stroke-linejoin="round" d="M12 3v18m0-14c-4 0-7 3-7 7m7-3c4 0 7 3 7 7" />' !!}
                    </svg>
                    {{ $cat->name }}
                    </span>
                </label>
            @endforeach
            <a href="{{ route($currentRoute, request()->except('category')) }}" class="flex-shrink-0 rounded-full border border-red-200 px-3 py-1 text-[10px] font-semibold text-red-700">Reset Filter</a>
        </nav>
        </form>
    </section>

    <!-- Info Jumlah Tanaman -->
    <p class="mb-2.5 text-[11px] text-[#557067]">
        Menampilkan <span class="font-bold text-[#003f32]">{{ $plants->count() }}</span> dari
        <span class="font-bold text-[#003f32]">{{ $plants->total() }}</span> tanaman
    </p>

    <!-- Tampilan List Mobile -->
    <!-- PERUBAHAN DI SINI: space-y-2.5 diubah menjadi space-y-4 agar jarak antar kartu lebih lega -->
    <section class="space-y-4">
        @forelse ($plants as $plant)
            <a href="{{ route('plant.show', $plant) }}" class="flex items-center gap-3 rounded-2xl bg-white border border-[#e0e9e1] p-2.5 shadow-sm transition hover:bg-[#fafafa]">
                <!-- Gambar Mini (Terkunci kecil 56x56 px) -->
                <div style="width: 56px; height: 56px; min-width: 56px; min-height: 56px;" class="overflow-hidden rounded-xl bg-[#f0f5f1]">
                    @if ($plant->image)
                        <img src="{{ asset('storage/plants/' . $plant->image) }}" alt="{{ $plant->name }}" style="width: 56px; height: 56px;" class="object-cover">
                    @else
                        <div class="flex h-full w-full items-center justify-center text-base">🌿</div>
                    @endif
                </div>
                
                <!-- Informasi Teks -->
                <div class="min-w-0 flex-1">
                    <div class="flex items-center justify-between gap-2 mb-0.5">
                        <h2 class="truncate font-['Manrope'] text-xs font-extrabold text-[#111827]">{{ $plant->name }}</h2>
                        <!-- Nama Kategori di Kartu -->
                        <span class="flex-shrink-0 rounded bg-[#fef0c7] px-2 py-0.5 text-[9px] font-bold text-[#b54708]">{{ $plant->category?->name ?? 'Herbal' }}</span>
                    </div>
                    <p class="truncate text-[10px] italic text-[#d97706] mb-0.5">{{ $plant->latin_name }}</p>
                    <p class="line-clamp-1 text-[10px] text-[#4b5563]">{{ $plant->description_short }}</p>
                </div>

                <!-- Panah Kanan -->
                <div class="flex-shrink-0 text-[#8a9991] pl-1">
                    <span class="text-xs font-bold">›</span>
                </div>
            </a>
        @empty
            <div class="py-10 text-center text-xs text-[#557067]">Tidak ada tanaman yang cocok.</div>
        @endforelse
    </section>

    @if ($plants->hasPages())
        <div class="mt-5 flex justify-center text-xs">{{ $plants->links() }}</div>
    @endif
@endsection