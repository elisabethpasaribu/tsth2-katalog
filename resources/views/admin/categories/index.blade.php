@extends('layouts.app')

@section('title', 'Kelola Kategori')

@section('content')
<div class="mx-auto max-w-5xl py-8 px-4">
    <a href="{{ route('admin.plants.index') }}" class="mb-6 inline-block text-sm font-bold text-[#557067] hover:text-[#003f32]">← Kembali ke Daftar Tanaman</a>
    
    <h1 class="mb-6 font-['Manrope'] text-2xl font-extrabold text-[#003f32]">Kelola Kategori</h1>

    @if(session('success'))
        <div class="mb-4 rounded-lg bg-[#eef6ef] p-4 text-sm font-bold text-[#003f32]">{{ session('success') }}</div>
    @endif
    
    @if ($errors->any())
        <div class="mb-4 rounded-lg bg-[#fff2e8] p-4 text-sm text-[#9b4b20]">
            {{ $errors->first() }}
        </div>
    @endif

    <div class="grid gap-8 md:grid-cols-3">
        <!-- Form Tambah Kategori -->
        <div class="md:col-span-1 h-fit rounded-xl border border-[#e0e9e1] bg-white p-6 shadow-sm">
            <h2 class="mb-4 text-sm font-bold text-[#38584d]">Tambah Kategori Baru</h2>
            <form action="{{ route('admin.categories.store') }}" method="POST">
                @csrf
                <label class="mb-2 block text-xs font-bold text-[#38584d]">Nama Kategori *</label>
                <input type="text" name="name" required class="mb-4 w-full rounded-lg bg-[#f5faf5] p-2.5 text-sm ring-1 ring-[#dbe9dd] focus:outline-none focus:ring-2 focus:ring-[#003f32]">
                <button type="submit" class="w-full rounded-lg bg-[#003f32] px-4 py-2.5 text-xs font-bold text-white hover:bg-[#00634e]">Simpan Kategori</button>
            </form>
        </div>

        <!-- Daftar Kategori -->
        <div class="md:col-span-2 rounded-xl border border-[#e0e9e1] bg-white p-6 shadow-sm">
            <h2 class="mb-4 text-sm font-bold text-[#38584d]">Daftar Kategori Saat Ini</h2>
            <div class="space-y-3">
                @forelse($categories as $category)
                    <div class="flex items-center justify-between rounded-lg border border-[#e0e9e1] p-3 hover:bg-[#f5faf5]">
                        <span class="text-sm font-bold text-[#123b32]">{{ $category->name }}</span>
                        <a href="{{ route('admin.categories.edit', $category) }}" class="rounded bg-[#e8f3ea] px-4 py-1.5 text-xs font-bold text-[#003f32] hover:bg-[#cbe5d0]">Edit</a>
                    </div>
                @empty
                    <p class="text-sm text-gray-500">Belum ada kategori.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection