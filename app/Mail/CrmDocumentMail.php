<?php

namespace App\Mail;

use App\Crm\Money;
use App\Crm\PdfRenderer;
use App\Models\Document;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

/**
 * Estimate or invoice PDF for the customer. Queued; the PDF is rendered by the queue worker.
 */
class CrmDocumentMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public int $tries = 3;

    public array $backoff = [60, 300];

    public function __construct(public Document $document) {}

    public function envelope(): Envelope
    {
        $company = $this->document->snapshot['company'] ?? config('crm.company');

        return new Envelope(
            replyTo: [new Address($company['email'], $company['name'])],
            subject: ($this->document->isEstimate() ? 'Estimate ' : 'Invoice ').$this->document->number.' from '.$company['name'],
        );
    }

    public function content(): Content
    {
        $company = $this->document->snapshot['company'] ?? config('crm.company');

        return new Content(
            text: 'emails.crm-document',
            with: [
                'customerName' => $this->document->snapshot['customer']['name'] ?? '',
                'kind' => $this->document->isEstimate() ? 'estimate' : 'invoice',
                'number' => $this->document->number,
                'total' => Money::format($this->document->total_cents),
                'company' => $company,
            ],
        );
    }

    /**
     * @return array<int, Attachment>
     */
    public function attachments(): array
    {
        $renderer = app(PdfRenderer::class);

        return [
            Attachment::fromData(fn () => $renderer->pdf($this->document), $renderer->filename($this->document))
                ->withMime('application/pdf'),
        ];
    }
}
