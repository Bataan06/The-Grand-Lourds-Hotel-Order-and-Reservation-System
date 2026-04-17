<?php

namespace App\Mail;

use App\Models\GuestMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class StaffReply extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public GuestMessage $guestMessage,
        public string $reply
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'Reply from The Grand Lourds Hotel');
    }

    public function content(): Content
    {
        return new Content(view: 'emails.staff-reply');
    }
}