<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RepairRequest extends Model
{
    protected $fillable = [
        'contact_id', 'service_id', 'location', 'brand', 'model', 'description',
    ];
}
