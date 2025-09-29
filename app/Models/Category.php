<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Category extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'image',
        'slug',
        'category_name',
        'short_description',
    ];

    // Relasi: Satu Kategori punya banyak Produk
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    protected static function booted()
    {
        static::creating(function ($category) {
            $slug = Str::slug($category->category_name);
            $originalSlug = $slug;
            $counter = 1;

            // Ulangi sampai slug unik
            while (Category::where('slug', $slug)->exists()) {
                $slug = $originalSlug . '-' . $counter++;
            }

            $category->slug = $slug;
        });

        static::updating(function ($category) {
            $slug = Str::slug($category->category_name);
            $originalSlug = $slug;
            $counter = 1;

            while (Category::where('slug', $slug)->where('id', '!=', $category->id)->exists()) {
                $slug = $originalSlug . '-' . $counter++;
            }

            $category->slug = $slug;
        });
    }
}
