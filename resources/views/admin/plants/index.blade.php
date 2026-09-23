@extends('layouts.admin')

@section('title', 'Kelola Tanaman | Admin TSTH2')

@section('content')
    <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-end">
        <div>
            <h1 class="mt-2 font-['Manrope'] text-3xl font-extrabold text-[#003f32]">Kelola Tanaman</h1>
            <p class="mt-1 text-sm text-[#718178]">Tambah, ubah, atau hapus data katalog</p>
        </div>
    </div>

    @if (session('success'))
        <div class="mt-6 rounded-lg bg-[#e8f3df] px-4 py-3 text-xs font-bold text-[#28604d]">{{ session('success') }}</div>
    @endif

    {{-- RINGKASAN DATA & STATISTIK KOLEKSI --}}
    <div class="mt-5 space-y-4">
        {{-- BAGIAN 1: STATISTIK UTAMA (LOKASI) --}}
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            {{-- Kartu Lokasi: Greenhouse --}}
            <div class="flex items-center justify-between rounded-2xl border border-[#dbe9dd] bg-white p-5 shadow-[0_5px_18px_rgba(0,63,50,.05)] transition-all hover:border-[#00634e]">
                <div class="flex items-center gap-4">
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-green-50 text-green-700">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs font-bold uppercase tracking-wider text-[#718178]">Greenhouse</p>
                        <h3 class="mt-1 text-xl font-extrabold text-[#003f32]">{{ $summary['greenhouse'] }} <span class="text-xs font-normal text-[#718178]">Jenis Tanaman</span></h3>
                    </div>
                </div>
            </div>

            {{-- Kartu Lokasi: Outdoor --}}
            <div class="flex items-center justify-between rounded-2xl border border-[#dbe9dd] bg-white p-5 shadow-[0_5px_18px_rgba(0,63,50,.05)] transition-all hover:border-[#00634e]">
                <div class="flex items-center gap-4">
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-amber-50 text-amber-700">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs font-bold uppercase tracking-wider text-[#718178]">Outdoor / Koleksi</p>
                        <h3 class="mt-1 text-xl font-extrabold text-[#003f32]">{{ $summary['outdoor'] }} <span class="text-xs font-normal text-[#718178]">Jenis Tanaman</span></h3>
                    </div>
                </div>
            </div>
        </div>

        {{-- BAGIAN 2: RINGKASAN PER KATEGORI (GRID RESPONSIF) --}}
        <div class="rounded-2xl border border-[#dbe9dd] bg-white p-5 shadow-[0_5px_18px_rgba(0,63,50,.05)]">
            <h2 class="text-xs font-bold uppercase tracking-wider text-[#718178]">Kategori Tanaman</h2>

            <div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4">
                @forelse($categorySummary as $item)
                    <div class="group relative flex flex-col justify-between rounded-xl border border-[#dbe9dd] bg-white p-4 transition-all duration-200 hover:border-[#00634e] hover:shadow-md">
                        <div>
                            <h3 class="mt-3 font-bold text-[#003f32] truncate text-sm" title="{{ $item->category?->name ?? 'Tanpa kategori' }}">
                                {{ $item->category?->name ?? 'Tanpa kategori' }}
                            </h3>
                        </div>
                        <div class="mt-4 flex items-center justify-between border-t border-gray-100 pt-3 text-xs text-[#718178]">
                            <span>{{ $item->plant_count }} jenis</span>
                            <span class="font-semibold text-[#b87900]">{{ number_format($item->population_total, 0, ',', '.') }} populasi</span>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full py-4 text-center text-xs text-[#718178]">Belum ada data kategori.</div>
                @endforelse
            </div>
        </div>

    </div>

    {{-- SEARCH BAR, FILTER, & TOMBOL TAMBAH--}}
    <div class="mt-7 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        {{-- Search Bar & Filter Dropdown --}}
        <form method="GET" class="flex w-full flex-col gap-2 sm:flex-1 sm:flex-row">
            <div class="relative flex min-w-0 flex-1 items-center">
                <span class="pointer-events-none absolute left-3.5 flex items-center text-[#718178]">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </span>
                <input type="search" name="search" value="{{ request('search') }}" placeholder="Cari tanaman, kategori, atau lokasi..." class="h-10 w-full rounded-lg border-0 bg-white pl-10 pr-4 text-sm shadow-sm ring-1 ring-[#dbe9dd] focus:ring-2 focus:ring-[#00634e]">
            </div>

            @php
                $selectedCategoryIds = array_map('strval', (array) request('category', []));
                $selectedCategoryNames = $categories->whereIn('id', $selectedCategoryIds)->pluck('name')->all();
                $categoryButtonLabel = $selectedCategoryNames ? implode(', ', $selectedCategoryNames) : 'Semua kategori';
                $statusButtonLabel = match (request('status')) {
                    'active' => 'Aktif',
                    'inactive' => 'Nonaktif',
                    default => 'Semua status',
                };
            @endphp

            <div class="relative shrink-0 sm:w-56">
                <button type="button" onclick="toggleDropdown('dropdown-filter-category')" class="flex h-10 w-full items-center justify-between rounded-lg bg-white px-4 text-sm font-bold text-[#557067] shadow-sm ring-1 ring-[#dbe9dd] transition hover:text-[#003f32] focus:outline-none focus:ring-2 focus:ring-[#00634e]">
                    <span class="truncate text-left" title="{{ $categoryButtonLabel }}">{{ $categoryButtonLabel }}</span>
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>
                <div id="dropdown-filter-category" class="sort-dropdown hidden absolute right-0 top-12 z-50 w-full min-w-[290px] overflow-hidden rounded-xl border border-[#dbe9dd] bg-white shadow-xl">
                    <div class="max-h-64 space-y-1 overflow-y-auto p-4">
                        <label class="flex cursor-pointer items-center gap-3 rounded-lg px-2 py-2 text-sm font-bold text-[#557067] hover:bg-[#f6fbf6]">
                            <input type="checkbox" id="all-categories" @checked(!$selectedCategoryIds) class="h-4 w-4 rounded accent-[#00634e]">
                            <span>Semua kategori</span>
                        </label>
                        @foreach ($categories as $category)
                            <label class="flex cursor-pointer items-center gap-3 rounded-lg px-2 py-2 text-sm text-[#557067] hover:bg-[#f6fbf6]">
                                <input type="checkbox" name="category[]" value="{{ $category->id }}" @checked(in_array((string) $category->id, $selectedCategoryIds, true)) class="category-option h-4 w-4 rounded accent-[#00634e]">
                                <span>{{ $category->name }}</span>
                            </label>
                        @endforeach
                    </div>
                    <div class="flex items-center justify-end gap-3 border-t border-[#edf3ed] px-4 py-3">
                        <a href="{{ route('admin.plants.index', request()->except('category', 'page')) }}" class="inline-flex h-10 items-center justify-center rounded-full px-4 text-sm font-bold text-[#557067] hover:bg-[#f1f5f1]">Reset</a>
                        <button type="submit" class="inline-flex h-10 items-center justify-center rounded-full bg-[#00634e] px-5 text-sm font-bold text-white shadow-sm transition hover:bg-[#003f32]">Tampilkan hasil</button>
                    </div>
                </div>
            </div>

            <div class="relative shrink-0 sm:w-48">
                <button type="button" onclick="toggleDropdown('dropdown-filter-status')" class="flex h-10 w-full items-center justify-between rounded-lg bg-white px-4 text-sm font-bold text-[#557067] shadow-sm ring-1 ring-[#dbe9dd] transition hover:text-[#003f32] focus:outline-none focus:ring-2 focus:ring-[#00634e]">
                    <span>{{ $statusButtonLabel }}</span>
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>
                <div id="dropdown-filter-status" class="sort-dropdown hidden absolute right-0 top-12 z-50 w-full min-w-[290px] overflow-hidden rounded-xl border border-[#dbe9dd] bg-white shadow-xl">
                    <div class="p-4">
                        <div class="space-y-1">
                            <label class="flex cursor-pointer items-center gap-3 rounded-lg px-2 py-2 text-sm text-[#557067] hover:bg-[#f6fbf6]">
                                <input type="radio" name="status" value="" @checked(!request('status')) class="h-4 w-4 accent-[#00634e]">
                                <span>Semua status</span>
                            </label>
                            <label class="flex cursor-pointer items-center gap-3 rounded-lg px-2 py-2 text-sm text-[#557067] hover:bg-[#f6fbf6]">
                                <input type="radio" name="status" value="active" @checked(request('status') === 'active') class="h-4 w-4 accent-[#00634e]">
                                <span>Aktif</span>
                            </label>
                            <label class="flex cursor-pointer items-center gap-3 rounded-lg px-2 py-2 text-sm text-[#557067] hover:bg-[#f6fbf6]">
                                <input type="radio" name="status" value="inactive" @checked(request('status') === 'inactive') class="h-4 w-4 accent-[#00634e]">
                                <span>Nonaktif</span>
                            </label>
                        </div>
                    </div>
                    <div class="flex items-center justify-end gap-3 border-t border-[#edf3ed] px-4 py-3">
                        <a href="{{ route('admin.plants.index', request()->except('status', 'page')) }}" class="inline-flex h-10 items-center justify-center rounded-full px-4 text-sm font-bold text-[#557067] hover:bg-[#f1f5f1]">Reset</a>
                        <button type="submit" class="inline-flex h-10 items-center justify-center rounded-full bg-[#00634e] px-5 text-sm font-bold text-white shadow-sm transition hover:bg-[#003f32]">Tampilkan hasil</button>
                    </div>
                </div>
            </div>
        </form>

        {{-- Tombol Tambah Tanaman--}}
        <a href="{{ route('admin.plants.create') }}" class="inline-flex h-10 shrink-0 items-center justify-center rounded-lg bg-[#003f32] px-5 text-xs font-bold text-white shadow-sm hover:bg-[#00634e] transition">
            Tambah Tanaman
        </a>
    </div>

    {{-- TABEL DATA TANAMAN & DROPDOWN SORT --}}
    <div class="mt-5 overflow-x-visible rounded-xl border border-[#dbe9dd] bg-white shadow-[0_5px_18px_rgba(0,63,50,.05)]" id="plant-table-container">
        @php
            $sortUrl = fn ($column, $direction) => route('admin.plants.index', array_merge(request()->query(), ['sort' => $column, 'direction' => $direction, 'page' => null]));
            $activeSort = request('sort', 'name');
            $activeDir = request('direction', 'asc');
        @endphp

        <table class="w-full min-w-[840px] text-left text-xs">
            <thead class="border-b border-[#dbe9dd] bg-[#f6fbf6] text-[10px] uppercase tracking-wide text-[#718178]">
                <tr>
                    {{-- 1. Kolom Tanaman --}}
                    <th class="relative px-5 py-4">
                        <button onclick="toggleDropdown('dropdown-name')" class="group inline-flex items-center gap-1.5 font-bold uppercase tracking-wide text-[#718178] hover:text-[#003f32] focus:outline-none">
                            <span>Tanaman</span>
                            <span class="flex h-5 w-5 items-center justify-center rounded-full {{ $activeSort === 'name' ? 'bg-[#e8f3df] text-[#003f32]' : 'text-[#b9c8bc] group-hover:bg-[#e3eee4] group-hover:text-[#003f32]' }}">
                                <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </span>
                        </button>
                        <div id="dropdown-name" class="sort-dropdown hidden absolute left-5 top-14 z-50 w-48 rounded-xl border border-[#dbe9dd] bg-white p-1.5 shadow-xl">
                            <a href="{{ $sortUrl('name', 'asc') }}" class="flex items-center gap-2 rounded-lg px-3 py-2 text-xs font-medium text-[#557067] hover:bg-[#f6fbf6] hover:text-[#003f32] {{ $activeSort === 'name' && $activeDir === 'asc' ? 'bg-[#f6fbf6] font-bold text-[#003f32]' : '' }}"><span>A ke Z</span></a>
                            <a href="{{ $sortUrl('name', 'desc') }}" class="flex items-center gap-2 rounded-lg px-3 py-2 text-xs font-medium text-[#557067] hover:bg-[#f6fbf6] hover:text-[#003f32] {{ $activeSort === 'name' && $activeDir === 'desc' ? 'bg-[#f6fbf6] font-bold text-[#003f32]' : '' }}"><span>Z ke A</span></a>
                        </div>
                    </th>

                    {{-- 2. Kolom Kategori --}}
                    <th class="relative px-5 py-4">
                        <button onclick="toggleDropdown('dropdown-category')" class="group inline-flex items-center gap-1.5 font-bold uppercase tracking-wide text-[#718178] hover:text-[#003f32] focus:outline-none">
                            <span>Kategori</span>
                            <span class="flex h-5 w-5 items-center justify-center rounded-full {{ $activeSort === 'category' ? 'bg-[#e8f3df] text-[#003f32]' : 'text-[#b9c8bc] group-hover:bg-[#e3eee4] group-hover:text-[#003f32]' }}">
                                <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </span>
                        </button>
                        <div id="dropdown-category" class="sort-dropdown hidden absolute left-5 top-14 z-50 w-48 rounded-xl border border-[#dbe9dd] bg-white p-1.5 shadow-xl">
                            <a href="{{ $sortUrl('category', 'asc') }}" class="flex items-center gap-2 rounded-lg px-3 py-2 text-xs font-medium text-[#557067] hover:bg-[#f6fbf6] hover:text-[#003f32] {{ $activeSort === 'category' && $activeDir === 'asc' ? 'bg-[#f6fbf6] font-bold text-[#003f32]' : '' }}"><span>A ke Z</span></a>
                            <a href="{{ $sortUrl('category', 'desc') }}" class="flex items-center gap-2 rounded-lg px-3 py-2 text-xs font-medium text-[#557067] hover:bg-[#f6fbf6] hover:text-[#003f32] {{ $activeSort === 'category' && $activeDir === 'desc' ? 'bg-[#f6fbf6] font-bold text-[#003f32]' : '' }}"><span>Z ke A</span></a>
                        </div>
                    </th>

                    {{-- 3. Kolom Lokasi --}}
                    <th class="relative px-5 py-4">
                        <button onclick="toggleDropdown('dropdown-location')" class="group inline-flex items-center gap-1.5 font-bold uppercase tracking-wide text-[#718178] hover:text-[#003f32] focus:outline-none">
                            <span>Lokasi</span>
                            <span class="flex h-5 w-5 items-center justify-center rounded-full {{ $activeSort === 'location' ? 'bg-[#e8f3df] text-[#003f32]' : 'text-[#b9c8bc] group-hover:bg-[#e3eee4] group-hover:text-[#003f32]' }}">
                                <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </span>
                        </button>
                        <div id="dropdown-location" class="sort-dropdown hidden absolute left-5 top-14 z-50 w-52 rounded-xl border border-[#dbe9dd] bg-white p-1.5 shadow-xl">
                            <a href="{{ $sortUrl('location', 'asc') }}" class="flex items-center gap-2 rounded-lg px-3 py-2 text-xs font-medium text-[#557067] hover:bg-[#f6fbf6] hover:text-[#003f32] {{ $activeSort === 'location' && $activeDir === 'asc' ? 'bg-[#f6fbf6] font-bold text-[#003f32]' : '' }}"><span>Greenhouse ke Outdoor</span></a>
                            <a href="{{ $sortUrl('location', 'desc') }}" class="flex items-center gap-2 rounded-lg px-3 py-2 text-xs font-medium text-[#557067] hover:bg-[#f6fbf6] hover:text-[#003f32] {{ $activeSort === 'location' && $activeDir === 'desc' ? 'bg-[#f6fbf6] font-bold text-[#003f32]' : '' }}"><span>Outdoor ke Greenhouse</span></a>
                        </div>
                    </th>

                    {{-- 4. Kolom Populasi --}}
                    <th class="relative px-5 py-4 text-right">
                        <div class="flex items-center justify-end">
                            <button onclick="toggleDropdown('dropdown-population')" class="group inline-flex items-center gap-1.5 font-bold uppercase tracking-wide text-[#718178] hover:text-[#003f32] focus:outline-none">
                                <span>Jumlah populasi</span>
                                <span class="flex h-5 w-5 items-center justify-center rounded-full {{ $activeSort === 'population' ? 'bg-[#e8f3df] text-[#003f32]' : 'text-[#b9c8bc] group-hover:bg-[#e3eee4] group-hover:text-[#003f32]' }}">
                                    <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                </span>
                            </button>
                        </div>
                        <div id="dropdown-population" class="sort-dropdown hidden absolute right-5 top-14 z-50 w-52 rounded-xl border border-[#dbe9dd] bg-white p-1.5 text-left shadow-xl">
                            <a href="{{ $sortUrl('population', 'asc') }}" class="flex items-center gap-2 rounded-lg px-3 py-2 text-xs font-medium text-[#557067] hover:bg-[#f6fbf6] hover:text-[#003f32] {{ $activeSort === 'population' && $activeDir === 'asc' ? 'bg-[#f6fbf6] font-bold text-[#003f32]' : '' }}"><span>Terkecil ke Terbanyak</span></a>
                            <a href="{{ $sortUrl('population', 'desc') }}" class="flex items-center gap-2 rounded-lg px-3 py-2 text-xs font-medium text-[#557067] hover:bg-[#f6fbf6] hover:text-[#003f32] {{ $activeSort === 'population' && $activeDir === 'desc' ? 'bg-[#f6fbf6] font-bold text-[#003f32]' : '' }}"><span>Terbanyak ke Terkecil</span></a>
                        </div>
                    </th>

                    {{-- 5. Kolom Status --}}
                    <th class="relative px-5 py-4">
                        <button onclick="toggleDropdown('dropdown-status')" class="group inline-flex items-center gap-1.5 font-bold uppercase tracking-wide text-[#718178] hover:text-[#003f32] focus:outline-none">
                            <span>Status</span>
                            <span class="flex h-5 w-5 items-center justify-center rounded-full {{ $activeSort === 'status' ? 'bg-[#e8f3df] text-[#003f32]' : 'text-[#b9c8bc] group-hover:bg-[#e3eee4] group-hover:text-[#003f32]' }}">
                                <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </span>
                        </button>
                        <div id="dropdown-status" class="sort-dropdown hidden absolute left-5 top-14 z-50 w-48 rounded-xl border border-[#dbe9dd] bg-white p-1.5 shadow-xl">
                            <a href="{{ $sortUrl('status', 'asc') }}" class="flex items-center gap-2 rounded-lg px-3 py-2 text-xs font-medium text-[#557067] hover:bg-[#f6fbf6] hover:text-[#003f32] {{ $activeSort === 'status' && $activeDir === 'asc' ? 'bg-[#f6fbf6] font-bold text-[#003f32]' : '' }}"><span>Aktif dulu</span></a>
                            <a href="{{ $sortUrl('status', 'desc') }}" class="flex items-center gap-2 rounded-lg px-3 py-2 text-xs font-medium text-[#557067] hover:bg-[#f6fbf6] hover:text-[#003f32] {{ $activeSort === 'status' && $activeDir === 'desc' ? 'bg-[#f6fbf6] font-bold text-[#003f32]' : '' }}"><span>Nonaktif dulu</span></a>
                        </div>
                    </th>

                    <th class="px-5 py-4 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-[#edf3ed]">
            @forelse ($plants as $plant)
                <tr class="hover:bg-[#fbfefb]">
                    <td class="px-5 py-4">
                        <div class="flex items-center gap-3">
                            @if($plant->image)
                                <img src="{{ asset('storage/plants/'.$plant->image) }}" class="h-10 w-10 rounded-md object-cover">
                            @else
                                <div class="flex h-10 w-10 items-center justify-center rounded-md bg-[#e8f3df] text-lg">🌿</div>
                            @endif
                            <div>
                                <p class="font-bold text-[#164f43]">{{ $plant->name }}</p>
                                <p class="mt-0.5 text-[10px] italic text-[#b87900]">{{ $plant->latin_name }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-5 py-4 text-[#557067]">{{ $plant->category?->name }}</td>
                    <td class="px-5 py-4">
                        @if($plant->location_type === 'greenhouse')
                            <span class="rounded bg-green-100 px-2 py-1 text-[10px] font-bold text-green-800">Greenhouse</span>
                        @else
                            <span class="rounded bg-amber-100 px-2 py-1 text-[10px] font-bold text-amber-800">Outdoor</span>
                        @endif
                    </td>
                    <td class="px-5 py-4 text-right text-[#557067]">{{ $plant->population !== null ? number_format($plant->population, 0, ',', '.') : '-' }}</td>
                    <td class="px-5 py-4">
                        <span class="rounded-full px-2.5 py-1 text-[10px] font-bold {{ $plant->is_active ? 'bg-[#e8f3df] text-[#28604d]' : 'bg-[#f1eeee] text-[#866464]' }}">
                            {{ $plant->is_active ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </td>
                    <td class="px-5 py-4">
                        <div class="flex justify-end gap-3 font-bold">
                            <a href="{{ route('admin.plants.edit', $plant) }}" class="text-[#00634e] hover:text-[#b87900]">Edit</a>
                            <form method="POST" action="{{ route('admin.plants.destroy', $plant) }}" onsubmit="return confirm('Hapus tanaman ini?')">
                                @csrf 
                                @method('DELETE')
                                <button class="text-[#b04d3e] hover:text-[#7d2318]">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="px-5 py-14 text-center text-sm text-[#718178]">Belum ada data tanaman.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="mt-6">{{ $plants->links() }}</div>

    <script>
        function toggleDropdown(dropdownId) {
            event.stopPropagation();
            document.querySelectorAll('.sort-dropdown').forEach(el => {
                if (el.id !== dropdownId) el.classList.add('hidden');
            });
            const target = document.getElementById(dropdownId);
            if (target) {
                target.classList.toggle('hidden');
            }
        }

        window.addEventListener('click', function () {
            document.querySelectorAll('.sort-dropdown').forEach(el => {
                el.classList.add('hidden');
            });
        });

        document.addEventListener("DOMContentLoaded", function () {
            const allCategories = document.getElementById('all-categories');
            const categoryOptions = document.querySelectorAll('.category-option');

            if (allCategories) {
                allCategories.addEventListener('change', function () {
                    if (this.checked) {
                        categoryOptions.forEach(option => option.checked = false);
                    }
                });
            }

            categoryOptions.forEach(option => {
                option.addEventListener('change', function () {
                    if (this.checked && allCategories) {
                        allCategories.checked = false;
                    }
                });
            });

            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.has('sort')) {
                const tableContainer = document.getElementById('plant-table-container');
                if (tableContainer) {
                    tableContainer.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                }
            }
        });
    </script>
@endsection