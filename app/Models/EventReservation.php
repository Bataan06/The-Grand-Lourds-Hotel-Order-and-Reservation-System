<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventReservation extends Model
{
    protected $fillable = [
        'user_id', 'venue_id', 'event_id', 'package_id',
        'event_date', 'event_time', 'pax_count', 'food_set',
        'price_per_pax', 'total_amount', 'celebrant_name',
        'special_requests', 'status', 'confirmed_at',
    ];

    protected $casts = [
        'event_date'   => 'date',
        'confirmed_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function venue()
    {
        return $this->belongsTo(Venue::class);
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function package()
    {
        return $this->belongsTo(Package::class);
    }
}