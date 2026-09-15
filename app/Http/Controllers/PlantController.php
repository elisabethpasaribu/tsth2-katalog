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
        return $this->filterPlants($request, 'greenhouse', 'catalog.index');
    }

    // Halaman katalog Tanaman Koleksi (Outdoor)
    public function collection(Request $request)
    {
        return $this->filterPlants($request, 'collection', 'catalog.index');
    }

    // Fungsi bantu bersama agar filternya konsisten (Greenhouse & Collection)
   private function filterPlants(Request $request, string $locationType, string $viewName)
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

    // Tentukan judul berdasarkan lokasi
    $title = $locationType === 'greenhouse' ? 'Katalog Greenhouse' : 'Tanaman Koleksi (Outdoor)';

    return view($viewName, compact('plants', 'categories', 'title'));
}

    // Halaman detail (show)
    public function show(Plant $plant)
    {
        return view('catalog.show', compact('plant'));
    }
}