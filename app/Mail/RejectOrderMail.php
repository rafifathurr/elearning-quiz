<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class RejectOrderMail extends Mailable
{
    use Queueable, SerializesModels;
    public $order;


    /**
     * Create a new message instance.
     */
    public function __construct($order)
    {
        $this->order = $order;
    }

    public function build()
    {
        return $this->subject('Pemberitahuan Order Brata Cerdas')
            ->view('auth.mail.reject-order')
            ->with([
                'order' => $this->order,
            ]);
    }
}
