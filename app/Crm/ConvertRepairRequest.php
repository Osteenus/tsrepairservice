<?php

namespace App\Crm;

use App\Models\Customer;
use App\Models\RepairRequest;
use App\Models\WorkOrder;
use Illuminate\Support\Facades\DB;

/**
 * Turns a website service request into a CRM customer, appliance and work order.
 */
class ConvertRepairRequest
{
    public function __invoke(RepairRequest $request): WorkOrder
    {
        return DB::transaction(function () use ($request) {
            $request = RepairRequest::lockForUpdate()->findOrFail($request->id);

            if ($existing = $request->workOrder) {
                return $existing;
            }

            $contact = $request->contact;
            $customer = Customer::where('phone_digits', Customer::digits($contact?->phone))->whereNotNull('phone_digits')->first();

            if (! $customer) {
                $location = trim((string) $request->location);
                $isZip = (bool) preg_match('/^\d{5}(-\d{4})?$/', $location);

                $customer = Customer::create([
                    'name' => $contact?->name ?: 'Website customer',
                    'phone' => $contact?->phone,
                    'email' => $contact?->email ?: null,
                    'city' => $isZip ? null : ($location ?: null),
                    'zip' => $isZip ? substr($location, 0, 5) : null,
                    'state' => 'CA',
                ]);
            } elseif (! $customer->email && $contact?->email) {
                $customer->update(['email' => $contact->email]);
            }

            $type = $request->applianceType();
            $appliance = $customer->appliances()
                ->where('type', $type)
                ->where('brand', $request->brand)
                ->where('model', $request->model)
                ->first()
                ?? $customer->appliances()->create(['type' => $type, 'brand' => $request->brand, 'model' => $request->model]);

            $request->forceFill(['archived_at' => now()])->save();

            return WorkOrder::create([
                'customer_id' => $customer->id,
                'appliance_id' => $appliance->id,
                'repair_request_id' => $request->id,
                'reported_problem' => $request->description,
                'service_address' => $customer->fullAddress() ?: null,
            ]);
        });
    }
}
