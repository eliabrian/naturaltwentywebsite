<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

class BoardGame extends Model
{
    /** @use HasFactory<\Database\Factories\BoardGameFactory> */
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'cover_image',
        'min_players',
        'max_players',
        'playtime_min',
        'playtime_max',
        'complexity',
        'shelf_location',
        'status',
    ];

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class);
    }

    protected function duration(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->playtime_min == $this->playtime_max
                ? "{$this->playtime_min} mins"
                : "{$this->playtime_min}-{$this->playtime_max} mins"
        );
    }

    protected function players(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->min_players == $this->max_players
                ? "{$this->min_players} Players"
                : "{$this->min_players}-{$this->max_players} Players"
        );
    }

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($game) {
            if (! $game->slug) {
                $game->slug = Str::slug($game->title);
            }
        });
    }
}
