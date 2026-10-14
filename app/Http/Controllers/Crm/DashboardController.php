<?php

namespace App\Http\Controllers\Crm;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\RepairRequest;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function __invoke(): Response
    {
        return Inertia::render('Crm/Dashboard', [
            'customerCount' => Customer::count(),
            'newRequestCount' => RepairRequest::whereNull('archived_at')->count(),
        ]);
    }
}
