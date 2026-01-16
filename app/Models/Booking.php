<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Booking extends Model
{
    /** @use HasFactory<\Database\Factories\BookingFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'room_id',
        'customer_name',
        'customer_phone',
        'booking_date',
        'eta',
        'total_person',
        'need_dm',
        'total_price',
        'payment_status',
        'status',
        'payment_proof',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'booking_date' => 'date',
            'eta' => 'datetime',
            'need_dm' => 'boolean',
            'total_price' => 'decimal:2',
        ];
    }

    /**
     * Get the room that belongs to the booking
     */
    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }
}
