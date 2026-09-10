@extends('layouts.admin')

@section('title', 'Login Admin | TSTH2')

@section('content')
    <div class="mx-auto mt-10 max-w-md rounded-2xl border border-[#dbe9dd] bg-white p-8 shadow-[0_12px_35px_rgba(0,63,50,.08)]">
        <p class="text-[10px] font-bold uppercase tracking-[.18em] text-[#b87900]">TSTH2 Indonesia</p>
        <h1 class="mt-2 font-['Manrope'] text-3xl font-extrabold text-[#003f32]">Masuk ke Admin</h1>
        <p class="mt-2 text-sm leading-6 text-[#718178]">Kelola koleksi tanaman herbal dari satu tempat.</p>
        @if ($errors->any())
            <div class="mt-5 rounded-lg bg-[#fff2e8] px-4 py-3 text-xs text-[#9b4b20]">{{ $errors->first() }}</div>
        @endif
        <form method="POST" action="{{ route('admin.authenticate') }}" class="mt-7 space-y-4">
            @csrf
            <label class="block text-xs font-bold text-[#38584d]">Password admin
                <input type="password" name="password" required autofocus class="mt-2 h-11 w-full rounded-lg border-0 bg-[#f5faf5] px-3 text-sm ring-1 ring-[#dbe9dd] focus:ring-2 focus:ring-[#00634e]">
            </label>
            <button class="h-11 w-full rounded-lg bg-[#003f32] text-sm font-bold text-white transition hover:bg-[#00634e]">Masuk</button>
        </form>
    </div>
@endsection
