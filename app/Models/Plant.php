<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plant extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id', 
    'name', 
    'slug', 
    'latin_name', 
    'family',
    'ordo',                 // <-- Ordo ditambahkan
    'genus',                // <-- Genus ditambahkan
    'botanist',
    'benefits',
    'origin_distribution',  // <-- Sesuai dengan phpMyAdmin
    'description_short', 
    'description_full', 
    'image', 
    'is_active'
    ];

    // Relasi: 1 Plant milik 1 Category
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Scope: filter hanya yang aktif
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Scope: pencarian
    public function scopeSearch($query, $term)
    {
        return $query->where(function ($q) use ($term) {
            $q->where('name', 'LIKE', "%{$term}%")
              ->orWhere('latin_name', 'LIKE', "%{$term}%")
              ->orWhere('family', 'LIKE', "%{$term}%")
              ->orWhere('description_short', 'LIKE', "%{$term}%");
        });
    }

    // Gunakan slug untuk URL (bukan id)
    public function getRouteKeyName()
    {
        return 'slug';
    }
}