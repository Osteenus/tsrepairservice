<?php

namespace App\Crm;

use App\Models\Document;

/**
 * Details printed on a document, frozen when it is issued so the PDF can be reproduced years later.
 */
class DocumentSnapshot
{
    public static function make(Document $document): array
    {
        $job = $document->workOrder;
        $customer = $job->customer;
        $appliance = $job->appliance;

        // The visit address is printed when it differs from the customer's billing address.
        $address = $customer->address_line1;
        $cityStateZip = $customer->cityStateZip();
        if ($job->service_address && $job->service_address !== $customer->fullAddress()) {
            [$address, $cityStateZip] = array_pad(explode(', ', $job->service_address, 2), 2, '');
        }

        return [
            'company' => config('crm.company'),
            'customer' => [
                'name' => $customer->name,
                'address_line1' => (string) $address,
                'city_state_zip' => (string) $cityStateZip,
                'phone' => $customer->phone,
                'email' => $customer->email,
            ],
            'appliance' => [
                'type' => $appliance?->type ?? '',
                'brand' => $appliance?->brand ?? '',
                'model' => $appliance?->model ?? '',
                'serial' => $appliance?->serial ?? '',
                'problem' => $job->reported_problem,
                'diagnosis' => $job->findings,
            ],
            'technician' => config('crm.technician_name'),
            'diagnostic_terms' => config('crm.diagnostic_terms'),
            'warranty' => config('crm.warranty'),
            'payment_methods' => config('crm.payment_methods'),
        ];
    }
}
