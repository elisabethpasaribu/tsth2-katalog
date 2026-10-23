<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Plant;
use App\Models\Category;

class PlantController extends Controller
{
    // Halaman katalog Greenhouse (index utama)
    public function index(Request $request)
    {
        return $this->filterPlants($request, 'greenhouse');
    }

    // Halaman katalog Tanaman Koleksi (Outdoor)
    public function collection(Request $request)
    {
        return $this->filterPlants($request, 'collection');
    }

    // Fungsi bantu bersama agar filternya konsisten
    private function filterPlants(Request $request, string $locationType)
    {
        $categories = Category::orderBy('id')->get();
        $rawLocationCounts = Plant::active()
            ->selectRaw('location_type, COUNT(*) as total')
            ->groupBy('location_type')
            ->pluck('total', 'location_type');
        $locationCounts = collect([
            'greenhouse' => $rawLocationCounts->get('greenhouse', 0) + $rawLocationCounts->get('both', 0),
            'collection' => $rawLocationCounts->get('collection', 0) + $rawLocationCounts->get('outdoor', 0) + $rawLocationCounts->get('both', 0),
        ]);
        $selectedCategories = collect((array) $request->input('category', []))
            ->filter(fn ($slug) => is_string($slug) && $slug !== 'semua')
            ->values();
        $categoryIds = Category::whereIn('slug', $selectedCategories->all())->pluck('id');

        $query = Plant::with('category')
            ->active()
            ->where(function ($query) use ($locationType) {
                $locationValues = $locationType === 'collection'
                    ? ['collection', 'outdoor', 'both']
                    : [$locationType, 'both'];
                $query->whereIn('location_type', $locationValues);
            })
            ->when($categoryIds->isNotEmpty(), fn ($query) => $query->whereIn('category_id', $categoryIds));

        if ($request->filled('search')) {
            $query->search($request->search);
        }

        $plants = $query->latest()->paginate(12)->withQueryString();
        $title = $locationType === 'greenhouse' ? 'Katalog Greenhouse' : 'Tanaman Koleksi (Outdoor)';

        // Deteksi apakah pengunjung menggunakan HP/Mobile
        $agent = $request->header('User-Agent');
        $isMobile = preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|puffin|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino/i', $agent);

        // Pilih file view: mobile atau desktop asli
        $viewName = $isMobile ? 'catalog.mobile' : 'catalog.index';

        return view($viewName, [
            'plants' => $plants,
            'categories' => $categories,
            'title' => $title,
            'locationType' => $locationType,
            'selectedCategories' => $selectedCategories->all(),
            'locationCounts' => $locationCounts,
        ]);
    }

    // Halaman detail (show)
    public function show(Request $request, Plant $plant)
    {
        $plant->load('category');
        $relatedPlants = $plant->category
            ? Plant::with('category')
                ->active()
                ->where('category_id', $plant->category_id)
                ->where($plant->getKeyName(), '!=', $plant->getKey())
                ->latest()
                ->limit(4)
                ->get()
            : collect();
        $relatedSectionLabel = $relatedPlants->isNotEmpty() ? 'KATEGORI SERUPA' : 'KATEGORI LAINNYA';
        $relatedSectionTitle = $relatedPlants->isNotEmpty()
            ? $plant->category->name
            : 'Tanaman Herbal Lainnya';

        if ($relatedPlants->isEmpty()) {
            $relatedPlants = Plant::with('category')
                ->active()
                ->where($plant->getKeyName(), '!=', $plant->getKey())
                ->latest()
                ->limit(4)
                ->get();
        }

        // Deteksi apakah pengunjung menggunakan HP/Mobile
        $agent = $request->header('User-Agent');
        $isMobile = preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|puffin|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino/i', $agent);

        // Jika lewat HP, arahkan ke view detail mobile. Jika lewat laptop, ke view desktop asli (`catalog.show`)
        $viewName = $isMobile ? 'catalog.mobile-show' : 'catalog.show';

        return view($viewName, compact('plant', 'relatedPlants', 'relatedSectionLabel', 'relatedSectionTitle'));
    }

}
