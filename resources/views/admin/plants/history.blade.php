@extends('layouts.admin')

@section('title', 'Detail Populasi | Admin TSTH2')

@php
    $reasonLabels = [
        'purchase' => 'Pembelian tanaman',
        'propagation' => 'Hasil perbanyakan',
        'initial_stock' => 'Penambahan stok awal',
        'damaged' => 'Tanaman rusak/mati',
        'sale_distribution' => 'Penjualan atau distribusi',
        'other' => 'Lainnya',
    ];
@endphp

@section('content')
    <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-end">
        <div>
            <a href="{{ route('admin.plants.index') }}" class="text-xs font-bold text-[#00634e] hover:text-[#003f32]">&larr; Kembali ke daftar tanaman</a>
            <h1 class="mt-3 font-['Manrope'] text-3xl font-extrabold text-[#003f32]">{{ $plant->name }}</h1>
            <p class="mt-1 text-sm text-[#718178]">{{ $plant->latin_name }} &middot; {{ $plant->category?->name ?? 'Tanpa kategori' }}</p>
        </div>
        <div class="rounded-xl border border-[#dbe9dd] bg-white px-5 py-4 text-right shadow-sm">
            <p class="text-[10px] font-bold uppercase tracking-wider text-[#718178]">Populasi saat ini</p>
            <p id="current-population" class="mt-1 text-2xl font-extrabold text-[#b87900]">{{ number_format((int) ($plant->population ?? 0), 0, ',', '.') }}</p>
            <p class="mt-1 text-[10px] text-[#718178]">Greenhouse: {{ number_format((int) ($plant->greenhouse_population ?? 0), 0, ',', '.') }} · Outdoor: {{ number_format((int) ($plant->outdoor_population ?? 0), 0, ',', '.') }}</p>
        </div>
    </div>

    @if (session('success'))
        <div class="mt-6 rounded-lg bg-[#e8f3df] px-4 py-3 text-xs font-bold text-[#28604d]" role="status">{{ session('success') }}</div>
    @endif

    @if ($errors->any())
        <div class="mt-6 rounded-lg bg-[#fff2e8] px-4 py-3 text-xs text-[#9b4b20]">
            <p class="font-bold">Periksa kembali form:</p>
            <ul class="mt-1 list-disc pl-4">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
    @endif

    <button type="button" id="toggle-population-form" aria-controls="population-form-panel" aria-expanded="{{ $errors->any() ? 'true' : 'false' }}" class="mt-7 rounded-lg bg-[#003f32] px-3 py-2 text-[10px] font-bold text-white transition hover:bg-[#00634e]">
        {{ $errors->any() ? 'Tutup form' : 'Perbarui jumlah tanaman' }}
    </button>

    <div id="population-content-grid" class="mt-7 grid gap-6 {{ $errors->any() ? 'xl:grid-cols-[minmax(0,1fr)_minmax(0,1.5fr)]' : '' }}">
        <section class="order-2 rounded-2xl border border-[#dbe9dd] bg-white p-5 shadow-[0_5px_18px_rgba(0,63,50,.05)] xl:col-start-2">
            <div class="flex items-center justify-between gap-3">
                <div>
                    <h2 class="text-lg font-extrabold text-[#003f32]">Riwayat perubahan</h2>
                    <p class="mt-1 text-xs text-[#718178]">Riwayat bersifat append-only dan tidak dapat diubah atau dihapus.</p>
                </div>
            </div>
            <div class="mt-5 flex items-center justify-between gap-3 text-xs text-[#718178]">
                <p>Showing {{ $histories->firstItem() ?? 0 }}-{{ $histories->lastItem() ?? 0 }} of {{ $histories->total() }} items</p>
                <p>15 items per page</p>
            </div>
            <div class="mt-5 overflow-x-auto">
                <table class="w-full table-fixed text-left text-xs">
                    <thead class="border-b border-[#dbe9dd] bg-[#f6fbf6] text-[10px] uppercase tracking-wide text-[#718178]">
                        <tr>
                            <th class="px-3 py-3">Tanggal & waktu</th><th class="px-3 py-3">Lokasi</th><th class="px-3 py-3">Jenis</th><th class="px-3 py-3">Perubahan</th>
                            <th class="px-3 py-3">Sebelum & sesudah</th><th class="px-3 py-3">Alasan</th><th class="px-3 py-3">Catatan</th>
                            <th class="px-3 py-3">Pencatat</th><th class="px-3 py-3">Foto</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#edf3ed]">
                        @forelse ($histories as $history)
                            <tr class="align-top">
                                <td class="whitespace-nowrap px-3 py-4 text-[#557067]">{{ $history->changed_at->format('d/m/Y H:i') }}</td>
                                <td class="px-3 py-4">{{ $history->location_type === 'outdoor' ? 'Outdoor' : 'Greenhouse' }}</td>
                                <td class="px-3 py-4"><span class="rounded-full px-2 py-1 text-[10px] font-bold {{ $history->change_type === 'add' ? 'bg-[#e8f3df] text-[#28604d]' : 'bg-[#fff2e8] text-[#9b4b20]' }}">{{ $history->change_type === 'add' ? 'Tambah' : 'Kurang' }}</span></td>
                                <td class="px-3 py-4 font-bold {{ $history->change_amount > 0 ? 'text-[#28604d]' : 'text-[#b04d3e]' }}">{{ $history->change_amount > 0 ? '+' : '' }}{{ number_format($history->change_amount, 0, ',', '.') }}</td>
                                <td class="whitespace-nowrap px-3 py-4 text-[#557067]">{{ number_format($history->population_before, 0, ',', '.') }} &rarr; {{ number_format($history->population_after, 0, ',', '.') }}</td>
                                <td class="px-3 py-4 text-[#557067]">{{ $reasonLabels[$history->reason] ?? $history->reason }}@if($history->reason === 'other' && $history->other_reason)<span class="mt-1 block text-[10px] text-[#718178]">{{ $history->other_reason }}</span>@endif</td>
                                <td class="max-w-[180px] px-3 py-4 text-[#557067]">{{ $history->notes ?: '-' }}</td>
                                <td class="whitespace-nowrap px-3 py-4 text-[#557067]">{{ $history->user?->name ?? $history->recorder_name }}</td>
                                <td class="px-3 py-4">
                                    @if ($history->documentation_photo)
                                        <button type="button" class="history-photo" data-photo="{{ asset('storage/plant-population-history/' . $history->documentation_photo) }}"><img src="{{ asset('storage/plant-population-history/' . $history->documentation_photo) }}" alt="Dokumentasi perubahan" class="h-12 w-12 rounded-lg object-cover ring-1 ring-[#dbe9dd]"></button>
                                    @else
                                        <span class="flex h-12 w-12 items-center justify-center rounded-lg bg-[#f1f5f1] text-[10px] text-[#718178]">Tidak ada</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="9" class="px-3 py-12 text-center text-sm text-[#718178]">Belum ada riwayat perubahan.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if ($histories->hasPages())
                <div class="mt-5 border-t border-[#edf3ed] pt-4">
                    {{ $histories->links() }}
                </div>
            @endif
        </section>

        <section id="population-form-panel" class="{{ $errors->any() ? '' : 'hidden' }} order-1 h-fit rounded-2xl border border-[#dbe9dd] bg-white p-5 shadow-[0_5px_18px_rgba(0,63,50,.05)] xl:col-start-1">
            <h2 class="text-lg font-extrabold text-[#003f32]">Perbarui jumlah tanaman</h2>
            <p class="mt-1 text-xs text-[#718178]">Tanggal perubahan diisi otomatis oleh server saat data disimpan.</p>
            <form method="POST" action="{{ route('admin.plants.population.update', $plant) }}" enctype="multipart/form-data" class="mt-5 space-y-4" id="population-form">
                @csrf
                <label class="block text-xs font-bold text-[#38584d]">Jumlah populasi saat ini
                    <span class="mt-2 grid grid-cols-2 gap-2">
                        <input id="form-current-greenhouse" value="Greenhouse: {{ (int) ($plant->greenhouse_population ?? 0) }}" readonly class="admin-input bg-[#f1f5f1]">
                        <input id="form-current-outdoor" value="Outdoor: {{ (int) ($plant->outdoor_population ?? 0) }}" readonly class="admin-input bg-[#f1f5f1]">
                    </span>
                </label>
                <label class="block text-xs font-bold text-[#38584d]">Lokasi perubahan *
                    <select name="location_type" id="population-location" class="admin-input mt-2" required>
                        <option value="greenhouse" @selected(old('location_type', 'greenhouse') === 'greenhouse')>Greenhouse</option>
                        <option value="outdoor" @selected(old('location_type') === 'outdoor')>Outdoor</option>
                    </select>
                </label>
                <fieldset>
                    <legend class="text-xs font-bold text-[#38584d]">Jenis perubahan *</legend>
                    <div class="mt-2 grid grid-cols-2 gap-2">
                        <label class="cursor-pointer rounded-lg border border-[#dbe9dd] p-3 text-xs font-bold text-[#557067] has-[:checked]:border-[#00634e] has-[:checked]:bg-[#e8f3df]"><input type="radio" name="change_type" value="add" class="mr-2 accent-[#00634e]" @checked(old('change_type') === 'add')>Tambah</label>
                        <label class="cursor-pointer rounded-lg border border-[#dbe9dd] p-3 text-xs font-bold text-[#557067] has-[:checked]:border-[#b04d3e] has-[:checked]:bg-[#fff2e8]"><input type="radio" name="change_type" value="subtract" class="mr-2 accent-[#b04d3e]" @checked(old('change_type') === 'subtract')>Kurang</label>
                    </div>
                </fieldset>
                <label class="block text-xs font-bold text-[#38584d]">Jumlah perubahan *
                    <input type="number" name="change_amount" id="change-amount" min="1" step="1" value="{{ old('change_amount') }}" class="admin-input mt-2" required>
                    <span id="amount-help" class="mt-1 block text-[10px] font-normal text-[#718178]">Masukkan bilangan bulat positif.</span>
                </label>
                <label class="block text-xs font-bold text-[#38584d]">Alasan perubahan *
                    <select name="reason" id="reason" class="admin-input mt-2" required><option value="">Pilih alasan</option></select>
                </label>
                <label id="other-reason-wrapper" class="hidden block text-xs font-bold text-[#38584d]">Keterangan alasan lainnya *
                    <input name="other_reason" value="{{ old('other_reason') }}" class="admin-input mt-2">
                </label>
                <label class="block text-xs font-bold text-[#38584d]">Tanggal & waktu perubahan
                    <input value="{{ now()->format('d/m/Y H:i:s') }} (server)" readonly class="admin-input mt-2 bg-[#f1f5f1]">
                </label>
                <label class="block text-xs font-bold text-[#38584d]">Catatan tambahan
                    <textarea name="notes" rows="3" class="admin-input mt-2">{{ old('notes') }}</textarea>
                </label>
                <label class="block text-xs font-bold text-[#38584d]">Foto dokumentasi <span id="photo-required-label"></span>
                    <input type="file" name="documentation_photo" id="documentation-photo" accept="image/jpeg,image/png,image/webp" class="mt-2 block w-full rounded-lg bg-[#f5faf5] p-2 text-xs ring-1 ring-[#dbe9dd]">
                    <span class="mt-1 block text-[10px] font-normal text-[#718178]">Maksimal 10 MB.</span>
                </label>

                <div class="rounded-xl bg-[#f6fbf6] p-4 text-xs text-[#557067]">
                    <p class="font-bold text-[#003f32]">Ringkasan sebelum submit</p>
                    <dl class="mt-3 space-y-2">
                        <div class="flex justify-between gap-3"><dt>Populasi</dt><dd id="summary-population" class="font-bold">-</dd></div>
                        <div class="flex justify-between gap-3"><dt>Jenis & jumlah</dt><dd id="summary-change" class="font-bold">-</dd></div>
                        <div class="flex justify-between gap-3"><dt>Alasan</dt><dd id="summary-reason" class="text-right font-bold">-</dd></div>
                        <div class="flex justify-between gap-3"><dt>Pencatat</dt><dd class="font-bold">{{ auth()->user()?->name ?? config('admin.user_name', 'Admin') }}</dd></div>
                        <div class="flex justify-between gap-3"><dt>Foto</dt><dd id="summary-photo" class="font-bold">Belum terlampir</dd></div>
                    </dl>
                </div>
                <button type="submit" id="submit-population" disabled class="w-full rounded-lg bg-[#003f32] px-5 py-3 text-xs font-bold text-white transition enabled:hover:bg-[#00634e] disabled:cursor-not-allowed disabled:opacity-40">Simpan perubahan</button>
            </form>
        </section>
    </div>

    <div id="photo-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/70 p-5" role="dialog" aria-modal="true">
        <button type="button" id="close-photo-modal" class="absolute right-5 top-5 rounded-full bg-white px-3 py-2 text-sm font-bold text-[#003f32]">Tutup</button>
        <img id="photo-modal-image" src="" alt="Foto dokumentasi diperbesar" class="max-h-[90vh] max-w-full rounded-xl object-contain">
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const form = document.getElementById('population-form');
            const formPanel = document.getElementById('population-form-panel');
            const contentGrid = document.getElementById('population-content-grid');
            const toggleForm = document.getElementById('toggle-population-form');
            const populations = {
                greenhouse: Number(@json((int) ($plant->greenhouse_population ?? 0))),
                outdoor: Number(@json((int) ($plant->outdoor_population ?? 0)))
            };
            const location = document.getElementById('population-location');
            const typeInputs = [...form.querySelectorAll('input[name="change_type"]')];
            const amount = document.getElementById('change-amount');
            const reason = document.getElementById('reason');
            const otherWrapper = document.getElementById('other-reason-wrapper');
            const otherReason = otherWrapper.querySelector('input');
            const photo = document.getElementById('documentation-photo');
            const submit = document.getElementById('submit-population');
            const options = {
                add: { purchase: 'Pembelian tanaman', propagation: 'Hasil perbanyakan', initial_stock: 'Penambahan stok awal', other: 'Lainnya' },
                subtract: { damaged: 'Tanaman rusak/mati', sale_distribution: 'Penjualan atau distribusi', other: 'Lainnya' }
            };
            const oldReason = @json(old('reason'));
            const format = value => new Intl.NumberFormat('id-ID').format(value);

            toggleForm.addEventListener('click', () => {
                const isHidden = formPanel.classList.toggle('hidden');
                contentGrid.classList.toggle('xl:grid-cols-[minmax(0,1fr)_minmax(0,1.5fr)]', !isHidden);
                toggleForm.setAttribute('aria-expanded', String(!isHidden));
                toggleForm.textContent = isHidden ? 'Perbarui jumlah tanaman' : 'Tutup form';
                if (!isHidden) formPanel.scrollIntoView({ behavior: 'smooth', block: 'start' });
            });

            function refreshReasons(reset = false) {
                const type = typeInputs.find(input => input.checked)?.value;
                reason.innerHTML = '<option value="">Pilih alasan</option>';
                Object.entries(options[type] || {}).forEach(([value, label]) => reason.add(new Option(label, value)));
                if (!reset && oldReason && type) reason.value = oldReason;
                if (reset) {
                    amount.value = '';
                    reason.value = '';
                    otherReason.value = '';
                }
                update();
            }

            function update() {
                const type = typeInputs.find(input => input.checked)?.value;
                const current = populations[location.value] || 0;
                const value = Number(amount.value);
                const signed = type === 'subtract' ? -value : value;
                const validAmount = Number.isInteger(value) && value > 0 && (type !== 'subtract' || value <= current);
                const isOther = reason.value === 'other';
                otherWrapper.classList.toggle('hidden', !isOther);
                otherReason.required = isOther;
                document.getElementById('photo-required-label').textContent = type === 'subtract' ? '(wajib)' : '(opsional)';
                document.getElementById('summary-population').textContent = type && validAmount ? `${location.options[location.selectedIndex].text}: ${format(current)} → ${format(current + signed)}` : '-';
                document.getElementById('summary-change').textContent = type && validAmount ? `${type === 'add' ? 'Tambah' : 'Kurang'} ${format(Math.abs(signed))}` : '-';
                document.getElementById('summary-reason').textContent = reason.options[reason.selectedIndex]?.text || '-';
                document.getElementById('summary-photo').textContent = photo.files.length ? 'Terlampir' : 'Belum terlampir';
                submit.disabled = !(type && validAmount && reason.value && (!isOther || otherReason.value.trim()) && (type !== 'subtract' || photo.files.length));
            }

            typeInputs.forEach(input => input.addEventListener('change', () => refreshReasons(true)));
            [amount, reason, otherReason, photo, location].forEach(input => input.addEventListener('input', update));
            refreshReasons();

            document.querySelectorAll('.history-photo').forEach(button => button.addEventListener('click', () => {
                document.getElementById('photo-modal-image').src = button.dataset.photo;
                document.getElementById('photo-modal').classList.remove('hidden');
                document.getElementById('photo-modal').classList.add('flex');
            }));
            document.getElementById('close-photo-modal').addEventListener('click', () => {
                document.getElementById('photo-modal').classList.add('hidden');
                document.getElementById('photo-modal').classList.remove('flex');
            });
        });
    </script>
@endsection
