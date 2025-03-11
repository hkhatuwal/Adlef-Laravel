<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OtcTransferSuccess extends Mailable
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
            subject: $this->data['title'] ?? 'Your OTC instruction is completed',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.otc-transfer-success',
            with: [
                'heading2' => $this->data['title'] ?? 'Your OTC instruction is completed',
                'username' => $this->data['user']->name ?? 'Valued Customer',
                'message2' => "Your OTC instruction for {$this->data['metadata']['from_amount']} {$this->data['metadata']['from_currency']} to {$this->data['metadata']['to_amount']} {$this->data['metadata']['to_currency']} has been completed successfully.",
                'from_amount' => $this->data['metadata']['from_amount'],
                'from_currency' => $this->data['metadata']['from_currency'],
                'to_amount' => $this->data['metadata']['to_amount'],
                'to_currency' => $this->data['metadata']['to_currency'],
                'network_fee' => $this->data['metadata']['network_fee'],
                'processed_at' => $this->data['metadata']['processed_at'],
                'processed_by' => $this->data['metadata']['processed_by'],
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
