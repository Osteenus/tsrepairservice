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
        'phone' => '(805) 334-8375',
        'email' => 'service@tsrepairservice.com',
        'website' => 'tsrepairservice.com',
        'registration' => 'State Registration No. A 50537 (BHGS)',
    ],

    // Default name for "invoice completed by" / "repair performed by".
    'technician_name' => 'Oleg Ustinov',

    // Appliance picker in the CRM ("Other…" allows any text). The first names match the website
    // service list, so appliances created from web requests use the same names.
    'appliance_types' => [
        'Refrigerator', 'Freezer', 'Ice Maker', 'Wine Cooler', 'Washer', 'Dryer', 'Dishwasher',
        'Oven & Stove', 'Cooktop', 'Microwave', 'Range Hood', 'Trash Compactor', 'Electronic',
    ],

    // Brand suggestions; brands already typed in the CRM are suggested too.
    'appliance_brands' => [
        'Admiral', 'Amana', 'Bosch', 'Electrolux', 'Frigidaire', 'GE', 'GE Profile', 'Haier', 'Hotpoint',
        'JennAir', 'Kenmore', 'KitchenAid', 'LG', 'Magic Chef', 'Maytag', 'Roper', 'Samsung', 'Sub-Zero',
        'Thermador', 'U-Line', 'Viking', 'Whirlpool', 'Wolf',
    ],

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

    // Headless Chromium for PDFs (spatie/browsershot + puppeteer). Leave null to use what is on PATH.
    'pdf' => [
        'node_binary' => env('CRM_PDF_NODE_BINARY'),
        'npm_binary' => env('CRM_PDF_NPM_BINARY'),
        'chrome_path' => env('CRM_PDF_CHROME_PATH'),
        'no_sandbox' => (bool) env('CRM_PDF_NO_SANDBOX', false),
    ],
];
