@extends('layouts.admin')

@section('title', 'Kelola Tanaman | Admin TSTH2')

@section('content')
    <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-end">
        <div><p class="text-[10px] font-bold uppercase tracking-[.18em] text-[#b87900]">Dashboard admin</p><h1 class="mt-2 font-['Manrope'] text-3xl font-extrabold text-[#003f32]">Koleksi Tanaman</h1><p class="mt-1 text-sm text-[#718178]">Tambah, ubah, atau hapus data katalog.</p></div>
        <a href="{{ route('admin.plants.create') }}" class="inline-flex h-10 items-center justify-center rounded-lg bg-[#003f32] px-4 text-xs font-bold text-white hover:bg-[#00634e]">+ Tambah Tanaman</a>
    </div>
    <form method="GET" class="mt-7 flex gap-2"><input type="search" name="search" value="{{ request('search') }}" placeholder="Cari nama tanaman..." class="h-10 flex-1 rounded-lg border-0 bg-white px-4 text-sm ring-1 ring-[#dbe9dd] focus:ring-2 focus:ring-[#00634e]"><button class="rounded-lg bg-[#e3eee4] px-5 text-xs font-bold text-[#285a4c] hover:bg-[#d5e7d7]">Cari</button></form>
    <div class="mt-5 overflow-x-auto rounded-xl border border-[#dbe9dd] bg-white shadow-[0_5px_18px_rgba(0,63,50,.05)]">
        <table class="w-full min-w-[760px] text-left text-xs"><thead class="border-b border-[#dbe9dd] bg-[#f6fbf6] text-[10px] uppercase tracking-wide text-[#718178]"><tr><th class="px-5 py-4">Tanaman</th><th class="px-5 py-4">Kategori</th><th class="px-5 py-4">Status</th><th class="px-5 py-4 text-right">Aksi</th></tr></thead><tbody class="divide-y divide-[#edf3ed]">
        @forelse ($plants as $plant)
            <tr class="hover:bg-[#fbfefb]"><td class="px-5 py-4"><div class="flex items-center gap-3">@if($plant->image)<img src="{{ asset('storage/plants/'.$plant->image) }}" class="h-10 w-10 rounded-md object-cover">@else<div class="flex h-10 w-10 items-center justify-center rounded-md bg-[#e8f3df] text-lg">🌿</div>@endif<div><p class="font-bold text-[#164f43]">{{ $plant->name }}</p><p class="mt-0.5 text-[10px] italic text-[#b87900]">{{ $plant->latin_name }}</p></div></div></td><td class="px-5 py-4 text-[#557067]">{{ $plant->category?->name }}</td><td class="px-5 py-4"><span class="rounded-full px-2.5 py-1 text-[10px] font-bold {{ $plant->is_active ? 'bg-[#e8f3df] text-[#28604d]' : 'bg-[#f1eeee] text-[#866464]' }}">{{ $plant->is_active ? 'Aktif' : 'Nonaktif' }}</span></td><td class="px-5 py-4"><div class="flex justify-end gap-3 font-bold"><a href="{{ route('admin.plants.edit', $plant) }}" class="text-[#00634e] hover:text-[#b87900]">Edit</a><form method="POST" action="{{ route('admin.plants.destroy', $plant) }}" data-delete-form>@csrf @method('DELETE')<button class="text-[#b04d3e] hover:text-[#7d2318]">Hapus</button></form></div></td></tr>
        @empty<tr><td colspan="4" class="px-5 py-14 text-center text-sm text-[#718178]">Belum ada data tanaman.</td></tr>@endforelse
        </tbody></table>
    </div>
    <div class="mt-6">{{ $plants->links() }}</div>
@endsection
