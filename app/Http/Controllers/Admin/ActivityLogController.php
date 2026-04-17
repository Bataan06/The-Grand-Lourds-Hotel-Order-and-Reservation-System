<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        $query = ActivityLog::with('user')->latest();

        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        if ($request->filled('search')) {
            $query->where('user_name', 'like', '%' . $request->search . '%');
        }

        $logs = $query->paginate(5)->withQueryString();

        return view('admin.activity-logs', compact('logs'));
    }
}