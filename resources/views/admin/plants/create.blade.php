@extends('layouts.admin')

@section('title', 'Tambah Tanaman | Admin TSTH2')

@section('content')
    <a href="{{ route('admin.plants.index') }}" class="text-xs font-bold text-[#00634e]">← Kembali ke koleksi</a>
    <div class="mt-5 max-w-4xl"><p class="text-[10px] font-bold uppercase tracking-[.18em] text-[#b87900]">Koleksi tanaman</p><h1 class="mt-2 font-['Manrope'] text-3xl font-extrabold text-[#003f32]">Tambah Tanaman</h1></div>
    <form method="POST" action="{{ route('admin.plants.store') }}" enctype="multipart/form-data" class="mt-7 rounded-xl border border-[#dbe9dd] bg-white p-6 shadow-[0_5px_18px_rgba(0,63,50,.05)] sm:p-8">@csrf @php($plant = null) @php($submitLabel = 'Simpan Tanaman') @include('admin.plants._form')</form>
@endsection
