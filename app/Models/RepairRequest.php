<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RepairRequest extends Model
{
    protected $fillable = [
        'service',
        'description',
    ];

    protected $attributes = [
        'contact_id' => 1,
        'service_id' => 1,
    ];
}
