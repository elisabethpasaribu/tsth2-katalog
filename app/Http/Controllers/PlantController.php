<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Plant;
use App\Models\Category;

class PlantController extends Controller
{
    // Halaman katalog (index)
    public function index(Request $request)
    {
        // 1. Ambil semua kategori untuk tombol filter
        $categories = Category::orderBy('id')->get();

        // 2. Mulai query tanaman yang aktif + eager load kategori
        $query = Plant::with('category')->active();

        // 3. Filter kategori (jika ada parameter 'category' & bukan 'semua')
        if ($request->filled('category') && $request->category !== 'semua') {
            $category = Category::where('slug', $request->category)->first();
            if ($category) {
                $query->where('category_id', $category->id);
            }
        }

        // 4. Filter pencarian (jika ada parameter 'search')
        if ($request->filled('search')) {
            $query->search($request->search);
        }

        // 5. Ambil hasil dengan paginasi 12 per halaman
        //    withQueryString() = pertahankan ?search=... & ?category=... saat ganti halaman
        $plants = $query->latest()->paginate(12)->withQueryString();

        // 6. Kirim data ke view 'catalog.index'
        return view('catalog.index', compact('plants', 'categories'));
    }

    // Halaman detail (show)
    // Route model binding otomatis cari Plant berdasarkan slug
    public function show(Plant $plant)
    {
        return view('catalog.show', compact('plant'));
    }
}