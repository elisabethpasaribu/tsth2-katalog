@extends('layouts.app')

@section('title', $plant->name . ' - Detail Tanaman')

@section('content')
    @php
        $catalogRoute = $plant->location_type === 'collection' ? 'collection' : 'home';
        $catalogLabel = $plant->location_type === 'collection' ? 'Koleksi Outdoor' : 'Katalog Greenhouse';
    @endphp

    <!-- Breadcrumb -->
    <nav class="mb-3 overflow-x-auto text-[10px] text-[#49645b]" aria-label="Breadcrumb">
        <ol class="flex min-w-max items-center gap-1.5 whitespace-nowrap">
            <li><a href="{{ route($catalogRoute) }}" class="font-semibold text-[#003f32] hover:underline">{{ $catalogLabel }}</a></li>
            <li aria-hidden="true" class="text-[#9aaa9f]">/</li>
            <li>
                @if ($plant->category)
                    <a href="{{ route($catalogRoute, ['category' => $plant->category->slug]) }}" class="font-semibold text-[#003f32] hover:underline">{{ $plant->category->name }}</a>
                @else
                    Tanaman Herbal
                @endif
            </li>
            <li aria-hidden="true" class="text-[#9aaa9f]">/</li>
            <li aria-current="page" class="max-w-[180px] truncate font-bold text-[#111827]">{{ $plant->name }}</li>
        </ol>
    </nav>

    <!-- Header Navigasi Atas -->
    <div class="mb-4 flex items-center justify-between">
        <a href="{{ url()->previous() }}" class="flex items-center gap-1.5 text-xs font-bold text-[#003f32]">
            <span class="text-base">←</span> Kembali
        </a>
        <span class="text-[10px] font-bold tracking-wider text-[#49645b] uppercase">Technical Data Sheet</span>
        <button onclick="navigator.share ? navigator.share({title: '{{ $plant->name }}', url: window.location.href}) : alert('Link disalin!')" class="text-[#003f32]">
            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/></svg>
        </button>
    </div>

    <!-- Foto Tanaman Utama -->
    <div class="mb-4 overflow-hidden rounded-2xl bg-white shadow-sm border border-[#e0e9e1] p-3">
        <div class="relative h-[200px] w-full overflow-hidden rounded-xl bg-[#f0f5f1]">
            @if ($plant->image)
                <img src="{{ asset('storage/plants/' . $plant->image) }}" alt="{{ $plant->name }}" class="h-full w-full object-cover">
            @else
                <div class="flex h-full items-center justify-center text-4xl"></div>
            @endif
        </div>
        <div class="pt-3.5 px-1 pb-1">
            <div class="flex items-center justify-between gap-2">
                <h1 class="font-['Manrope'] text-lg font-extrabold text-[#111827]">{{ $plant->name }}</h1>
                @if ($plant->category)
                    <span class="rounded-full bg-[#fef0c7] px-3 py-0.5 text-[10px] font-bold text-[#b54708]">{{ $plant->category->name }}</span>
                @endif
            </div>
            <p class="mt-0.5 text-xs italic text-[#d97706]">{{ $plant->latin_name }}</p>
        </div>
    </div>

    <!-- Tab / Filter Navigasi Interaktif -->
    <nav class="sticky top-2 z-10 mb-6 flex overflow-x-auto gap-2 bg-[#f8faf8] py-2 scrollbar-none" aria-label="Filter bagian">
        <button type="button" onclick="filterSection('semua')" class="tab-btn rounded-full bg-[#003f32] px-4 py-1.5 text-[11px] font-bold text-white whitespace-nowrap transition" data-target="semua">Semua</button>
        <button type="button" onclick="filterSection('taksonomi')" class="tab-btn rounded-full bg-white border border-[#e0e9e1] px-4 py-1.5 text-[11px] font-bold text-[#38584d] whitespace-nowrap transition" data-target="taksonomi">Taksonomi</button>
        <button type="button" onclick="filterSection('deskripsi')" class="tab-btn rounded-full bg-white border border-[#e0e9e1] px-4 py-1.5 text-[11px] font-bold text-[#38584d] whitespace-nowrap transition" data-target="deskripsi">Deskripsi</button>
        <button type="button" onclick="filterSection('manfaat')" class="tab-btn rounded-full bg-white border border-[#e0e9e1] px-4 py-1.5 text-[11px] font-bold text-[#38584d] whitespace-nowrap transition" data-target="manfaat">Manfaat</button>
        <button type="button" onclick="filterSection('asal-daerah')" class="tab-btn rounded-full bg-white border border-[#e0e9e1] px-4 py-1.5 text-[11px] font-bold text-[#38584d] whitespace-nowrap transition" data-target="asal-daerah">Asal Daerah</button>
        
    </nav>

    <div class="space-y-6">
        <!-- 1. TAKSONOMI BOTANI (Kingdom, Ordo, Famili, Genus, Botanist) -->
        <!-- 1. TAKSONOMI BOTANI (Disinkronkan dengan data Desktop) -->
        <div class="content-section" data-section="taksonomi">
            <section class="rounded-2xl bg-white border border-[#e0e9e1] p-4 shadow-sm">
                <div class="mb-3 flex items-center gap-2.5">
                    <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-[#e8f3ea] text-[#003f32]"></div>
                    <div>
                        <h2 class="font-['Manrope'] text-sm font-extrabold text-[#111827]">TAKSONOMI BOTANI</h2>
                        <p class="text-[10px] text-[#49645b]">Klasifikasi biologis tanaman</p>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-2 text-xs">
                    <div class="rounded-xl bg-[#fafafa] border border-[#f0f0f0] p-2.5">
                        <span class="block text-[9px] font-bold tracking-wider text-[#8a9991] uppercase">Kingdom</span>
                        <span class="font-semibold text-[#111827]">{{ $plant->kingdom ?? 'Plantae' }}</span>
                    </div>
                    <div class="rounded-xl bg-[#fafafa] border border-[#f0f0f0] p-2.5">
                        <span class="block text-[9px] font-bold tracking-wider text-[#8a9991] uppercase">Ordo</span>
                        <span class="font-semibold text-[#111827]">{{ $plant->order ?? $plant->ordo ?? '-' }}</span>
                    </div>
                    <div class="rounded-xl bg-[#fafafa] border border-[#f0f0f0] p-2.5">
                        <span class="block text-[9px] font-bold tracking-wider text-[#8a9991] uppercase">Famili</span>
                        <span class="font-semibold text-[#111827]">{{ $plant->family ?? '-' }}</span>
                    </div>
                    <div class="rounded-xl bg-[#fafafa] border border-[#f0f0f0] p-2.5">
                        <span class="block text-[9px] font-bold tracking-wider text-[#8a9991] uppercase">Genus</span>
                        <span class="font-semibold text-[#111827]">{{ $plant->genus ?? '-' }}</span>
                    </div>
                    <div class="col-span-2 rounded-xl bg-[#fafafa] border border-[#f0f0f0] p-2.5">
                        <span class="block text-[9px] font-bold tracking-wider text-[#8a9991] uppercase">Botanist</span>
                        <span class="font-semibold text-[#111827]">{{ $plant->botanist ?? $plant->author ?? '-' }}</span>
                    </div>
                </div>
            </section>
        </div>

        <!-- 2. DESKRIPSI MORFOLOGI -->
        <div class="content-section" data-section="deskripsi">
            <section class="rounded-2xl bg-white border border-[#e0e9e1] p-4 shadow-sm">
                <div class="mb-3 flex items-center gap-2.5">
                    <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-[#e8f3ea] text-[#003f32]"></div>
                    <div>
                        <h2 class="font-['Manrope'] text-sm font-extrabold text-[#111827]">DESKRIPSI MORFOLOGI</h2>
                        <p class="text-[10px] text-[#49645b]">Karakteristik fisik & rasa</p>
                    </div>
                </div>

                <div class="rounded-xl bg-[#fafafa] border border-[#f0f0f0] p-3 text-xs leading-relaxed text-[#4b5563]">
                    {{ $plant->description_full ?: $plant->description_short ?? 'Belum ada deskripsi mendalam untuk tanaman ini.' }}
                </div>
            </section>
        </div>

        <!-- 3. MANFAAT FARMAKOLOGIS -->
        <div class="content-section" data-section="manfaat">
            <section class="rounded-2xl bg-white border border-[#e0e9e1] p-4 shadow-sm">
                <div class="mb-3 flex items-center gap-2.5">
                    <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-[#e8f3ea] text-[#003f32]"></div>
                    <div>
                        <h2 class="font-['Manrope'] text-sm font-extrabold text-[#111827]">MANFAAT FARMAKOLOGIS</h2>
                        <p class="text-[10px] text-[#49645b]">Khasiat terapeutik teruji</p>
                    </div>
                </div>

                <div class="space-y-2">
                    @if($plant->benefits)
                        @php
                            $benefitItems = preg_split('/\r\n|\r|\n|;/', $plant->benefits);
                        @endphp
                        @foreach($benefitItems as $item)
                            @if(trim($item) !== '')
                                <div class="flex items-start gap-2.5 rounded-xl bg-[#fafafa] border border-[#f0f0f0] p-3">
                                    <span class="flex h-5 w-5 flex-shrink-0 items-center justify-center rounded-full bg-[#e8f3ea] text-[#003f32] text-xs font-bold">✓</span>
                                    <p class="text-xs leading-relaxed text-[#4b5563] pt-0.5">{{ trim(ltrim(trim($item), '-•*')) }}</p>
                                </div>
                            @endif
                        @endforeach
                    @else
                        <div class="rounded-xl bg-[#fafafa] border border-[#f0f0f0] p-3 text-xs text-[#4b5563]">
                            Informasi manfaat belum ditambahkan.
                        </div>
                    @endif
                </div>
            </section>
        </div>

        <!-- 4. ASAL DAERAH & HABITAT -->
        <div class="content-section" data-section="asal-daerah">
            <section class="rounded-2xl bg-white border border-[#e0e9e1] p-4 shadow-sm">
                <div class="mb-3 flex items-center gap-2.5">
                    <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-[#e8f3ea] text-[#003f32]"></div>
                    <div>
                        <h2 class="font-['Manrope'] text-sm font-extrabold text-[#111827]">ASAL DAERAH & HABITAT</h2>
                        <p class="text-[10px] text-[#49645b]">Penyebaran agroekosistem</p>
                    </div>
                </div>

                <div class="rounded-xl bg-[#fafafa] border border-[#f0f0f0] p-3 text-xs leading-relaxed text-[#4b5563]">
                    {{ $plant->origin_distribution ?? 'Data asal daerah dan sebaran botani belum ditambahkan.' }}
                </div>
            </section>
        </div>
    </div>

    <!-- Script JavaScript untuk Filter / Tab -->
    <script>
        function filterSection(target) {
            const buttons = document.querySelectorAll('.tab-btn');
            const sections = document.querySelectorAll('.content-section');

            buttons.forEach(btn => {
                if (btn.getAttribute('data-target') === target) {
                    btn.classList.remove('bg-white', 'border', 'border-[#e0e9e1]', 'text-[#38584d]');
                    btn.classList.add('bg-[#003f32]', 'text-white');
                } else {
                    btn.classList.add('bg-white', 'border', 'border-[#e0e9e1]', 'text-[#38584d]');
                    btn.classList.remove('bg-[#003f32]', 'text-white');
                }
            });

            sections.forEach(sec => {
                if (target === 'semua' || sec.getAttribute('data-section') === target) {
                    sec.style.display = 'block';
                } else {
                    sec.style.display = 'none';
                }
            });
        }
    </script>
@endsection