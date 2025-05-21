<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OtcTransferCreated extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(protected $data)
    {
        //
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your new OTC instruction is created',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.otc-transfer-created',
            with: [
                'username' => $this->data['user']->name ?? 'Valued Customer',
                'reference_code' => $this->data['metadata']['reference_code'],
                'amount' => $this->data['metadata']['amount'],
                'currency' => $this->data['metadata']['currency'],
                'target_amount' => $this->data['metadata']['target_amount'] ?? null,
                'target_currency' => $this->data['metadata']['target_currency'] ?? null,
                'created_at' => $this->data['metadata']['created_at'],
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