<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Category extends Model
{
    use SoftDeletes; // Active la suppression logique

    protected $fillable = ['name', 'slug'];

    protected static function boot()
    {
        parent::boot();

        // Génère automatiquement un slug unique lors de la création
        static::creating(function ($category) {
            $slug = Str::slug($category->name);
            $count = static::where('slug', 'LIKE', "{$slug}%")->count();
            $category->slug = $count ? "{$slug}-{$count}" : $slug;
        });
    }

    // Force Laravel à chercher par slug dans l'URL (ex: /api/categories/web-design)
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }
}
