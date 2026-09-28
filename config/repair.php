<?php

return [
    'phone' => '(805) 991-2874',
    'request_recipient' => env('SERVICE_REQUEST_EMAIL', 'ilmetal44@gmail.com'),
    'registration' => [
        'business_name' => 'TECH SOLUTIONS REPAIR SERVICE, INC',
        'number' => '50537',
        'type' => 'Major Appliance Repair',
        'verification_url' => 'https://search.dca.ca.gov/details/3900/A/50537/27a445340e03876e4bbb527b372f7689',
    ],
    'service_areas' => ['Moorpark', 'Ventura County', 'Simi Valley', 'Santa Clarita'],
    'services' => [
        ['id' => 1, 'slug' => 'refrigerator', 'name' => 'Refrigerator Repair', 'url' => '/services/refrigerator-repair-moorpark', 'logoUrl' => '/storage/img/components/side-by-side-fridge.png'],
        ['id' => 2, 'slug' => 'washer', 'name' => 'Washer Repair', 'url' => '/washer-repair-moorpark', 'logoUrl' => '/storage/img/components/top-load-washer.jpeg'],
        ['id' => 3, 'slug' => 'dryer', 'name' => 'Dryer Repair', 'url' => '/services/dryer-repair', 'logoUrl' => '/storage/img/components/dryer-1.jpg'],
        ['id' => 4, 'slug' => 'oven-stove', 'name' => 'Oven & Stove Repair', 'url' => '/oven-stove-repair-moorpark', 'logoUrl' => '/images/services/oven-stove-repair-moorpark.webp'],
        ['id' => 5, 'slug' => 'dishwasher', 'name' => 'Dishwasher Repair', 'url' => '/dishwasher-repair-moorpark', 'logoUrl' => '/images/services/dishwasher-repair-moorpark.webp'],
        ['id' => 7, 'slug' => 'microwave', 'name' => 'Microwave Repair', 'url' => '/microwave-repair-moorpark', 'logoUrl' => '/images/services/microwave-repair-moorpark.webp'],
        ['id' => 8, 'slug' => 'range-hood', 'name' => 'Range Hood Repair', 'url' => '/range-hood-repair-moorpark', 'logoUrl' => '/images/services/range-hood-repair-moorpark.webp'],
        ['id' => 9, 'slug' => 'trash-compactor', 'name' => 'Trash Compactor Repair', 'url' => '/trash-compactor-repair-moorpark', 'logoUrl' => '/images/services/trash-compactor-repair-moorpark.webp'],
        ['id' => 10, 'slug' => 'electronic', 'name' => 'Electronic Repair', 'url' => '/services/electronic-repair', 'logoUrl' => '/storage/img/components/pcb.png'],
    ],
];
