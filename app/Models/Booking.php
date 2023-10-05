<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Booking extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'checkin',
        'checkout',
        'room_total',
        'price',
        'customer_id',
        'room_type_id',
        'adults',
        'children',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'checkin' => 'datetime',
        'checkout' => 'datetime',
    ];

    /**
     * @return BelongsToMany
     */
    public function rooms(): BelongsToMany
    {
        return $this->belongsToMany(Room::class, 'booking_rooms')->withTimestamps();
    }

    /**
     * @return BelongsTo
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * @return BelongsTo
     */
    public function roomType(): BelongsTo
    {
        return $this->belongsTo(RoomType::class);
    }
}
