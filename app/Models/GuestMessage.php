<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GuestMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'reference_no',
        'guest_name',
        'guest_phone',
        'guest_email',
        'message',
        'is_read',
        'staff_reply',
        'replied_at',
    ];

    protected $casts = [
        'is_read'    => 'boolean',
        'replied_at' => 'datetime',
    ];
}