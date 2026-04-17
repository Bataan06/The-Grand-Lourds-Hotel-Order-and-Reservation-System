<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    protected $fillable = [
        'event_id', 'venue_id', 'pax_range', 'pax_min', 'pax_max',
        'amenities', 'price_per_pax_set_a', 'price_per_pax_set_b',
        'price_per_pax_set_c', 'price_per_pax_set_d',
        'menu_set_a', 'menu_set_b', 'menu_set_c', 'menu_set_d',
        'price_tiers', 'is_active',
    ];

    public function getAmenitiesAttribute($value)
    {
        if (is_array($value)) return $value;
        $decoded = json_decode($value, true);
        if (is_string($decoded)) $decoded = json_decode($decoded, true);
        return $decoded ?? [];
    }

    public function getPriceTiersAttribute($value)
    {
        if (is_array($value)) return $value;
        if (!$value) return [];
        $decoded = json_decode($value, true);
        if (is_string($decoded)) $decoded = json_decode($decoded, true);
        return $decoded ?? [];
    }

    public function getMenuSetAAttribute($value)
    {
        if (is_array($value)) return $value;
        if (!$value) return null;
        $decoded = json_decode($value, true);
        return $decoded;
    }

    public function getMenuSetBAttribute($value)
    {
        if (is_array($value)) return $value;
        if (!$value) return null;
        $decoded = json_decode($value, true);
        return $decoded;
    }

    public function getMenuSetCAttribute($value)
    {
        if (is_array($value)) return $value;
        if (!$value) return null;
        $decoded = json_decode($value, true);
        return $decoded;
    }

    public function getMenuSetDAttribute($value)
    {
        if (is_array($value)) return $value;
        if (!$value) return null;
        $decoded = json_decode($value, true);
        return $decoded;
    }

    public function event() { return $this->belongsTo(Event::class); }
    public function venue() { return $this->belongsTo(Venue::class); }
    public function eventReservations() { return $this->hasMany(EventReservation::class); }
}