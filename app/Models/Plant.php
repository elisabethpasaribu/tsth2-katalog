<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Plant extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id', 
        'location_type',      // <-- Tambahkan ini agar data lokasi bisa disimpan
        'population',
        'greenhouse_population',
        'outdoor_population',
        'name', 
        'kingdom',
        'slug', 
        'latin_name', 
        'family',
        'ordo',              
        'genus',             
        'botanist',
        'benefits',
        'origin',              // <-- Tambahkan ini agar data kawasan asal bisa disimpan
        'origin_distribution',  
        'description_short', 
        'description_full', 
        'image',
        'image_gallery',
        'is_active'
    ];

    protected $casts = [
        'population' => 'integer',
        'greenhouse_population' => 'integer',
        'outdoor_population' => 'integer',
        'is_active' => 'boolean',
        'image_gallery' => 'array',
    ];

    // Relasi: 1 Plant milik 1 Category
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function populationHistories()
    {
        return $this->hasMany(PlantPopulationHistory::class)->latest('changed_at');
    }

    // Scope: filter hanya yang aktif
    public function scopeActive($query)
    {
        return $this->where('is_active', true);
    }

    // Scope: pencarian
    public function scopeSearch($query, $term)
    {
        $term = Str::lower(trim($term));

        return $query->where(function ($q) use ($term) {
            $q->where('name', 'LIKE', "%{$term}%")
              ->orWhere('latin_name', 'LIKE', "%{$term}%")
              ->orWhere('family', 'LIKE', "%{$term}%")
              ->orWhere('description_short', 'LIKE', "%{$term}%")
              ->orWhere('location_type', 'LIKE', "%{$term}%")
              ->orWhereHas('category', function ($categoryQuery) use ($term) {
                  $categoryQuery->where('name', 'LIKE', "%{$term}%")
                      ->orWhere('slug', 'LIKE', "%{$term}%");
              });

            if ($term === 'outdoor' || $term === 'koleksi') {
                $q->orWhere('location_type', 'collection');
            }

            if (in_array($term, ['aktif', 'active'], true)) {
                $q->orWhere('is_active', true);
            } elseif (in_array($term, ['nonaktif', 'inactive'], true)) {
                $q->orWhere('is_active', false);
            }
        });
    }

    // Gunakan slug untuk URL (bukan id)
    public function getRouteKeyName()
    {
        return 'slug';
    }
}