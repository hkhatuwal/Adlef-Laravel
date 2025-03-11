<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TransferOutSuccess extends Mailable
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
            subject: $this->data['title'] ?? 'Transfer Verified',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.transfer-out-success',
            with: [
                'heading2' => $this->data['title'] ?? 'Transfer Verified',
                'username' => $this->data['user']->name ?? 'Valued Customer',
                'message2' => $this->data['message'] ?? "Your transfer has been verified successfully.",
                'reference_number' => $this->data['metadata']['reference_number'],
                'amount' => $this->data['metadata']['amount'],
                'currency' => $this->data['metadata']['currency'],
                'fee' => $this->data['metadata']['fee'],
                'verified_at' => $this->data['metadata']['verified_at'],
                'verified_by' => $this->data['metadata']['verified_by'],
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
