<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class CafeTable extends Model
{
    protected $fillable = ['name', 'token'];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($table) {
            if (empty($table->token)) {
                $table->token = (string) Str::uuid(); 
            }
        });
    }
}
