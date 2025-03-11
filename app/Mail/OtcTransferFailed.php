<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OtcTransferFailed extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(protected $data)
    {
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->data['title'] ?? 'Your OTC instruction has been cancelled',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.otc-transfer-failed',
            with: [
                'heading2' => $this->data['title'] ?? 'Your OTC instruction has been cancelled',
                'username' => $this->data['user']->name ?? 'Valued Customer',
                'message2' => "Your OTC instruction (reference code: {$this->data['metadata']['reference_code']}) for {$this->data['metadata']['amount']} {$this->data['metadata']['currency']} has been cancelled.",
                'reference_code' => $this->data['metadata']['reference_code'],
                'amount' => $this->data['metadata']['amount'],
                'currency' => $this->data['metadata']['currency'],
                'created_at' => $this->data['metadata']['created_at'],
                'reason' => $this->data['metadata']['reason'] ?? 'No specific reason provided.',
            ]
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
