<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class BannedEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $reason;
    public $start_date;
    public $end_date;

    /**
     * Create a new message instance.
     */
    public function __construct($reason, $start_date, $end_date)
    {
        $this->reason = $reason;
        $this->start_date = $start_date;
        $this->end_date = $end_date;
    }

    public function build()
    {
        return $this->text('emails.banned');
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Banned Email',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.banned',
        );
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
