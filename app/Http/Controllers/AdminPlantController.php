<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Plant;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AdminPlantController extends Controller
{
    public function login(): View
    {
        return view('admin.login');
    }

    public function authenticate(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'password' => ['required', 'string'],
        ]);

        if (! hash_equals((string) config('admin.password'), $credentials['password'])) {
            return back()->withErrors(['password' => 'Password admin tidak sesuai.'])->onlyInput('password');
        }

        $request->session()->regenerate();
        $request->session()->put('admin_authenticated', true);

        return redirect()->intended(route('admin.plants.index'));
    }

    public function logout(Request $request): RedirectResponse
    {
        $request->session()->forget('admin_authenticated');
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }

    public function index(Request $request): View
    {
        $query = Plant::with('category');

        // Filter tambahan untuk admin memilah berdasarkan lokasi
        if ($request->filled('location') && in_array($request->location, ['greenhouse', 'collection'])) {
            $query->where('location_type', $request->location);
        }

        $plants = $query->when($request->filled('search'), fn ($q) => $q->search($request->string('search')->toString()))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.plants.index', compact('plants'));
    }

    public function create(): View
    {
        return view('admin.plants.create', ['categories' => Category::orderBy('name')->get()]);
    }

    public function store(Request $request): RedirectResponse
    {
        $plant = new Plant();
        $this->savePlant($plant, $request);

        return redirect()->route('admin.plants.index')->with('success', 'Tanaman berhasil ditambahkan.');
    }

    public function edit(Plant $plant): View
    {
        return view('admin.plants.edit', [
            'plant' => $plant,
            'categories' => Category::orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, Plant $plant): RedirectResponse
    {
        $this->savePlant($plant, $request);

        return redirect()->route('admin.plants.index')->with('success', 'Tanaman berhasil diperbarui.');
    }

    public function destroy(Plant $plant): RedirectResponse
    {
        if ($plant->image) {
            Storage::disk('public')->delete('plants/' . $plant->image);
        }

        $plant->delete();

        return back()->with('success', 'Tanaman berhasil dihapus.');
    }

    private function savePlant(Plant $plant, Request $request): void
    {
        $validated = $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'location_type' => ['required', 'in:greenhouse,collection'], // <-- DITAMBAHKAN AGAR TIDAK DIABAIKAN
            'name' => ['required', 'string', 'max:255'],
            'latin_name' => ['required', 'string', 'max:255'],
            'family' => ['required', 'string', 'max:255'],
            'ordo' => ['required', 'string', 'max:255'],
            'genus' => ['required', 'string', 'max:255'],
            'botanist' => ['required', 'string', 'max:255'],
            'description_short' => ['required', 'string'],
            'description_full' => ['required', 'string'],
            'benefits' => ['required', 'string'],
            'origin_distribution' => ['required', 'string'],
            'image' => [$plant->exists ? 'nullable' : 'required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $slugRule = Rule::unique('plants', 'slug')->ignore($plant->id);
        validator(['slug' => $validated['slug']], ['slug' => [$slugRule]])->validate();
        $validated['is_active'] = $request->boolean('is_active');

        if ($request->hasFile('image')) {
            if ($plant->image) {
                Storage::disk('public')->delete('plants/' . $plant->image);
            }
            $validated['image'] = $request->file('image')->store('plants', 'public');
            $validated['image'] = basename($validated['image']);
        }

        $plant->fill($validated)->save();
    }
}