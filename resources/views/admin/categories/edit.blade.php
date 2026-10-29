@extends('layouts.admin')

@section('title', 'Edit Kategori')

@section('content')
<div class="mx-auto max-w-xl py-8 px-4">
    <a href="{{ route('admin.categories.index') }}" class="mb-6 inline-block text-sm font-bold text-[#557067] hover:text-[#003f32]">← Kembali ke Kelola Kategori</a>
    
    <div class="rounded-xl border border-[#e0e9e1] bg-white p-8 shadow-sm">
        <h1 class="mb-6 font-['Manrope'] text-xl font-extrabold text-[#003f32]">Edit Kategori</h1>

        <form action="{{ route('admin.categories.update', $category) }}" method="POST">
            @csrf
            @method('PUT')
            <label class="mb-2 block text-xs font-bold text-[#38584d]">Nama Kategori *</label>
            <input type="text" name="name" value="{{ old('name', $category->name) }}" required class="mb-6 w-full rounded-lg bg-[#f5faf5] p-2.5 text-sm ring-1 ring-[#dbe9dd] focus:outline-none focus:ring-2 focus:ring-[#003f32]">
            
            <div class="flex justify-end gap-3">
                <a href="{{ route('admin.categories.index') }}" class="rounded-lg px-4 py-2.5 text-xs font-bold text-[#557067] hover:bg-[#eef6ef]">Batal</a>
                <button type="submit" class="rounded-lg bg-[#003f32] px-6 py-2.5 text-xs font-bold text-white hover:bg-[#00634e]">Update Kategori</button>
            </div>
        </form>
    </div>
</div>
@endsection