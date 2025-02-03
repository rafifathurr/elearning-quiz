<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class FinishMail extends Mailable
{
    use Queueable, SerializesModels;
    public $data;
    public $pdfPath;

    /**
     * Create a new message instance.
     */
    public function __construct($data, $pdfPath)
    {
        $this->data = $data;
        $this->pdfPath = $pdfPath;
    }

    /**
     * Get the message envelope.
     */
    public function build()
    {
        Log::info('Menyusun email');
        return $this->subject('Hasil Test')
            ->view('auth.mail.finish')
            ->with(['data' => $this->data])
            ->attach($this->pdfPath, [
                'as' => 'result_pdf.pdf',
                'mime' => 'application/pdf',
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
