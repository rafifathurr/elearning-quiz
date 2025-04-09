<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ApproveOrderMail extends Mailable
{
    use Queueable, SerializesModels;
    public $order;
    public $order_package;

    /**
     * Create a new message instance.
     */
    public function __construct($order, $order_package)
    {
        $this->order = $order;
        $this->order_package = $order_package;
    }

    public function build()
    {
        return $this->subject('Approve Order Brata Cerdas')
            ->view('auth.mail.approve-order')
            ->with([
                'order' => $this->order,
                'order_package' => $this->order_package,
            ]);
    }
}
