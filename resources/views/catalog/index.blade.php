@extends('layouts.app')

@section('title', 'Koleksi Tanaman Herbal Greenhouse TSTH2')

@section('content')
    <header class="mb-8 text-center sm:text-left">
        <h1 class="font-['Manrope'] text-3xl font-extrabold tracking-tight text-[#003f32] sm:text-[40px]">Koleksi Tanaman Herbal Greenhouse TSTH2</h1>
        <p class="mt-2 max-w-[690px] text-xs leading-5 text-[#49645b] sm:text-sm">Pindai &amp; Jelajahi Koleksi Plasma Nutfah Tanaman Obat Indonesia di Taman Sains Teknologi Herbal<br class="hidden sm:block"> dan Hortikultura.</p>
    </header>

    <section class="mb-8 flex flex-col gap-3 xl:flex-row xl:items-center xl:justify-between">
        <form method="GET" action="{{ route('home') }}" class="relative w-full xl:max-w-[480px]">
            <svg class="absolute left-3 top-1/2 h-3.5 w-3.5 -translate-y-1/2 text-[#164f43]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="7"/><path d="m20 20-4-4"/></svg>
            <input type="search" name="search" value="{{ request('search') }}" placeholder="Cari nama lokal, taksonomi latin, atau khasiat obat..." class="h-10 w-full rounded-lg border-0 bg-white pl-9 pr-4 text-[11px] text-[#123b32] shadow-sm ring-1 ring-[#e0e9e1] placeholder:text-[#8a9991] focus:ring-2 focus:ring-[#00634e]">
        </form>
        <nav class="flex flex-wrap gap-1.5" aria-label="Filter kategori">
            @php($activeCategory = request('category', 'semua'))
            <a href="{{ route('home', array_filter(array_merge(request()->query(), ['category' => 'semua']))) }}" class="rounded-md px-3 py-2 text-[10px] font-bold transition {{ $activeCategory === 'semua' ? 'bg-[#003f32] text-white' : 'bg-white text-[#38584d] ring-1 ring-[#e0e9e1] hover:bg-[#e8f3ea]' }}">●&nbsp; Semua Koleksi</a>
            @foreach ($categories as $cat)
                <a href="{{ route('home', array_filter(array_merge(request()->query(), ['category' => $cat->slug]))) }}" class="rounded-md bg-white px-3 py-2 text-[10px] font-bold text-[#38584d] ring-1 ring-[#e0e9e1] transition hover:bg-[#e8f3ea] {{ $activeCategory === $cat->slug ? '!bg-[#003f32] !text-white' : '' }}">{{ $cat->name }}</a>
            @endforeach
        </nav>
    </section>

    <section class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
        @forelse ($plants as $plant)
            <article class="catalog-card group overflow-hidden rounded-xl bg-white transition duration-300">
                <div class="plant-photo relative h-[126px] overflow-hidden">
                    @if ($plant->image)
                        <img src="{{ asset('storage/plants/' . $plant->image) }}" alt="{{ $plant->name }}" class="h-full w-full object-cover">
                    @else
                        <div class="flex h-full items-center justify-center text-[#477568]"><span class="text-4xl opacity-50">🌿</span></div>
                    @endif
                    <span class="absolute right-2 top-2 rounded-full bg-[#f5c329] px-2 py-1 text-[8px] font-bold uppercase tracking-wide text-[#5b4100]">{{ $plant->category?->name ?? 'Herbal' }}</span>
                </div>
                <div class="flex min-h-[158px] flex-col p-3">
                    <h2 class="font-['Manrope'] text-sm font-extrabold text-[#064a3c]">{{ $plant->name }}</h2>
                    <p class="text-[10px] italic text-[#c28100]">{{ $plant->latin_name }}</p>
                    <p class="mt-0.5 text-[8px] font-bold uppercase tracking-wide text-[#41564d]">FAMILI: {{ $plant->family }}</p>
                    <p class="mt-2 line-clamp-3 flex-1 text-[9px] leading-[1.45] text-[#5d6c65]">{{ $plant->description_short }}</p>
                    <a href="{{ route('plant.show', $plant) }}" class="mt-2 flex h-6 items-center justify-center rounded-md bg-[#003f32] text-[9px] font-bold text-white transition hover:bg-[#00634e]">Lihat Detail Tanaman <span class="ml-1 text-[#f5c329]">→</span></a>
                </div>
            </article>
        @empty
            <div class="col-span-full py-20 text-center text-sm text-[#557067]">Tidak ada tanaman yang cocok. <a href="{{ route('home') }}" class="font-bold text-[#003f32] underline">Reset filter</a></div>
        @endforelse
    </section>

    @if ($plants->hasPages())
        <div class="mt-8 flex justify-center">{{ $plants->links() }}</div>
    @endif
@endsection
