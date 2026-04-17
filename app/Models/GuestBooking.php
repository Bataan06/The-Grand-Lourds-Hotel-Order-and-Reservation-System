<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GuestBooking extends Model
{
    protected $fillable = [
        'reference_no',
        'guest_name',
        'guest_phone',
        'guest_email',
        'event_id',
        'package_id',
        'venue_id',
        'celebrant_name',
        'pax_count',
        'event_date',
        'event_time_start',
        'food_set',
        'price_per_pax',
        'total_amount',
        'additional_charges',
        'additional_total',
        'special_requests',
        'status',
        'payment_status',
        'amount_paid',
        'has_conflict',
        'is_pencil',
        'expires_at',
    ];

    protected $casts = [
        'event_date'         => 'date',
        'additional_charges' => 'array',
        'total_amount'       => 'decimal:2',
        'amount_paid'        => 'decimal:2',
        'has_conflict'       => 'boolean',
        'is_pencil'          => 'boolean',
        'expires_at'         => 'datetime',
    ];

    public function event()   { return $this->belongsTo(Event::class); }
    public function venue()   { return $this->belongsTo(Venue::class); }
    public function package() { return $this->belongsTo(Package::class); }
}