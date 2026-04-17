<?php

namespace App\Mail;

use App\Models\GuestBooking;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class BookingConfirmed extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public GuestBooking $booking) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Booking Confirmed — The Grand Lourds Hotel',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.booking-confirmed',
        );
    }
}