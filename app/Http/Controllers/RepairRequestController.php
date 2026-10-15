<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRepairRequest;
use App\Mail\WebFormRequestReceivedEmail;
use App\Models\Contact;
use App\Models\Photo;
use App\Models\RepairRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;
use Throwable;

class RepairRequestController extends Controller
{
    public function create(Request $request): Response
    {
        $service = collect(config('repair.services'))->firstWhere('slug', $request->query('service'));

        return Inertia::render('Contact', ['selectedServiceId' => $service['id'] ?? null]);
    }

    public function store(StoreRepairRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $stored = [];

        try {
            DB::transaction(function () use ($data, &$stored) {
                $contact = Contact::create([
                    'name' => $data['name'],
                    'phone' => $data['phone'],
                    // The existing contacts table has a non-null email column.
                    'email' => $data['email'] ?? '',
                ]);
                $repairRequest = RepairRequest::create([
                    'contact_id' => $contact->id,
                    'service_id' => $data['serviceId'],
                    'location' => $data['location'],
                    'brand' => $data['brand'] ?? null,
                    'model' => $data['model'] ?? null,
                    'description' => $data['description'],
                ]);
                foreach ($data['photos'] ?? [] as $file) {
                    $photo = Photo::storeUpload($file, "requests/{$repairRequest->id}");
                    $stored[] = $photo['path'];
                    $repairRequest->photos()->create([...$photo, 'kind' => 'other', 'source' => 'customer']);
                }
                Mail::to(config('repair.request_recipient'))->send(new WebFormRequestReceivedEmail($repairRequest, $contact));
            });
        } catch (Throwable $exception) {
            report($exception);
            Storage::disk('local')->delete($stored);
            throw ValidationException::withMessages([
                'submission' => 'We could not send your request. Your details are still here. Please try again or call us.',
            ]);
        }

        return to_route('contact')->with('requestReceived', true);
    }
}
