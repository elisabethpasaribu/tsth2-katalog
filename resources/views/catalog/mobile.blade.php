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

    <section class="mb-4 flex flex-col gap-2.5">
        <!-- Kolom Pencarian -->
        <form method="GET" action="{{ route($currentRoute) }}" class="relative w-full">
            <svg class="absolute left-3.5 top-1/2 h-4 w-4 -translate-y-1/2 text-[#164f43]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="7"/><path d="m20 20-4-4"/></svg>
            <input type="search" name="search" value="{{ request('search') }}" placeholder="Cari nama tanaman atau khasiat..." class="h-10 w-full rounded-full border border-[#e0e9e1] bg-white pl-10 pr-4 text-xs text-[#123b32] shadow-sm focus:border-[#00634e] focus:outline-none">
        </form>
        
        <!-- Filter Kategori (Tetap 1 baris sesuai permintaan sebelumnya) -->
        <nav class="flex flex-nowrap items-center gap-1.5 overflow-x-auto pb-1.5 scrollbar-none" aria-label="Filter kategori">
            @php($activeCategory = request('category', 'semua'))
            <a href="{{ route($currentRoute, array_filter(array_merge(request()->query(), ['category' => 'semua']))) }}" style="display: inline-block; white-space: nowrap;" class="flex-shrink-0 rounded-full px-3 py-1 text-[10px] font-semibold transition {{ $activeCategory === 'semua' ? 'bg-[#003f32] text-white' : 'bg-white text-[#38584d] border border-[#e0e9e1]' }}">Semua Koleksi</a>
            @foreach ($categories as $cat)
                <a href="{{ route($currentRoute, array_filter(array_merge(request()->query(), ['category' => $cat->slug]))) }}" style="display: inline-block; white-space: nowrap;" class="flex-shrink-0 rounded-full px-3 py-1 text-[10px] font-semibold transition {{ $activeCategory === $cat->slug ? 'bg-[#003f32] text-white' : 'bg-white text-[#38584d] border border-[#e0e9e1]' }}">{{ $cat->name }}</a>
            @endforeach
        </nav>
    </section>

    <!-- Info Jumlah Spesies -->
    <p class="mb-2.5 text-[11px] text-[#557067]">Menampilkan {{ $plants->total() ?? count($plants) }} spesies</p>

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