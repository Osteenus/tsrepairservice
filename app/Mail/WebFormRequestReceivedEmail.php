<?php

namespace App\Mail;

use App\Models\Contact;
use App\Models\RepairRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

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
                'appliance' => collect(config('repair.services'))->firstWhere('id', $this->repairRequest->service_id)['name'] ?? 'Unknown',
                'location' => $this->repairRequest->location,
                'brand' => $this->repairRequest->brand,
                'model' => $this->repairRequest->model,

            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
