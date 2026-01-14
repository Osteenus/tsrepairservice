<?php

namespace App\Http\Controllers;

use App\Models\RepairRequest;
use App\Models\Contact;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

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

        $contact = Contact::create($validatedContactData);

        $repairRequest = RepairRequest::create($validatedRepairRequestData);
        $repairRequest->contact_id = $contact->id;
        $repairRequest->service_id = $request->serviceId;
        $repairRequest->save();

        // dd($repairRequest);

        return redirect('/contact')->with('success', 'Request sent successfully!');
    }
}
