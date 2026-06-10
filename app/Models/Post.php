<?php

namespace App\Models;

use App\Enums\PostStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Post extends Model
{
    use SoftDeletes;

    protected $fillable = ['title', 'slug', 'content', 'status', 'user_id', 'category_id'];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($post) {
            $slug = Str::slug($post->title);
            $count = static::where('slug', 'LIKE', "{$slug}%")->count();
            $post->slug = $count ? "{$slug}-{$count}" : $slug;
        });
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class)->withTimestamps();
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    // Relation polymorphique pour lister les likes reçus par cet article
    public function likes(): MorphMany
    {
        return $this->morphMany(Like::class, 'likeable');
    }
    protected $casts = [
        'status' => PostStatus::class,
    ];
}
