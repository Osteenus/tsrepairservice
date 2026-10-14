<?php

namespace Tests\Feature\Crm;

use App\Crm\PdfRenderer;
use App\Mail\CrmDocumentMail;
use App\Models\Document;
use App\Models\Payment;
use App\Models\WorkOrder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class InvoiceTest extends CrmTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('local');
        Carbon::setTestNow('2026-10-14 17:00:00');
        $this->signIn();
    }

    protected function tearDown(): void
    {
        Carbon::setTestNow();
        parent::tearDown();
    }

    private function signature(): string
    {
        $image = imagecreatetruecolor(40, 10);
        ob_start();
        imagepng($image);

        return 'data:image/png;base64,'.base64_encode(ob_get_clean());
    }

    private function items(array $extra = []): array
    {
        return [
            ['kind' => 'service_call', 'description' => 'Service call / diagnostic fee', 'qty' => 1, 'unit' => '95'],
            ['kind' => 'labor', 'description' => 'Labor: replace suspension rods', 'qty' => 1, 'unit' => '120'],
            ['kind' => 'part', 'description' => 'Suspension rod kit', 'part_number' => 'W10780051', 'qty' => 1, 'unit' => '52'],
            ...$extra,
        ];
    }

    /** A job with an estimate the customer signed for $267.00. */
    private function approvedJob(): WorkOrder
    {
        $job = WorkOrder::factory()->create(['findings' => 'Worn suspension rods.']);
        $job->customer->update(['address_line1' => '123 Sample Street']);
        $this->post(route('crm.estimates.store', $job));
        $estimate = $job->documents()->sole();
        $this->put(route('crm.documents.update', $estimate), ['items' => $this->items()]);
        $this->post(route('crm.estimates.approve', $estimate), ['signature' => $this->signature(), 'signed_name' => 'Jane']);

        return $job;
    }

    private function invoiceFor(WorkOrder $job): Document
    {
        $this->post(route('crm.invoices.store', $job))->assertSessionHasNoErrors();

        return $job->documents()->invoices()->sole();
    }

    public function test_invoice_requires_a_written_estimate(): void
    {
        $job = WorkOrder::factory()->create();

        $this->post(route('crm.invoices.store', $job))->assertSessionHasErrors('document');
        $this->assertSame(0, $job->documents()->count());
    }

    public function test_invoice_copies_the_approved_estimate(): void
    {
        $invoice = $this->invoiceFor($this->approvedJob());

        $this->assertSame('draft', $invoice->status);
        $this->assertCount(3, $invoice->items);
        $this->assertSame(26700, $invoice->total_cents);
        $this->assertSame('Oleg Ustinov', $invoice->completed_by);
        $this->assertSame('Oleg Ustinov', $invoice->performed_by);
        $this->assertFalse($invoice->charge_tax);
    }

    public function test_invoice_above_the_authorized_estimate_is_blocked(): void
    {
        $job = $this->approvedJob();
        $invoice = $this->invoiceFor($job);
        $this->put(route('crm.documents.update', $invoice), [
            'items' => $this->items([['kind' => 'part', 'description' => 'Drain pump', 'qty' => 1, 'unit' => '80']]),
            'completed_by' => 'Oleg Ustinov', 'performed_by' => 'Oleg Ustinov',
        ]);

        $this->post(route('crm.invoices.issue', $invoice))->assertSessionHasErrors('document');
        $this->assertSame('draft', $invoice->fresh()->status);
        $this->assertNull($invoice->fresh()->number);
    }

    public function test_revised_estimate_with_consent_raises_the_limit(): void
    {
        $job = $this->approvedJob();
        $this->post(route('crm.estimates.revise', $job->documents()->sole()));
        $revision = Document::whereNotNull('revision_of_id')->sole();
        $this->put(route('crm.documents.update', $revision), [
            'items' => $this->items([['kind' => 'part', 'description' => 'Drain pump', 'qty' => 1, 'unit' => '80']]),
        ]);
        $this->post(route('crm.estimates.approve', $revision), ['consent' => [
            'method' => 'oral', 'authorized_by' => 'Jane Doe', 'phone' => '(805) 555-0100',
            'consented_at' => '2026-10-14T11:00', 'description' => 'Replace drain pump.',
        ]]);

        $invoice = $this->invoiceFor($job);
        $this->assertSame(34700, $invoice->total_cents);
        $this->post(route('crm.invoices.issue', $invoice))->assertSessionHasNoErrors();

        $invoice->refresh();
        $this->assertSame('TS-2026-0001', $invoice->number);
        $this->assertSame($revision->id, $invoice->estimate_id);
        $this->assertSame('completed', $job->fresh()->status);
    }

    public function test_declined_repair_is_billed_for_the_service_call_only(): void
    {
        $job = WorkOrder::factory()->create();
        $job->customer->update(['address_line1' => '1 Main St']);
        $this->post(route('crm.estimates.store', $job));
        $estimate = $job->documents()->sole();
        $this->put(route('crm.documents.update', $estimate), ['items' => $this->items()]);
        $this->post(route('crm.estimates.issue', $estimate));

        $invoice = $this->invoiceFor($job);
        $this->assertSame(9500, $invoice->total_cents);
        $this->post(route('crm.invoices.issue', $invoice))->assertSessionHasNoErrors();

        $html = app(PdfRenderer::class)->html($invoice->fresh());
        $this->assertStringContainsString('repair declined by customer', $html);
    }

    public function test_bhgs_fields_are_required_before_issuing(): void
    {
        $job = $this->approvedJob();
        $job->appliance->update(['serial' => null]);
        $invoice = $this->invoiceFor($job);
        $this->put(route('crm.documents.update', $invoice), ['items' => $this->items(), 'completed_by' => '', 'performed_by' => '']);

        $this->post(route('crm.invoices.issue', $invoice))->assertSessionHasErrors('document');
        $message = session('errors')->first('document');
        $this->assertStringContainsString('appliance serial number', $message);
        $this->assertStringContainsString('"invoice completed by"', $message);
        $this->assertStringContainsString('"repair performed by"', $message);
    }

    public function test_invoice_html_contains_every_bhgs_item(): void
    {
        $job = $this->approvedJob();
        $invoice = $this->invoiceFor($job);
        $this->put(route('crm.documents.update', $invoice), [
            'items' => $this->items([['kind' => 'part', 'description' => 'Rebuilt control board', 'qty' => 1, 'unit' => '0', 'is_warranty' => true, 'is_used_or_exchanged' => true]]),
            'completed_by' => 'Oleg Ustinov', 'performed_by' => 'Oleg Ustinov', 'work_performed' => 'Replaced suspension rods.',
        ]);
        $this->post(route('crm.invoices.issue', $invoice));

        $html = app(PdfRenderer::class)->html($invoice->fresh());
        $appliance = $job->appliance;

        foreach ([
            'Tech Solutions Repair Service Inc.', '51 Majestic Ct, Apt 904, Moorpark, CA 93021', '(805) 991-2874',
            'State Registration No. A 50537 (BHGS)', 'TS-2026-0001', 'Oct 14, 2026',
            $job->customer->name, '123 Sample Street',
            $appliance->brand, $appliance->model, 'S/N '.$appliance->serial,
            $job->reported_problem,
            'Non-warranty part - charged', 'Warranty replacement - no charge', 'USED / EXCHANGED part',
            'Used or exchanged parts were installed: Rebuilt control board.',
            'Invoice completed by (signature or employee no.)', 'Repair performed by (signature or employee no.)',
            'Written estimate no. TS-E-2026-0001 dated Oct 14, 2026, authorized by customer: $267.00 (excludes tax).',
            'Section 9844 of the California Business and Professions Code',
            'Labor is guaranteed for 30 days and parts installed for 90 days',
            'replaced parts returned to customer', '$267.00', 'BALANCE DUE',
        ] as $text) {
            $this->assertStringContainsString(e($text), $html, $text);
        }
        $this->assertStringNotContainsString('Sales tax on parts', $html);
    }

    public function test_tax_line_appears_only_when_enabled(): void
    {
        config(['crm.tax.enabled' => true]);
        $invoice = $this->invoiceFor($this->approvedJob());

        $this->assertTrue($invoice->charge_tax);
        $this->assertSame(377, $invoice->tax_cents); // 7.25% of $52 parts
        $this->assertSame(27077, $invoice->total_cents);
        $this->post(route('crm.invoices.issue', $invoice))->assertSessionHasNoErrors(); // limit is checked before tax
        $this->assertStringContainsString('Sales tax on parts (7.25%)', app(PdfRenderer::class)->html($invoice->fresh()));
    }

    public function test_payments_mark_the_job_paid(): void
    {
        $job = $this->approvedJob();
        $invoice = $this->invoiceFor($job);
        $this->post(route('crm.invoices.issue', $invoice));

        $this->post(route('crm.payments.store', $invoice), ['amount' => '100', 'method' => 'cash', 'paid_on' => '2026-10-14'])->assertSessionHasNoErrors();
        $this->assertSame('completed', $job->fresh()->status);

        $this->post(route('crm.payments.store', $invoice), ['amount' => '167.00', 'method' => 'zelle', 'paid_on' => '2026-10-14']);
        $this->assertSame('paid', $job->fresh()->status);
        $this->assertSame(26700, $invoice->paidCents());

        $html = app(PdfRenderer::class)->html($invoice->fresh());
        $this->assertStringContainsString('PAID', $html);
        $this->assertStringContainsString('Paid by: <b>Cash, Zelle</b>', $html);

        $this->delete(route('crm.payments.destroy', Payment::where('method', 'zelle')->sole()));
        $this->assertSame('completed', $job->fresh()->status);
        $this->assertSame(1, Payment::onlyTrashed()->count());
    }

    public function test_payments_need_an_issued_invoice(): void
    {
        $invoice = $this->invoiceFor($this->approvedJob());

        $this->post(route('crm.payments.store', $invoice), ['amount' => '10', 'method' => 'cash', 'paid_on' => '2026-10-14'])
            ->assertSessionHasErrors('amount');
        $this->post(route('crm.payments.store', $invoice), ['amount' => '10', 'method' => 'bitcoin', 'paid_on' => '2026-10-14'])
            ->assertSessionHasErrors('method');
    }

    public function test_issued_documents_are_emailed_through_the_queue(): void
    {
        Mail::fake();
        $job = $this->approvedJob();
        $estimate = $job->documents()->sole();

        $this->post(route('crm.documents.email', $estimate))->assertSessionHas('flash.banner');

        Mail::assertQueued(CrmDocumentMail::class, fn ($mail) => $mail->hasTo($job->customer->email) && $mail->document->is($estimate));
        $this->assertNotNull($estimate->fresh()->emailed_at);
    }

    public function test_drafts_and_customers_without_email_are_not_emailed(): void
    {
        Mail::fake();
        $job = $this->approvedJob();
        $invoice = $this->invoiceFor($job);

        $this->post(route('crm.documents.email', $invoice))->assertSessionHas('flash.bannerStyle', 'danger');
        $job->customer->update(['email' => null]);
        $this->post(route('crm.documents.email', $job->documents()->estimates()->sole()))->assertSessionHas('flash.bannerStyle', 'danger');

        Mail::assertNothingQueued();
    }

    public function test_invoice_screens_render(): void
    {
        $invoice = $this->invoiceFor($this->approvedJob());

        $this->get(route('crm.documents.edit', $invoice))->assertInertia(fn ($page) => $page->where('document.limit_cents', 26700));
        $this->post(route('crm.invoices.issue', $invoice));
        $this->get(route('crm.documents.show', $invoice))->assertInertia(fn ($page) => $page->where('document.balance', 26700));
    }
}
