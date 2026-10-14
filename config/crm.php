<?php

return [
    // Business dates (issued_on, valid_until, "today") are computed in this zone; timestamps stay in UTC.
    'timezone' => 'America/Los_Angeles',

    // Shown only on customer documents (estimates, invoices), never on public pages.
    'company' => [
        'name' => 'Tech Solutions Repair Service',
        'legal_name' => 'Tech Solutions Repair Service Inc.',
        'address' => '51 Majestic Ct, Apt 904, Moorpark, CA 93021',
        'service_area' => 'Serving Moorpark & Ventura County',
        'phone' => '(805) 991-2874',
        'email' => 'service@tsrepairservice.com',
        'website' => 'tsrepairservice.com',
        'registration' => 'State Registration No. A 50537 (BHGS)',
    ],

    // Default name for "invoice completed by" / "repair performed by".
    'technician_name' => 'Oleg Ustinov',

    'service_call_cents' => 9500,
    'service_call_description' => 'Service call / diagnostic fee',
    'estimate_valid_business_days' => 5,

    'diagnostic_terms' => 'The $95.00 service call / diagnostic fee is included in the estimate total, not charged extra. If you decline the repair, only $95.00 is due.',

    'warranty' => 'Guarantor: Tech Solutions Repair Service Inc. Labor is guaranteed for 30 days and parts installed for 90 days from the date of service. The guarantee covers the repair performed and the parts installed; it does not cover other components or damage caused by misuse.',

    'payment_methods' => 'Cash, check, Zelle or card (confirm with technician).',

    // Sales tax stays OFF until the business has a CDTFA seller's permit. When on, it applies to parts only.
    'tax' => [
        'enabled' => false,
        'default_rate' => 0.0725,
    ],
];
