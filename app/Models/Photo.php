<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class Photo extends Model
{
    use SoftDeletes;

    public const KINDS = ['serial_label' => 'Serial label', 'before' => 'Before', 'after' => 'After', 'other' => 'Other'];

    public const SOURCES = ['technician' => 'Technician', 'customer' => 'From customer'];

    protected $fillable = ['path', 'mime', 'kind', 'caption', 'source', 'repair_request_id'];

    /**
     * Store an uploaded image on the private disk under the given folder.
     */
    public static function storeUpload(UploadedFile $file, string $folder): array
    {
        $extension = $file->guessExtension() ?: 'jpg';

        return [
            'path' => $file->storeAs($folder, Str::uuid().'.'.$extension, 'local'),
            'mime' => $file->getMimeType(),
        ];
    }

    public function workOrder(): BelongsTo
    {
        return $this->belongsTo(WorkOrder::class);
    }

    public function repairRequest(): BelongsTo
    {
        return $this->belongsTo(RepairRequest::class);
    }
}
