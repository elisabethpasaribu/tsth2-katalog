@if ($errors->any())<div class="mb-6 rounded-lg bg-[#fff2e8] px-4 py-3 text-xs text-[#9b4b20]"><p class="font-bold">Periksa kembali form:</p><ul class="mt-1 list-disc pl-4">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
<div class="grid gap-5 md:grid-cols-2">
    <label class="block text-xs font-bold text-[#38584d]">Nama tanaman *<input name="name" value="{{ old('name', $plant->name ?? '') }}" required class="admin-input"></label>
    <label class="block text-xs font-bold text-[#38584d]">Kategori *<select name="category_id" required class="admin-input"><option value="">Pilih kategori</option>@foreach($categories as $category)<option value="{{ $category->id }}" @selected(old('category_id', $plant->category_id ?? '') == $category->id)>{{ $category->name }}</option>@endforeach</select></label>
    <label class="block text-xs font-bold text-[#38584d]">Nama latin *<input name="latin_name" value="{{ old('latin_name', $plant->latin_name ?? '') }}" required class="admin-input italic"></label>
    <label class="block text-xs font-bold text-[#38584d]">Famili *<input name="family" value="{{ old('family', $plant->family ?? '') }}" required class="admin-input"></label>
    <label class="block text-xs font-bold text-[#38584d]">Botanis<input name="botanist" value="{{ old('botanist', $plant->botanist ?? '') }}" class="admin-input"></label>
    <label class="block text-xs font-bold text-[#38584d]">Gambar tanaman<input type="file" name="image" accept="image/jpeg,image/png,image/webp" class="mt-2 block w-full rounded-lg bg-[#f5faf5] p-2 text-xs ring-1 ring-[#dbe9dd]">@if(!empty($plant?->image))<span class="mt-1 block text-[10px] font-normal text-[#718178]">Gambar saat ini: {{ $plant->image }}</span>@endif</label>
</div>
<div class="mt-5 space-y-5">
    <label class="block text-xs font-bold text-[#38584d]">Deskripsi singkat *<textarea name="description_short" required rows="3" class="admin-input">{{ old('description_short', $plant->description_short ?? '') }}</textarea></label>
    <label class="block text-xs font-bold text-[#38584d]">Deskripsi lengkap<textarea name="description_full" rows="4" class="admin-input">{{ old('description_full', $plant->description_full ?? '') }}</textarea></label>
    <label class="block text-xs font-bold text-[#38584d]">Manfaat<textarea name="benefits" rows="4" class="admin-input">{{ old('benefits', $plant->benefits ?? '') }}</textarea></label>
    <label class="block text-xs font-bold text-[#38584d]">Asal daerah &amp; sebaran botani<textarea name="origin_distribution" rows="4" class="admin-input">{{ old('origin_distribution', $plant->origin_distribution ?? '') }}</textarea></label>
</div>
<label class="mt-5 flex items-center gap-2 text-xs font-bold text-[#38584d]"><input type="checkbox" name="is_active" value="1" @checked(old('is_active', $plant->is_active ?? true)) class="h-4 w-4 accent-[#003f32]"> Tampilkan di katalog publik</label>
<div class="mt-7 flex justify-end gap-3"><a href="{{ route('admin.plants.index') }}" class="rounded-lg px-4 py-2.5 text-xs font-bold text-[#557067] hover:bg-[#eef6ef]">Batal</a><button class="rounded-lg bg-[#003f32] px-5 py-2.5 text-xs font-bold text-white hover:bg-[#00634e]">{{ $submitLabel }}</button></div>
