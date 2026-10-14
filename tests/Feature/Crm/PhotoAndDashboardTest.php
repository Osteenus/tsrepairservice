<?php

namespace Tests\Feature\Crm;

use App\Models\Contact;
use App\Models\Document;
use App\Models\Payment;
use App\Models\Photo;
use App\Models\RepairRequest;
use App\Models\WorkOrder;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;

class PhotoAndDashboardTest extends CrmTestCase
{
    public function test_photos_are_stored_privately_and_served_only_to_users(): void
    {
        Storage::fake('local');
        $this->signIn();
        $job = WorkOrder::factory()->create();

        $this->post(route('crm.photos.store', $job), [
            'photos' => [UploadedFile::fake()->image('label.jpg'), UploadedFile::fake()->image('before.jpg')],
            'kind' => 'serial_label',
            'caption' => 'Rating plate',
        ])->assertSessionHasNoErrors();

        $photo = $job->photos()->first();
        $this->assertCount(2, $job->photos);
        $this->assertSame('serial_label', $photo->kind);
        Storage::disk('local')->assertExists($photo->path);
        $this->assertStringStartsWith("photos/{$job->id}/", $photo->path);
        $this->assertFileDoesNotExist(public_path('storage/'.$photo->path));

        $this->get(route('crm.photos.show', $photo))->assertOk()->assertHeader('Content-Type', 'image/jpeg');
        $this->get(route('crm.jobs.show', $job))->assertInertia(fn ($page) => $page->has('photos', 2));

        $this->app['auth']->forgetGuards();
        $this->get(route('crm.photos.show', $photo))->assertRedirect('/login');
    }

    public function test_only_images_are_accepted(): void
    {
        Storage::fake('local');
        $this->signIn();

        $this->post(route('crm.photos.store', WorkOrder::factory()->create()), [
            'photos' => [UploadedFile::fake()->create('notes.pdf', 10, 'application/pdf')],
            'kind' => 'other',
        ])->assertSessionHasErrors('photos.0');
    }

    public function test_removed_photos_are_soft_deleted_and_the_file_kept(): void
    {
        Storage::fake('local');
        $this->signIn();
        $job = WorkOrder::factory()->create();
        $this->post(route('crm.photos.store', $job), ['photos' => [UploadedFile::fake()->image('a.jpg')], 'kind' => 'after']);
        $photo = Photo::sole();

        $this->delete(route('crm.photos.destroy', $photo));

        $this->assertSoftDeleted($photo);
        Storage::disk('local')->assertExists($photo->path);
    }

    public function test_dashboard_shows_today_upcoming_requests_and_unpaid_invoices(): void
    {
        $this->signIn();
        Carbon::setTestNow('2026-10-14 17:00:00'); // 10 AM in Los Angeles
        WorkOrder::factory()->create(['scheduled_at' => '2026-10-14 21:00:00']); // 2 PM today
        WorkOrder::factory()->create(['scheduled_at' => '2026-10-16 16:00:00']);
        WorkOrder::factory()->create(['scheduled_at' => '2026-10-14 21:00:00', 'status' => 'cancelled']);
        $contact = Contact::create(['name' => 'Web Lead', 'phone' => '8055550000', 'email' => '']);
        RepairRequest::create(['contact_id' => $contact->id, 'service_id' => 1, 'location' => 'Moorpark', 'description' => 'Warm fridge']);

        $paidJob = WorkOrder::factory()->create();
        $unpaid = $paidJob->documents()->create(['type' => 'invoice', 'status' => 'issued', 'issued_on' => '2026-10-10']);
        $unpaid->forceFill(['number' => 'TS-2026-0001', 'total_cents' => 26700])->save();
        $unpaid->payments()->create(['work_order_id' => $paidJob->id, 'amount_cents' => 10000, 'method' => 'cash', 'paid_on' => '2026-10-10']);
        $settled = $paidJob->documents()->create(['type' => 'invoice', 'status' => 'issued', 'issued_on' => '2026-10-11']);
        $settled->forceFill(['number' => 'TS-2026-0002', 'total_cents' => 9500])->save();
        $settled->payments()->create(['work_order_id' => $paidJob->id, 'amount_cents' => 9500, 'method' => 'zelle', 'paid_on' => '2026-10-11']);

        $this->get('/crm')->assertInertia(fn ($page) => $page
            ->component('Crm/Dashboard')
            ->has('today', 1)
            ->where('today.0.time', '2:00 PM')
            ->has('upcoming', 1)
            ->has('requests', 1)
            ->where('newRequestCount', 1)
            ->has('unpaid', 1)
            ->where('unpaid.0.number', 'TS-2026-0001')
            ->where('unpaidTotal', 16700));

        Carbon::setTestNow();
    }
}
