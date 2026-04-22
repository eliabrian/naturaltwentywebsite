<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    protected $fillable = [
        'total_amount',
        'status',
        'customer_name',
        'cafe_table_id', 
    ];

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function cafeTable()
    {
        return $this->belongsTo(CafeTable::class);
    }
}
