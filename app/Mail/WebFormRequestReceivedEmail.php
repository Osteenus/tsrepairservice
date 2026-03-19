<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\RepairRequest;
use App\Models\Contact;


class WebFormRequestReceivedEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $repairRequest;
    public $contact;

    /**
     * Create a new message instance.
     */
    public function __construct(RepairRequest $repairRequest, Contact $contact)
    {
        $this->repairRequest = $repairRequest;
        $this->contact = $contact;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Web Form Request Received Email',
        );
    }

    

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        
        return new Content(
            view: 'emails.message-received',
            with: [
                'name' => $this->contact->name,
                'phone' => $this->contact->phone,
                'email' => $this->contact->email,
                'messageText' => $this->repairRequest->description,

            ],
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
