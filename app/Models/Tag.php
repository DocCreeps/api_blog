<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

class Tag extends Model
{
    protected $fillable = ['name', 'slug'];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($tag) {
            $slug = Str::slug($tag->name);
            $count = static::where('slug', 'LIKE', "{$slug}%")->count();
            $tag->slug = $count ? "{$slug}-{$count}" : $slug;
        });
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    // Relation Many-to-Many vers les articles (via la table pivot post_tag)
    public function posts(): BelongsToMany
    {
        return $this->belongsToMany(Post::class)->withTimestamps();
    }
}
