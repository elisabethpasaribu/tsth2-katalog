@extends('layouts.app')

@section('title', $plant->name . ' | Katalog TSTH2')

@section('content')
<div class="mx-auto max-w-6xl px-4 py-8">
    @php
        $catalogRoute = $plant->location_type === 'collection' ? 'collection' : 'home';
        $catalogLabel = $plant->location_type === 'collection' ? 'Koleksi Outdoor' : 'Katalog Greenhouse';
    @endphp
    
    <!-- Breadcrumb -->
    <div class="mb-6 flex flex-wrap items-center justify-between gap-4">
        <nav class="min-w-0 overflow-x-auto text-xs font-medium text-gray-500" aria-label="Breadcrumb">
            <ol class="flex min-w-max items-center gap-2 whitespace-nowrap">
                <li><a href="{{ route($catalogRoute) }}" class="text-[#003f32] transition hover:underline">{{ $catalogLabel }}</a></li>
                <li aria-hidden="true" class="text-gray-300">/</li>
                <li>
                    @if ($plant->category)
                        <a href="{{ route($catalogRoute, ['category' => $plant->category->slug]) }}" class="text-[#003f32] transition hover:underline">{{ $plant->category->name }}</a>
                    @else
                        Tanaman Herbal
                    @endif
                </li>
                <li aria-hidden="true" class="text-gray-300">/</li>
                <li aria-current="page" class="max-w-[280px] truncate font-bold text-gray-900">{{ $plant->name }}</li>
            </ol>
        </nav>
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
                
                <h1 class="font-['Montserrat'] text-4xl font-extrabold tracking-tight text-[#000000]">{{ $plant->name }}</h1>
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
                        <p class="text-[9px] font-bold uppercase tracking-wider text-gray-400">Botanist</p>
                        <p class="mt-1 text-sm font-semibold text-gray-800">{{ $plant->botanist ?: '-' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bagian Bawah: Deskripsi, Manfaat, Asal Daerah -->
        <div class="space-y-10">
            
            <section>
                <h2 class="mb-4 flex items-center gap-2.5 font-['Montserrat'] text-lg font-bold text-gray-900">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" />
                    <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                   </svg>
                    Deskripsi
                </h2>
                <div class="text-[13px] leading-relaxed text-black">
                    {!! nl2br(e($plant->description_full ?: $plant->description_short)) !!}
                </div>
            </section>

            <section>
                <h2 class="mb-4 flex items-center gap-2.5 font-['Montserrat'] text-lg font-bold text-gray-900">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                    Manfaat
                </h2>
                <ul class="space-y-3 text-[13px] text-black">
                    @if ($plant->benefits)
                        {{-- Memecah teks berdasarkan enter/baris baru --}}
                        @foreach (explode("\n", str_replace("\r", "", $plant->benefits)) as $manfaat)
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
                <h2 class="mb-4 flex items-center gap-2.5 font-['Montserrat'] text-lg font-bold text-gray-900">
                    Asal Daerah & Sebaran Botani
                </h2>
                <div class="rounded-xl border border-green-100 bg-[#f0f8f4] p-5 shadow-sm">
                    <div class="text-[13px] leading-relaxed text-gray-700">
                        <p class="font-bold text-[#003f32] mb-1">
                            Kawasan Asal: {{ $plant->origin ?? 'Data belum tersedia' }}
                        </p>
                        <p>
                            {{ $plant->origin_distribution ?? 'Sebaran botani belum ditambahkan.' }}
                        </p>
                    </div>
                </div>
            </section>
            
        </div>
    </article>
</div>
@endsection