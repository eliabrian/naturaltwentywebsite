<?php

namespace App\Models;

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

    public function getDurationAttribute()
    {
        if ($this->playtime_min == $this->playtime_max) {
            return "{$this->playtime_min} mins";
        }

        return "{$this->playtime_min}-{$this->playtime_max} mins";
    }

    public function getPlayersAttribute()
    {
        if ($this->min_players == $this->max_players) {
            return "{$this->min_players} Players";
        }

        return "{$this->min_players}-{$this->max_players} Players";
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
