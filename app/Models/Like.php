<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Like extends Model
{
    // Pas de SoftDeletes ici : un unlike supprime physiquement la ligne pour ne pas encombrer la BDD
    protected $fillable = ['user_id', 'likeable_id', 'likeable_type'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Reconnecte dynamiquement le Like au bon modèle parent (Post ou Comment)
    public function likeable(): MorphTo
    {
        return $this->morphTo();
    }
}
