<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SpecialOffer extends Model
{
    protected $fillable = [
        'title',
        'badge',
        'emoji',
        'gradient',
        'description',
        'highlight',
        'highlight_color',
        'highlight_bg',
        'image',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    protected $attributes = [
        'is_active'       => true,
        'gradient'        => 'linear-gradient(135deg,#4a0080,#7b2ff7)',
        'highlight_bg'    => '#f0e6ff',
        'highlight_color' => '#4a0080',
        'emoji'           => '🎁',
    ];
}