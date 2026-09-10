@extends('layouts.app')

@section('title', $plant->name . ' | Katalog TSTH2')

@section('content')
    <a href="{{ route('home') }}" class="mb-7 inline-flex items-center gap-2 text-[11px] font-bold text-[#005541] transition hover:text-[#c28100]">
        <span class="text-base">←</span> Kembali ke koleksi tanaman
    </a>

    <header class="mb-7">
        <p class="text-[10px] font-bold uppercase tracking-[.18em] text-[#b87900]">Direktori Tanaman Herbal TSTH2</p>
        <h1 class="mt-2 font-['Manrope'] text-3xl font-extrabold tracking-tight text-[#003f32] sm:text-[40px]">Detail Tanaman</h1>
    </header>

    <article class="detail-panel overflow-hidden rounded-xl bg-white">
        <div class="grid lg:grid-cols-[minmax(330px,.82fr)_1.18fr]">
            <div>
                <div class="plant-photo relative flex min-h-[300px] items-center justify-center lg:min-h-[390px]">
                    @if ($plant->image)
                        <img src="{{ asset('storage/plants/' . $plant->image) }}" alt="{{ $plant->name }}" class="h-full min-h-[300px] w-full object-cover lg:min-h-[390px]">
                    @else
                        <div class="text-center text-[#477568]"><div class="text-7xl opacity-50">🌿</div><p class="mt-2 text-xs">Gambar belum tersedia</p></div>
                    @endif
                    <span class="absolute left-5 top-5 rounded-full bg-[#f5c329] px-3 py-1.5 text-[9px] font-bold uppercase tracking-wide text-[#5b4100]">{{ $plant->category?->name ?? 'Tanaman Herbal' }}</span>
                </div>

                <div class="space-y-5 p-6 sm:p-8">
                    <section>
                        <h3 class="font-['Manrope'] text-base font-extrabold text-[#003f32]">Deskripsi</h3>
                        <p class="mt-2 text-xs leading-6 text-[#5d6c65]">{{ $plant->description_full ?: $plant->description_short }}</p>
                    </section>
                    <section class="border-t border-[#e1ebe2] pt-5">
                        <h3 class="font-['Manrope'] text-base font-extrabold text-[#003f32]">Manfaat</h3>
                        <p class="mt-2 text-xs leading-6 text-[#5d6c65]">{{ $plant->benefits ?: 'Informasi manfaat tanaman belum dicatat.' }}</p>
                    </section>
                    <section class="border-t border-[#e1ebe2] pt-5">
                        <h3 class="font-['Manrope'] text-base font-extrabold text-[#003f32]">Asal Daerah &amp; Sebaran Botani</h3>
                        <p class="mt-2 text-xs leading-6 text-[#5d6c65]">{{ $plant->origin_distribution ?: 'Informasi asal daerah dan sebaran botani belum dicatat.' }}</p>
                    </section>
                </div>
            </div>
            <div class="p-6 sm:p-9 lg:p-11">
                <h2 class="font-['Manrope'] text-3xl font-extrabold tracking-tight text-[#003f32] sm:text-4xl">{{ $plant->name }}</h2>
                <p class="mt-1 text-sm italic text-[#b87900]">{{ $plant->latin_name }}</p>

                <div class="mt-8 border-y border-[#e1ebe2] py-6">
                    <h3 class="font-['Manrope'] text-base font-extrabold text-[#003f32]">Klasifikasi Botani</h3>
                    <dl class="mt-4 grid grid-cols-2 gap-x-5 gap-y-4 sm:grid-cols-3">
                        <div><dt class="text-[9px] font-bold uppercase tracking-wide text-[#84928b]">Kingdom</dt><dd class="mt-1 text-xs font-bold text-[#164f43]">Plantae</dd></div>
                        <div><dt class="text-[9px] font-bold uppercase tracking-wide text-[#84928b]">Ordo</dt><dd class="mt-1 text-xs font-bold text-[#164f43]">{{ data_get($plant, 'ordo') ?: 'Belum dicatat' }}</dd></div>
                        <div><dt class="text-[9px] font-bold uppercase tracking-wide text-[#84928b]">Famili</dt><dd class="mt-1 text-xs font-bold text-[#164f43]">{{ $plant->family }}</dd></div>
                        <div><dt class="text-[9px] font-bold uppercase tracking-wide text-[#84928b]">Genus</dt><dd class="mt-1 text-xs font-bold text-[#164f43]">{{ data_get($plant, 'genus') ?: 'Belum dicatat' }}</dd></div>
                        <div><dt class="text-[9px] font-bold uppercase tracking-wide text-[#84928b]">Botanis</dt><dd class="mt-1 text-xs font-bold text-[#164f43]">{{ $plant->botanist ?: 'Belum dicatat' }}</dd></div>
                    </dl>
                </div>

            </div>
        </div>
    </article>
@endsection