<?php

namespace App\Mail;

use App\Models\TravelOrder;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Queue\SerializesModels;

class TravelOrderNotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $order;

    public function __construct(TravelOrder $order)
    {
        $this->order = $order;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Order #{$this->order->id} status has been updated",
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.travel_order_notification',
        );
    }
}
