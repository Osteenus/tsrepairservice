<?php

namespace App\Http\Controllers\Crm;

use App\Http\Controllers\Controller;
use App\Models\Appliance;
use App\Models\Customer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ApplianceController extends Controller
{
    public function store(Request $request, Customer $customer): RedirectResponse
    {
        $customer->appliances()->create($this->validated($request));

        return back()->with('flash.banner', 'Appliance added.');
    }

    public function update(Request $request, Appliance $appliance): RedirectResponse
    {
        $appliance->update($this->validated($request));

        return back()->with('flash.banner', 'Appliance saved.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'type' => ['required', 'string', 'max:120'],
            'brand' => ['nullable', 'string', 'max:120'],
            'model' => ['nullable', 'string', 'max:120'],
            'serial' => ['nullable', 'string', 'max:120'],
            'notes' => ['nullable', 'string', 'max:5000'],
        ]);
    }
}
