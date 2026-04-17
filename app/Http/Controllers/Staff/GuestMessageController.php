<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\GuestMessage;
use Illuminate\Http\Request;

class GuestMessageController extends Controller
{
    public function index()
    {
        $unread   = GuestMessage::where('is_read', false)->count();
        $messages = GuestMessage::latest()->paginate(10);

        // Mark all as read
        GuestMessage::where('is_read', false)->update(['is_read' => true]);

        return view('staff.messages.index', compact('messages', 'unread'));
    }

    public function reply(Request $request, GuestMessage $guestMessage)
    {
        $request->validate([
            'staff_reply' => 'required|string|max:2000',
        ]);

        $guestMessage->update([
            'staff_reply' => $request->staff_reply,
            'replied_at'  => now(),
        ]);

        return back()->with('success', 'Reply sent successfully.');
    }
}