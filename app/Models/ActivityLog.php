<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class ActivityLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'user_name',
        'user_role',
        'action',
        'module',
        'description',
        'ip_address',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Quick helper to log an activity.
     * Usage: ActivityLog::log('login', 'User logged in')
     */
    public static function log(string $action, string $description, string $module = 'System'): void
    {
        try {
            self::create([
                'user_id'     => Auth::id(),
                'user_name'   => Auth::check() ? Auth::user()->name : 'Guest',
                'user_role'   => Auth::check() ? Auth::user()->role : 'guest',
                'action'      => $action,
                'module'      => $module,
                'description' => $description,
                'ip_address'  => Request::ip(),
            ]);
        } catch (\Exception $e) {
            // Fail silently — don't break the app if logging fails
        }
    }
}