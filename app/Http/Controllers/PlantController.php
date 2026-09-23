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
        $query = Plant::with('category')->active()->where('location_type', $locationType);

        if ($request->filled('category') && $request->category !== 'semua') {
            $category = Category::where('slug', $request->category)->first();
            if ($category) {
                $query->where('category_id', $category->id);
            }
        }

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

        return view($viewName, compact('plants', 'categories', 'title'));
    }

    // Halaman detail (show)
    public function show(Request $request, Plant $plant)
    {
        // Deteksi apakah pengunjung menggunakan HP/Mobile
        $agent = $request->header('User-Agent');
        $isMobile = preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|puffin|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino/i', $agent);

        // Jika lewat HP, arahkan ke view detail mobile. Jika lewat laptop, ke view desktop asli (`catalog.show`)
        $viewName = $isMobile ? 'catalog.mobile-show' : 'catalog.show';

        return view($viewName, compact('plant'));
    }

}
