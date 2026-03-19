<?php

namespace App\Http\Controllers;

use App\Models\RepairRequest;
use App\Models\Contact;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\WebFormRequestReceivedEmail;


class RepairRequestController extends Controller
{
    public function store(Request $request): RedirectResponse 
    {
        // dd($request->serviceId);
        $validatedContactData = $request->validate([
            'name' => 'required|max:255',
            'phone' => 'required',
            'email' => 'nullable|email',
        ]);

        $validatedRepairRequestData = $request->validate([
            'description' => 'required|max:255',
            'serviceId' => 'required|integer'
        ]);

        // dd($validatedContactData);

        $email = 'ilmetal44@gmail.com';
        $contact = Contact::create($validatedContactData);

        $repairRequest = RepairRequest::create($validatedRepairRequestData);
        $repairRequest->contact_id = $contact->id;
        $repairRequest->service_id = $request->serviceId;
        $repairRequest->save();
        Mail::to($email)->send(new WebFormRequestReceivedEmail($repairRequest, $contact));


        return redirect('/contact')->with('success', 'Request sent successfully!');
    }
}
