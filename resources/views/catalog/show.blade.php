@extends('layouts.app')

@section('title', $plant->name . ' | Katalog TSTH2')

@section('content')
<div class="mx-auto max-w-8xl px-4 py-8">
    @php
        $catalogRoute = $plant->location_type === 'collection' || $plant->location_type === 'outdoor' ? 'collection' : 'home';
        $catalogLabel = $plant->location_type === 'collection' || $plant->location_type === 'outdoor' ? 'Koleksi Outdoor' : 'Katalog Greenhouse';
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
    <article class="overflow-hidden rounded-xl border border-gray-100 bg-white p-8 shadow-sm sm:p-10">
        
        <!-- Bagian Atas: Gambar & Info Utama -->
        <!-- Mengubah gap-10 mb-12 menjadi gap-8 mb-8 agar tidak terlalu jauh -->
        <div class="mb-8 grid gap-8 md:grid-cols-[380px_minmax(0,1fr)]">
            
            @php
                $plantImages = collect([$plant->image])->filter()->merge($plant->image_gallery ?? [])->values();
            @endphp
            <div class="plant-carousel order-1 w-full shrink-0 md:col-start-1 md:row-start-1 md:w-[380px]" data-carousel>
                <div class="relative">
                    <div class="overflow-hidden rounded-2xl border border-gray-100 bg-gray-50 shadow-sm" data-carousel-track>
                    @forelse ($plantImages as $imageIndex => $image)
                        <div class="{{ $imageIndex === 0 ? '' : 'hidden' }} aspect-square w-full" data-carousel-slide="{{ $imageIndex }}">
                            <img src="{{ asset('storage/plants/' . $image) }}" alt="{{ $plant->name }} - foto {{ $imageIndex + 1 }}" class="h-full w-full object-cover">
                        </div>
                    @empty
                        <div class="flex aspect-square w-full flex-col items-center justify-center text-gray-400">
                            <span class="mb-2 text-6xl">🌿</span>
                        </div>
                    @endforelse
                    </div>
                    @if ($plantImages->count() > 1)
                        <button type="button" class="absolute left-2 top-1/2 flex h-8 w-8 -translate-y-1/2 items-center justify-center rounded-full bg-white/90 text-lg font-bold text-[#003f32] shadow" data-carousel-prev aria-label="Foto sebelumnya">‹</button>
                        <button type="button" class="absolute right-2 top-1/2 flex h-8 w-8 -translate-y-1/2 items-center justify-center rounded-full bg-white/90 text-lg font-bold text-[#003f32] shadow" data-carousel-next aria-label="Foto berikutnya">›</button>
                    @endif
                </div>
                @if ($plantImages->count() > 1)
                    <div class="mt-4 flex gap-3 overflow-x-auto pb-1 scrollbar-none" data-carousel-thumbnails>
                        @foreach ($plantImages as $imageIndex => $image)
                            <button type="button" class="aspect-square w-20 shrink-0 overflow-hidden rounded-lg border-2 {{ $imageIndex === 0 ? 'border-[#16805f]' : 'border-transparent' }} bg-gray-50 transition hover:border-[#16805f]" data-carousel-thumbnail="{{ $imageIndex }}" aria-label="Lihat foto {{ $imageIndex + 1 }}">
                                <img src="{{ asset('storage/plants/' . $image) }}" alt="" class="h-full w-full object-cover">
                            </button>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- FUNGSI SCROLL -->
            <div class="order-2 min-h-0 max-h-[calc(100vh-12rem)] overflow-y-auto pr-2 md:col-start-2 md:row-start-1">
                <div>
                <span class="mb-4 inline-block rounded-sm bg-[#f0fdf4] px-3 py-1 text-[10px] font-bold uppercase tracking-wider text-[#16a34a]">
                    {{ $plant->category?->name ?? 'Tanaman Herbal' }}
                </span>
                
                <h1 class="font-['Montserrat'] text-4xl font-extrabold tracking-tight text-[#000000]">{{ $plant->name }}</h1>
                <p class="mb-8 mt-1 text-lg italic text-gray-500">{{ $plant->latin_name }}</p>
                @auth
                    <div class="mb-6 grid grid-cols-2 gap-3 rounded-lg bg-[#f6fbf6] p-4 text-xs text-[#557067]">
                        <div><p class="font-bold text-[#718178]">Populasi Greenhouse</p><p class="mt-1 text-lg font-extrabold text-[#003f32]">{{ number_format((int) ($plant->greenhouse_population ?? 0), 0, ',', '.') }}</p></div>
                        <div><p class="font-bold text-[#718178]">Populasi Outdoor</p><p class="mt-1 text-lg font-extrabold text-[#003f32]">{{ number_format((int) ($plant->outdoor_population ?? 0), 0, ',', '.') }}</p></div>
                    </div>
                @endauth
                
                <!-- 1. TAKSONOMI BOTANI -->
                <div class="content-section" data-section="taksonomi">
                    <!-- Padding card p-4 diubah jadi p-5 agar lebih lega -->
                    <section class="rounded-2xl bg-white border border-[#e0e9e1] p-5 shadow-sm">
                        <div class="mb-4">
                            <h2 class="font-['Manrope'] text-sm font-extrabold text-[#111827]">TAKSONOMI BOTANI</h2>
                            <p class="text-[10px] text-[#49645b]">Klasifikasi biologis tanaman</p>
                        </div>

                        <div class="grid grid-cols-2 gap-3 text-xs">
                            <!-- Padding kotak dalam p-2.5 diubah jadi p-3 -->
                            <div class="rounded-xl bg-[#fafafa] border border-[#f0f0f0] p-3">
                                <span class="block text-[9px] font-bold tracking-wider text-[#8a9991] uppercase">Kingdom</span>
                                <span class="font-semibold text-[#111827]">{{ $plant->kingdom ?? 'Plantae' }}</span>
                            </div>
                            <div class="rounded-xl bg-[#fafafa] border border-[#f0f0f0] p-3">
                                <span class="block text-[9px] font-bold tracking-wider text-[#8a9991] uppercase">Ordo</span>
                                <span class="font-semibold text-[#111827]">{{ $plant->order ?? $plant->ordo ?? '-' }}</span>
                            </div>
                            <div class="rounded-xl bg-[#fafafa] border border-[#f0f0f0] p-3">
                                <span class="block text-[9px] font-bold tracking-wider text-[#8a9991] uppercase">Famili</span>
                                <span class="font-semibold text-[#111827]">{{ $plant->family ?? '-' }}</span>
                            </div>
                            <div class="rounded-xl bg-[#fafafa] border border-[#f0f0f0] p-3">
                                <span class="block text-[9px] font-bold tracking-wider text-[#8a9991] uppercase">Genus</span>
                                <span class="font-semibold text-[#111827]">{{ $plant->genus ?? '-' }}</span>
                            </div>
                            <div class="col-span-2 rounded-xl bg-[#fafafa] border border-[#f0f0f0] p-3">
                                <span class="block text-[9px] font-bold tracking-wider text-[#8a9991] uppercase">Botanist</span>
                                <span class="font-semibold text-[#111827]">{{ $plant->botanist ?? $plant->author ?? '-' }}</span>
                            </div>
                        </div>
                    </section>
                </div>
                </div>
                
                <!-- Bagian Bawah: Deskripsi, Manfaat, Asal Daerah -->
                <!-- Jarak antar card mt-10 space-y-10 diubah jadi mt-8 space-y-6 agar tidak terlalu jauh -->
                <div class="mt-8 space-y-6">
                    <!-- Deskripsi -->
                    <div class="content-section" data-section="deskripsi">
                        <section class="rounded-2xl bg-white border border-[#e0e9e1] p-5 shadow-sm">
                            <div class="mb-4">
                                <h2 class="font-['Manrope'] text-sm font-extrabold text-[#111827]">DESKRIPSI MORFOLOGI</h2>
                                <p class="text-[10px] text-[#49645b]">Karakteristik fisik & rasa</p>
                            </div>

                            <div class="rounded-xl bg-[#fafafa] border border-[#f0f0f0] p-4 text-xs leading-relaxed text-[#4b5563]">
                                {{ $plant->description_full ?: $plant->description_short ?? 'Belum ada deskripsi mendalam untuk tanaman ini.' }}
                            </div>
                        </section>
                    </div>
                    
                    <!-- Manfaat -->
                    <div class="content-section" data-section="manfaat">
                        <section class="rounded-2xl bg-white border border-[#e0e9e1] p-5 shadow-sm">
                            <div class="mb-4">
                                <h2 class="font-['Manrope'] text-sm font-extrabold text-[#111827]">MANFAAT FARMAKOLOGIS</h2>
                                <p class="text-[10px] text-[#49645b]">Khasiat terapeutik teruji</p>
                            </div>

                            <div class="space-y-3">
                                @if($plant->benefits)
                                    @php
                                        $benefitItems = preg_split('/\r\n|\r|\n|;/', $plant->benefits);
                                    @endphp
                                    @foreach($benefitItems as $item)
                                        @if(trim($item) !== '')
                                            <div class="flex items-start gap-3 rounded-xl bg-[#fafafa] border border-[#f0f0f0] p-4">
                                                <span class="flex h-5 w-5 flex-shrink-0 items-center justify-center rounded-full bg-[#e8f3ea] text-[#003f32] text-xs font-bold mt-0.5">✓</span>
                                                <p class="text-xs leading-relaxed text-[#4b5563]">{{ trim(ltrim(trim($item), '-•*')) }}</p>
                                            </div>
                                        @endif
                                    @endforeach
                                @else
                                    <div class="rounded-xl bg-[#fafafa] border border-[#f0f0f0] p-4 text-xs text-[#4b5563]">
                                        Informasi manfaat belum ditambahkan.
                                    </div>
                                @endif
                            </div>
                        </section>
                    </div>

                    <!-- Asal Daerah -->
                    <div class="content-section" data-section="asal-daerah">
                        <section class="rounded-2xl bg-white border border-[#e0e9e1] p-5 shadow-sm">
                            <div class="mb-4">
                                <h2 class="font-['Manrope'] text-sm font-extrabold text-[#111827]">ASAL DAERAH & HABITAT</h2>
                                <p class="text-[10px] text-[#49645b]">Kawasan asal dan sebaran botani</p>
                            </div>
                            <div class="rounded-xl border border-green-100 bg-[#f0f8f4] p-4 shadow-sm">
                                <div class="text-[13px] leading-relaxed text-gray-700">
                                    <p class="font-bold text-[#003f32] mb-1.5">
                                        Kawasan Asal: {{ $plant->origin ?? 'Data belum tersedia' }}
                                    </p>
                                    <p>
                                        {{ $plant->origin_distribution ?? 'Sebaran botani belum ditambahkan.' }}
                                    </p>
                                </div>
                            </div>
                        </section>
                    </div>
                </div>
            </div>
        </div>
    </article>

    @if ($relatedPlants->isNotEmpty())
        <section class="mt-16" aria-labelledby="related-plants-heading">
            <div class="flex items-end justify-between gap-4">
                <div>
                    <p class="text-[10px] font-bold uppercase tracking-[0.25em] text-[#16805f]">{{ $relatedSectionLabel }}</p>
                    <h2 id="related-plants-heading" class="mt-2 font-serif text-3xl font-bold text-[#003f32]">{{ $relatedSectionTitle }}</h2>
                </div>
                <a href="{{ route($catalogRoute) }}" class="shrink-0 text-sm font-bold text-[#16805f] transition hover:text-[#003f32]">Lihat katalog <span aria-hidden="true">→</span></a>
            </div>
            <div class="mt-7 grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">
                @foreach ($relatedPlants as $relatedPlant)
                    <a href="{{ route('plant.show', $relatedPlant) }}" class="group overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm transition hover:-translate-y-1 hover:shadow-md">
                        <div class="aspect-[4/3] overflow-hidden bg-gray-50">
                            @if ($relatedPlant->image)
                                <img src="{{ asset('storage/plants/' . $relatedPlant->image) }}" alt="{{ $relatedPlant->name }}" class="h-full w-full object-cover transition duration-300 group-hover:scale-105">
                            @else
                                <div class="flex h-full items-center justify-center text-4xl text-[#477568] opacity-50">🌿</div>
                            @endif
                        </div>
                        <div class="p-4">
                            <h3 class="font-semibold text-[#111827]">{{ $relatedPlant->name }}</h3>
                            <p class="mt-1 text-sm italic text-gray-500">{{ $relatedPlant->latin_name ?: 'Nama latin belum tersedia' }}</p>
                        </div>
                    </a>
                @endforeach
            </div>
        </section>
    @endif
</div>
    <script>
        document.querySelectorAll('[data-carousel]').forEach((carousel) => {
            const slides = Array.from(carousel.querySelectorAll('[data-carousel-slide]'));
            const thumbnails = Array.from(carousel.querySelectorAll('[data-carousel-thumbnail]'));
            let currentIndex = 0;
            let autoplay;

            if (slides.length < 2) return;

            const showSlide = (index) => {
                currentIndex = (index + slides.length) % slides.length;
                slides.forEach((slide, slideIndex) => {
                    slide.classList.toggle('hidden', slideIndex !== currentIndex);
                });
                thumbnails.forEach((thumbnail, thumbnailIndex) => {
                    thumbnail.classList.toggle('border-[#16805f]', thumbnailIndex === currentIndex);
                    thumbnail.classList.toggle('border-transparent', thumbnailIndex !== currentIndex);
                });
            };

            const startAutoplay = () => {
                clearInterval(autoplay);
                autoplay = setInterval(() => showSlide(currentIndex + 1), 4500);
            };

            carousel.querySelector('[data-carousel-prev]').addEventListener('click', () => {
                showSlide(currentIndex - 1);
                startAutoplay();
            });
            carousel.querySelector('[data-carousel-next]').addEventListener('click', () => {
                showSlide(currentIndex + 1);
                startAutoplay();
            });
            thumbnails.forEach((thumbnail) => thumbnail.addEventListener('click', () => {
                showSlide(Number(thumbnail.dataset.carouselThumbnail));
                startAutoplay();
            }));
            carousel.addEventListener('mouseenter', () => clearInterval(autoplay));
            carousel.addEventListener('mouseleave', startAutoplay);
            startAutoplay();
        });
    </script>
@endsection