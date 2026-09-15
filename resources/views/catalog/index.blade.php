@extends('layouts.app')

@section('title', $title ?? 'Katalog Tanaman Herbal TSTH2')

@section('content')
    <header class="mb-10 text-left">
        <h1 class="font-['Manrope'] text-3xl font-extrabold tracking-tight text-[#003f32] sm:text-[40px]">{{ $title ?? 'Koleksi Tanaman Herbal' }}</h1>
        <p class="mt-3 max-w-[800px] text-sm leading-relaxed text-[#49645b]">Pindai & Jelajahi Koleksi Plasma Nutfah Tanaman Obat Indonesia di Taman Sains Teknologi Herbal dan Hortikultura.</p>
    </header>

    @php
        // Menentukan nama route saat ini agar form dan filter kategori mengarah ke halaman yang tepat
        $currentRoute = request()->routeIs('collection') ? 'collection' : 'home';
    @endphp

    <section class="mb-10 flex flex-col gap-4 xl:flex-row xl:items-center xl:justify-between">
        <form method="GET" action="{{ route($currentRoute) }}" class="relative w-full xl:max-w-[500px]">
            <svg class="absolute left-3.5 top-1/2 h-4 w-4 -translate-y-1/2 text-[#164f43]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="7"/><path d="m20 20-4-4"/></svg>
            <input type="search" name="search" value="{{ request('search') }}" placeholder="Cari nama lokal, taksonomi latin, atau khasiat obat..." class="h-11 w-full rounded-full border border-[#e0e9e1] bg-white pl-10 pr-5 text-xs text-[#123b32] shadow-sm placeholder:text-[#8a9991] focus:border-[#00634e] focus:outline-none focus:ring-1 focus:ring-[#00634e]">
        </form>
        
        <nav class="flex flex-wrap gap-2" aria-label="Filter kategori">
            @php($activeCategory = request('category', 'semua'))
            <a href="{{ route($currentRoute, array_filter(array_merge(request()->query(), ['category' => 'semua']))) }}" class="rounded-full px-4 py-2 text-[11px] font-bold transition {{ $activeCategory === 'semua' ? 'bg-[#003f32] text-white' : 'bg-white text-[#38584d] border border-[#e0e9e1] hover:bg-[#e8f3ea]' }}">● Semua Koleksi</a>
            @foreach ($categories as $cat)
                <a href="{{ route($currentRoute, array_filter(array_merge(request()->query(), ['category' => $cat->slug]))) }}" class="rounded-full bg-white px-4 py-2 text-[11px] font-bold text-[#38584d] border border-[#e0e9e1] transition hover:bg-[#e8f3ea] {{ $activeCategory === $cat->slug ? '!bg-[#003f32] !text-white' : '' }}">{{ $cat->name }}</a>
            @endforeach
        </nav>
    </section>

    <section class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
        @forelse ($plants as $plant)
            <article class="group flex flex-col overflow-hidden rounded-2xl bg-[#fafafa] border border-[#f0f0f0] transition duration-300 hover:shadow-md">
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
                    
                    <a href="{{ route('plant.show', $plant) }}" class="mt-5 flex w-full h-10 items-center justify-center rounded-lg bg-[#003f32] text-[11px] font-bold text-white transition hover:bg-[#002e24]">
                        Lihat Detail Tanaman <span class="ml-1.5">→</span>
                    </a>
                </div>
            </article>
        @empty
            <div class="col-span-full py-20 text-center text-sm text-[#557067]">Tidak ada tanaman yang cocok. <a href="{{ route($currentRoute) }}" class="font-bold text-[#003f32] underline">Reset filter</a></div>
        @endforelse
    </section>

    @if ($plants->hasPages())
        <div class="mt-10 flex justify-center">{{ $plants->links() }}</div>
    @endif
@endsection