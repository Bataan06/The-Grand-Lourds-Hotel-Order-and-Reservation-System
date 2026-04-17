<?php

namespace App\Http\Controllers;

use App\Models\GuestMessage;
use Illuminate\Http\Request;

class GuestMessageController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'guest_name'  => 'required|string|max:255',
            'guest_phone' => 'required|string|max:20',
            'guest_email' => 'nullable|email|max:255',
            'message'     => 'required|string|max:2000',
        ]);

        $refNo = 'MSG-' . strtoupper(substr(md5(uniqid()), 0, 8));

        // Start conversation with the first guest message
        $conversation = [
            [
                'role'    => 'guest',
                'message' => $validated['message'],
                'sent_at' => now()->format('M d, Y h:i A'),
            ]
        ];

        GuestMessage::create([
            'reference_no' => $refNo,
            'guest_name'   => $validated['guest_name'],
            'guest_phone'  => $validated['guest_phone'],
            'guest_email'  => $validated['guest_email'] ?? null,
            'message'      => $validated['message'],
            'is_read'      => false,
            'conversation' => $conversation,
        ]);

        return response()->json([
            'success'      => true,
            'reference_no' => $refNo,
            'message'      => 'Message sent successfully.',
        ]);
    }

    public function checkReply(Request $request)
    {
        $request->validate([
            'reference_no' => 'required|string',
        ]);

        $msg = GuestMessage::where('reference_no', strtoupper(trim($request->reference_no)))->first();

        if (!$msg) {
            return response()->json(['found' => false, 'message' => 'Reference number not found.']);
        }

        return response()->json([
            'found'        => true,
            'reference_no' => $msg->reference_no,
            'guest_name'   => $msg->guest_name,
            'conversation' => $msg->conversation ?? [],
            'staff_reply'  => $msg->staff_reply,
            'replied_at'   => $msg->replied_at ? $msg->replied_at->format('M d, Y h:i A') : null,
        ]);
    }

    public function guestReply(Request $request)
    {
        $request->validate([
            'reference_no' => 'required|string',
            'message'      => 'required|string|max:2000',
        ]);

        $msg = GuestMessage::where('reference_no', strtoupper(trim($request->reference_no)))->first();

        if (!$msg) {
            return response()->json(['success' => false, 'message' => 'Reference number not found.']);
        }

        // Append guest reply to conversation
        $conversation = $msg->conversation ?? [];
        $conversation[] = [
            'role'    => 'guest',
            'message' => $request->message,
            'sent_at' => now()->format('M d, Y h:i A'),
        ];

        $msg->update([
            'conversation' => $conversation,
            'message'      => $request->message, // update latest message
            'is_read'      => false,              // mark as unread again for staff
        ]);

        return response()->json([
            'success'      => true,
            'conversation' => $conversation,
        ]);
    }
}