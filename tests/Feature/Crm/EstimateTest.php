<?php

namespace Tests\Feature\Crm;

use App\Crm\DocumentNumbers;
use App\Crm\EstimateWorkflow;
use App\Crm\PdfRenderer;
use App\Models\Document;
use App\Models\WorkOrder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;

class EstimateTest extends CrmTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('local');
        Carbon::setTestNow('2026-10-14 17:00:00'); // Wed Oct 14, 10 AM in Los Angeles
    }

    protected function tearDown(): void
    {
        Carbon::setTestNow();
        parent::tearDown();
    }

    private function signaturePng(): string
    {
        $image = imagecreatetruecolor(40, 10);
        ob_start();
        imagepng($image);

        return 'data:image/png;base64,'.base64_encode(ob_get_clean());
    }

    private function draft(?WorkOrder $job = null): Document
    {
        $this->post(route('crm.estimates.store', $job ??= WorkOrder::factory()->create()));

        return $job->documents()->latest('id')->first();
    }

    private function saveItems(Document $estimate, array $extra = []): void
    {
        $this->put(route('crm.documents.update', $estimate), [
            'items' => [
                ['kind' => 'service_call', 'description' => 'Service call / diagnostic fee', 'qty' => 1, 'unit' => '95.00'],
                ['kind' => 'labor', 'description' => 'Labor: replace suspension rods', 'qty' => 1, 'unit' => '120'],
                ['kind' => 'part', 'description' => 'Suspension rod kit', 'part_number' => 'W10780051', 'qty' => 1, 'unit' => '52.00'],
                ...$extra,
            ],
            'discount' => '',
        ])->assertSessionHasNoErrors();
    }

    public function test_new_estimate_starts_with_the_95_dollar_service_call(): void
    {
        $this->signIn();
        $estimate = $this->draft();

        $this->assertSame('draft', $estimate->status);
        $this->assertNull($estimate->number);
        $this->assertSame('service_call', $estimate->items->sole()->kind);
        $this->assertSame(9500, $estimate->items->sole()->unit_cents);
        $this->assertSame(9500, $estimate->total_cents);
    }

    public function test_saving_items_stores_totals_in_cents_with_discount(): void
    {
        $this->signIn();
        $estimate = $this->draft();
        $this->saveItems($estimate);
        $this->assertSame(26700, $estimate->fresh()->total_cents);

        $this->put(route('crm.documents.update', $estimate), [
            'items' => [['kind' => 'labor', 'description' => 'Labor', 'qty' => 1, 'unit' => '100']],
            'discount' => '15.50',
        ]);
        $this->assertSame(1550, $estimate->fresh()->discount_cents);
        $this->assertSame(8450, $estimate->fresh()->total_cents);
    }

    public function test_numbers_are_gapless_per_year_and_skip_deleted_drafts(): void
    {
        $this->signIn();
        $numbers = app(DocumentNumbers::class);

        $first = $this->draft();
        $deleted = $this->draft(WorkOrder::factory()->create());
        $this->post(route('crm.documents.void', $deleted));
        $third = $this->draft(WorkOrder::factory()->create());

        $this->post(route('crm.estimates.issue', $first));
        $this->post(route('crm.estimates.issue', $third));

        $this->assertSame('TS-E-2026-0001', $first->fresh()->number);
        $this->assertSame('TS-E-2026-0002', $third->fresh()->number);
        $this->assertSoftDeleted($deleted);
        $this->assertSame('TS-E-2027-0001', $numbers->next('estimate', 2027));
        $this->assertSame('TS-2026-0001', $numbers->next('invoice', 2026));
        $this->assertSame('TS-E-2026-0003', $numbers->next('estimate', 2026));
    }

    public function test_issuing_freezes_the_estimate(): void
    {
        $this->signIn();
        $estimate = $this->draft();
        $this->saveItems($estimate);
        $this->post(route('crm.estimates.issue', $estimate));

        $estimate->refresh();
        $this->assertSame('issued', $estimate->status);
        $this->assertSame('2026-10-14', $estimate->issued_on->toDateString());
        $this->assertSame('2026-10-21', $estimate->valid_until->toDateString());
        $this->assertSame('State Registration No. A 50537 (BHGS)', $estimate->snapshot['company']['registration']);
        $this->assertSame($estimate->workOrder->customer->name, $estimate->snapshot['customer']['name']);
        $this->assertSame('estimate_sent', $estimate->workOrder->status);

        $this->put(route('crm.documents.update', $estimate), ['items' => []])->assertForbidden();
    }

    public function test_customer_signature_approves_estimate_and_job(): void
    {
        $this->signIn();
        $estimate = $this->draft();
        $this->saveItems($estimate);

        $this->post(route('crm.estimates.approve', $estimate), [
            'signature' => $this->signaturePng(), 'signed_name' => 'Jane Doe',
        ])->assertRedirect(route('crm.documents.show', $estimate));

        $estimate->refresh();
        $this->assertSame('approved', $estimate->status);
        $this->assertSame('TS-E-2026-0001', $estimate->number);
        $this->assertSame('approved', $estimate->workOrder->status);
        $signature = $estimate->signatures->sole();
        $this->assertSame('customer', $signature->role);
        Storage::disk('local')->assertExists($signature->path);
        $this->get(route('crm.signatures.show', $signature))->assertOk()->assertHeader('Content-Type', 'image/png');
    }

    public function test_approval_needs_a_valid_signature(): void
    {
        $this->signIn();
        $estimate = $this->draft();

        $this->post(route('crm.estimates.approve', $estimate), ['signed_name' => 'Jane'])->assertSessionHasErrors('signature');
        $this->post(route('crm.estimates.approve', $estimate), [
            'signature' => 'data:image/png;base64,'.base64_encode('not a png'), 'signed_name' => 'Jane',
        ])->assertSessionHasErrors('signature');

        $this->assertSame('draft', $estimate->fresh()->status);
        $this->assertNull($estimate->fresh()->number); // the failed approval did not use up a number
    }

    public function test_expired_estimates_cannot_be_signed(): void
    {
        $this->signIn();
        $estimate = $this->draft();
        $this->post(route('crm.estimates.issue', $estimate));

        Carbon::setTestNow('2026-10-22 17:00:00'); // Thursday after the Oct 21 deadline
        $this->post(route('crm.estimates.approve', $estimate), [
            'signature' => $this->signaturePng(), 'signed_name' => 'Jane',
        ])->assertSessionHasErrors('document');
        $this->assertSame('issued', $estimate->fresh()->status);
    }

    public function test_repair_cannot_start_without_an_approved_estimate(): void
    {
        $this->signIn();
        $estimate = $this->draft();

        $this->patch(route('crm.jobs.status', $estimate->workOrder), ['status' => 'in_progress'])->assertSessionHasErrors('status');

        $this->post(route('crm.estimates.approve', $estimate), ['signature' => $this->signaturePng(), 'signed_name' => 'Jane']);
        $this->patch(route('crm.jobs.status', $estimate->workOrder), ['status' => 'in_progress'])->assertSessionHasNoErrors();
        $this->assertSame('in_progress', $estimate->workOrder->fresh()->status);
    }

    public function test_revised_estimate_with_oral_consent_records_bhgs_details(): void
    {
        $user = $this->signIn();
        $estimate = $this->draft();
        $this->saveItems($estimate);
        $this->post(route('crm.estimates.approve', $estimate), ['signature' => $this->signaturePng(), 'signed_name' => 'Jane']);

        $this->post(route('crm.estimates.revise', $estimate->fresh()));
        $revision = Document::where('revision_of_id', $estimate->id)->sole();
        $this->assertSame(3, $revision->items()->count());
        $this->saveItems($revision, [['kind' => 'part', 'description' => 'Drain pump', 'qty' => 1, 'unit' => '80']]);

        $this->post(route('crm.estimates.approve', $revision), ['consent' => ['method' => 'oral']])
            ->assertSessionHasErrors(['consent.authorized_by', 'consent.phone', 'consent.consented_at', 'consent.description']);

        $this->post(route('crm.estimates.approve', $revision), ['consent' => [
            'method' => 'oral', 'authorized_by' => 'Jane Doe', 'phone' => '(805) 555-0100',
            'consented_at' => '2026-10-14T11:15', 'description' => 'Replace drain pump.',
        ]])->assertSessionHasNoErrors();

        $revision->refresh();
        $this->assertSame('approved', $revision->status);
        $this->assertSame(34700, $revision->total_cents);
        $consent = $revision->consent;
        $this->assertSame('oral', $consent->method);
        $this->assertSame(34700, $consent->new_total_cents);
        $this->assertSame($user->id, $consent->recorded_by);
        $this->assertSame('2026-10-14 18:15', $consent->consented_at->utc()->format('Y-m-d H:i'));
        $this->assertTrue($revision->workOrder->approvedEstimate()->is($revision));
    }

    public function test_revised_estimate_cannot_be_approved_without_consent(): void
    {
        $this->signIn();
        $estimate = $this->draft();
        $this->post(route('crm.estimates.approve', $estimate), ['signature' => $this->signaturePng(), 'signed_name' => 'Jane']);
        $this->post(route('crm.estimates.revise', $estimate->fresh()));
        $revision = Document::where('revision_of_id', $estimate->id)->sole();

        $this->post(route('crm.estimates.approve', $revision), ['consent' => ['method' => 'written', 'description' => 'More work']])
            ->assertSessionHasErrors('signature');
        $this->assertSame('draft', $revision->fresh()->status);
    }

    public function test_issued_estimates_are_voided_not_deleted(): void
    {
        $this->signIn();
        $estimate = $this->draft();
        $this->post(route('crm.estimates.issue', $estimate));
        $this->post(route('crm.documents.void', $estimate));

        $this->assertSame('void', $estimate->fresh()->status);
        $this->assertNotSoftDeleted($estimate);
    }

    public function test_estimate_html_has_the_bhgs_fields(): void
    {
        $this->signIn();
        $estimate = $this->draft();
        $this->saveItems($estimate);
        $this->post(route('crm.estimates.issue', $estimate));

        $html = app(PdfRenderer::class)->html($estimate->fresh());

        foreach ([
            'TS-E-2026-0001', 'Valid until', 'Oct 21, 2026', 'State Registration No. A 50537 (BHGS)',
            '51 Majestic Ct, Apt 904, Moorpark, CA 93021', '(805) 991-2874',
            'The $95.00 service call / diagnostic fee is included in the estimate total',
            'Sales tax and other applicable taxes are not included in this estimate.',
            'Section 9844 of the California Business and Professions Code', '$267.00', 'W10780051',
        ] as $text) {
            $this->assertStringContainsString(e($text), $html, $text);
        }
        $this->assertStringNotContainsString('class="sample"', $html);
    }

    public function test_pdf_failure_shows_a_message_instead_of_an_error_page(): void
    {
        $this->signIn();
        $estimate = $this->draft();
        $this->mock(PdfRenderer::class, function ($mock) {
            $mock->shouldReceive('pdf')->andThrow(new \App\Crm\PdfRenderingFailed('The PDF could not be created.'));
        });

        $this->from(route('crm.documents.show', $estimate))
            ->get(route('crm.documents.pdf', $estimate))
            ->assertRedirect(route('crm.documents.show', $estimate))
            ->assertSessionHas('flash.bannerStyle', 'danger');
    }

    public function test_document_screens_render(): void
    {
        $this->signIn();
        $estimate = $this->draft();

        $this->get(route('crm.documents.edit', $estimate))->assertInertia(fn ($page) => $page->component('Crm/Documents/Edit'));
        $this->get(route('crm.estimates.present', $estimate))->assertInertia(fn ($page) => $page->component('Crm/Documents/Present'));
        $this->get(route('crm.documents.show', $estimate))->assertInertia(fn ($page) => $page->component('Crm/Documents/Show'));
        $this->get(route('crm.jobs.show', $estimate->workOrder))->assertInertia(fn ($page) => $page->has('documents', 1));
    }

    public function test_guests_cannot_see_documents_or_signatures(): void
    {
        $estimate = app(EstimateWorkflow::class)->create(WorkOrder::factory()->create());

        $this->get(route('crm.documents.pdf', $estimate))->assertRedirect('/login');
        $this->get(route('crm.documents.preview', $estimate))->assertRedirect('/login');
    }
}
