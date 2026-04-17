<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    // List all users who have sent messages
    public function index()
    {
        $users = User::where('role', 'user')
            ->whereHas('sentMessages')
            ->withCount(['sentMessages as unread_count' => function ($q) {
                $q->where('is_read', false)->where('sender_role', 'user');
            }])
            ->get();

        return view('staff.messages.index', compact('users'));
    }

    // Show conversation with a specific user
    public function show($userId)
    {
        $user = User::findOrFail($userId);

        $messages = Message::where(function ($q) use ($userId) {
                $q->where('sender_id', $userId)
                  ->orWhere(function ($q2) use ($userId) {
                      $q2->where('receiver_id', $userId)
                         ->where('sender_role', '!=', 'user');
                  })
                  ->orWhere(function ($q3) {
                      $q3->whereNull('receiver_id')
                         ->where('sender_role', '!=', 'user');
                  });
            })
            ->with('sender')
            ->orderBy('created_at', 'asc')
            ->get();

        // Mark user messages as read
        Message::where('sender_id', $userId)
               ->where('sender_role', 'user')
               ->where('is_read', false)
               ->update(['is_read' => true]);

        return view('staff.messages.show', compact('user', 'messages'));
    }

    // Reply to user
    public function reply(Request $request, $userId)
    {
        $request->validate(['body' => 'required|string|max:1000']);

        Message::create([
            'sender_id'   => Auth::id(),
            'sender_role' => Auth::user()->role,
            'receiver_id' => $userId,
            'body'        => $request->body,
            'is_read'     => false,
        ]);

        return back()->with('success', 'Message sent!');
    }

    // Unread count for badge (AJAX)
    public function unread()
    {
        $count = Message::where('sender_role', 'user')
            ->where('is_read', false)
            ->count();

        return response()->json(['count' => $count]);
    }
}