<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class InvoiceMail extends Mailable
{
    use Queueable, SerializesModels;

    public $order;
    public $order_package;
    public $totalPrice;

    /**
     * Create a new message instance.
     */
    public function __construct($order, $order_package, $totalPrice)
    {
        $this->order = $order;
        $this->order_package = $order_package;
        $this->totalPrice = $totalPrice;
    }

    /**
     * Get the message envelope.
     */
    public function build()
    {
        Log::info('Menyusun email');
        return $this->subject('Invoice Brata Cerdas')
            ->view('auth.mail.invoice')
            ->with([
                'order' => $this->order,
                'order_package' => $this->order_package,
                'totalPrice' => $this->totalPrice
            ]);
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
