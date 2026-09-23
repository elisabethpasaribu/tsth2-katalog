<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Plant;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
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

        $query->when($request->filled('search'), fn ($q) => $q->search($request->string('search')->toString()));
        $categoryIds = collect($request->input('category', []))
            ->filter(fn ($categoryId) => ctype_digit((string) $categoryId))
            ->map(fn ($categoryId) => (int) $categoryId)
            ->values()
            ->all();

        $query->when($categoryIds, fn ($q) => $q->whereIn('category_id', $categoryIds));
        $query->when(in_array($request->input('status'), ['active', 'inactive'], true), function ($q) use ($request) {
            $q->where('is_active', $request->input('status') === 'active');
        });

        $categorySummary = (clone $query)
            ->reorder()
            ->select('category_id', DB::raw('COUNT(*) as plant_count'), DB::raw('COALESCE(SUM(population), 0) as population_total'))
            ->groupBy('category_id')
            ->with('category')
            ->get()
            ->sortBy(fn ($item) => $item->category?->name)
            ->values();
        $locationSummary = (clone $query)
            ->reorder()
            ->select('location_type', DB::raw('COUNT(*) as plant_count'))
            ->groupBy('location_type')
            ->pluck('plant_count', 'location_type');

$sortColumns = [
            'name' => 'plants.name',
            'category' => 'categories.name',
            'location' => 'plants.location_type',
            'population' => 'plants.population',
            'status' => 'plants.is_active',
        ];
        $sort = $request->input('sort', 'name');
        $direction = $request->input('direction') === 'desc' ? 'desc' : 'asc';

        if ($sort === 'category') {
            $query->leftJoin('categories', 'categories.id', '=', 'plants.category_id')->select('plants.*');
        }

        // --- PENGURUTAN KHUSUS LOKASI ---
        if ($sort === 'location') {
            if ($direction === 'asc') {
                $query->orderByRaw("CASE WHEN plants.location_type = 'greenhouse' THEN 1 ELSE 2 END");
            } else {
                $query->orderByRaw("CASE WHEN plants.location_type = 'greenhouse' THEN 2 ELSE 1 END");
            }
        } 
        // --- PENGURUTAN KHUSUS STATUS ---
        elseif ($sort === 'status') {
            // Jika ASC: Aktif (1) di atas, Nonaktif (0) di bawah. Jika DESC: sebaliknya.
            if ($direction === 'asc') {
                $query->orderByRaw("CASE WHEN plants.is_active = 1 THEN 1 ELSE 2 END");
            } else {
                $query->orderByRaw("CASE WHEN plants.is_active = 1 THEN 2 ELSE 1 END");
            }
        } 
        else {
            $query->orderBy($sortColumns[$sort] ?? $sortColumns['name'], $direction);
        }

        $query->orderBy('plants.id');

        $summary = [
            'total' => $categorySummary->sum('plant_count'),
            'population' => $categorySummary->sum('population_total'),
            'greenhouse' => $locationSummary->get('greenhouse', 0),
            'outdoor' => $locationSummary->get('collection', 0),
        ];

        $plants = $query
            ->paginate(10)
            ->withQueryString();

        return view('admin.plants.index', [
            'plants' => $plants,
            'summary' => $summary,
            'categorySummary' => $categorySummary,
            'categories' => Category::orderBy('name')->get(),
        ]);
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
            'location_type' => ['required', 'in:greenhouse,collection'],
            'population' => ['nullable', 'integer', 'min:0'],
            'name' => ['required', 'string', 'max:255'],
            'latin_name' => ['required', 'string', 'max:255'],
            'family' => ['required', 'string', 'max:255'],
            'ordo' => ['required', 'string', 'max:255'],
            'genus' => ['required', 'string', 'max:255'],
            'botanist' => ['required', 'string', 'max:255'],
            'description_short' => ['required', 'string'],
            'description_full' => ['required', 'string'],
            'benefits' => ['required', 'string'],
            'origin' => ['required', 'string', 'max:255'], // Tambahan untuk Kawasan Asal
            'origin_distribution' => ['required', 'string'], // Untuk Sebaran Botani
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