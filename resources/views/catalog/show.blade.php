@extends('layouts.app')

@section('title', $plant->name . ' | Katalog TSTH2')

@section('content')
<div class="mx-auto max-w-6xl px-4 py-8">
    
    <!-- Navigasi Breadcrumb -->
    <div class="mb-6 flex flex-wrap items-center justify-between gap-4 text-xs font-medium text-gray-500">
        <div class="flex items-center gap-2">
            <a href="{{ route('home') }}" class="hover:text-[#003f32] transition">← Kembali ke Katalog Greenhouse</a>
            <span>&nbsp;&nbsp; Zona {{ $plant->category?->name ?? 'Kategori' }} &nbsp;&nbsp;</span>
            <span class="font-bold text-gray-900">{{ $plant->name }} ({{ $plant->latin_name }})</span>
        </div>
        <button class="flex items-center gap-1.5 hover:text-gray-900 transition">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z" />
            </svg>
            Bagikan
        </button>
    </div>

    <!-- Main Card -->
    <article class="overflow-hidden rounded-xl bg-white shadow-sm border border-gray-100 p-8 sm:p-12">
        
        <!-- Bagian Atas: Gambar & Info Utama -->
        <div class="mb-12 flex flex-col gap-10 md:flex-row">
            
            <div class="w-full shrink-0 md:w-1/3 lg:w-[320px]">
                @if ($plant->image)
                    <img src="{{ asset('storage/plants/' . $plant->image) }}" alt="{{ $plant->name }}" class="aspect-square h-auto w-full rounded-lg border border-gray-50 object-cover shadow-sm">
                @else
                    <div class="flex h-80 flex-col items-center justify-center rounded-lg border border-gray-100 bg-gray-50 text-gray-400">
                        <span class="mb-2 text-6xl">🌿</span>
                    </div>
                @endif
            </div>
            
            <div class="flex-1">
                <span class="mb-4 inline-block rounded-sm bg-[#f0fdf4] px-3 py-1 text-[10px] font-bold uppercase tracking-wider text-[#16a34a]">
                    {{ $plant->category?->name ?? 'Tanaman Herbal' }}
                </span>
                
                <h1 class="font-['Manrope'] text-4xl font-extrabold tracking-tight text-[#003f32]">{{ $plant->name }}</h1>
                <p class="mb-8 mt-1 text-lg italic text-gray-500">{{ $plant->latin_name }}</p>
                
                <!-- Tabel Taksonomi (Grid 3 Kolom) -->
                <div class="grid grid-cols-2 gap-x-4 gap-y-6 border-y border-gray-100 py-5 sm:grid-cols-3">
                    <div>
                        <p class="text-[9px] font-bold uppercase tracking-wider text-gray-400">Kingdom</p>
                        <p class="mt-1 text-sm font-semibold text-gray-800">Plantae</p>
                    </div>
                    <div>
                        <p class="text-[9px] font-bold uppercase tracking-wider text-gray-400">Ordo</p>
                        <p class="mt-1 text-sm font-semibold text-gray-800">{{ $plant->ordo ?: '-' }}</p>
                    </div>
                    <div>
                        <p class="text-[9px] font-bold uppercase tracking-wider text-gray-400">Famili</p>
                        <p class="mt-1 text-sm font-semibold text-gray-800">{{ $plant->family }}</p>
                    </div>
                    <div>
                        <p class="text-[9px] font-bold uppercase tracking-wider text-gray-400">Genus</p>
                        <p class="mt-1 text-sm font-semibold text-gray-800">{{ $plant->genus ?: '-' }}</p>
                    </div>
                    <div>
                        <p class="text-[9px] font-bold uppercase tracking-wider text-gray-400">Botanis</p>
                        <p class="mt-1 text-sm font-semibold text-gray-800">{{ $plant->botanist ?: '-' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bagian Bawah: Deskripsi, Manfaat, Asal Daerah -->
        <div class="space-y-10">
            
            <section>
                <h2 class="mb-4 flex items-center gap-2.5 font-['Manrope'] text-lg font-bold text-gray-900">
                    <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Deskripsi
                </h2>
                <div class="text-[13px] leading-relaxed text-gray-600">
                    {!! nl2br(e($plant->description_full ?: $plant->description_short)) !!}
                </div>
            </section>

            <section>
                <h2 class="mb-4 flex items-center gap-2.5 font-['Manrope'] text-lg font-bold text-gray-900">
                    <svg class="h-5 w-5 text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                    Manfaat
                </h2>
                <ul class="space-y-3 text-[13px] text-gray-600">
                    @if ($plant->benefits)
                        {{-- Memecah teks berdasarkan enter/baris baru --}}
                        @foreach (explode("\n", str_replace("\r", "", $plant->benefits)) as $manfaat)
                            {{-- Memastikan baris yang kosong tidak ikut ditampilkan --}}
                            @if (trim($manfaat) !== '')
                                <li class="flex items-start gap-2.5">
                                    <svg class="mt-0.5 h-4 w-4 shrink-0 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                    </svg>
                                    <span>{{ trim($manfaat) }}</span>
                                </li>
                            @endif
                        @endforeach
                    @else
                        <li class="flex items-start gap-2.5">
                            <svg class="mt-0.5 h-4 w-4 shrink-0 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                            </svg>
                            <span>Data manfaat belum ditambahkan.</span>
                        </li>
                    @endif
                </ul>
            </section>

            <section>
                <h2 class="mb-4 flex items-center gap-2.5 font-['Manrope'] text-lg font-bold text-gray-900">
                    <svg class="h-5 w-5 text-gray-700" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Asal Daerah & Sebaran Botani
                </h2>
                <div class="rounded-lg border border-green-50 bg-[#f0f8f4] p-5">
                    <p class="font-medium leading-relaxed text-[#003f32] text-[13px]">
                        Kawasan Asal: {{ $plant->origin ?? $plant->origin_distribution ?? 'Data belum tersedia.' }}
                    </p>
                </div>
            </section>
            
        </div>
    </article>
</div>
@endsection