<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Venue extends Model
{
    protected $fillable = ['name', 'description', 'image', 'is_active'];

    public function packages()
    {
        return $this->hasMany(Package::class);
    }

    public function eventReservations()
    {
        return $this->hasMany(EventReservation::class);
    }
}