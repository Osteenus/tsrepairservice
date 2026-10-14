<?php

namespace App\Crm;

use App\Models\Document;
use App\Models\Signature;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

/**
 * Saves a canvas signature (PNG data URL) on the private disk.
 */
class SignatureStore
{
    private const MAX_BYTES = 512 * 1024;

    public function store(Document $document, string $role, string $dataUrl, string $name, Request $request): Signature
    {
        $png = str_starts_with($dataUrl, 'data:image/png;base64,')
            ? base64_decode(substr($dataUrl, 22), true)
            : false;

        if ($png === false || ! str_starts_with($png, "\x89PNG\r\n\x1a\n") || strlen($png) > self::MAX_BYTES) {
            throw ValidationException::withMessages(['signature' => 'The signature could not be read. Please sign again.']);
        }

        $path = "signatures/{$document->id}/{$role}-".Str::uuid().'.png';
        Storage::disk('local')->put($path, $png);

        return $document->signatures()->create([
            'role' => $role,
            'path' => $path,
            'signed_name' => $name,
            'signed_at' => now(),
            'ip' => $request->ip(),
            'user_agent' => Str::limit((string) $request->userAgent(), 500, ''),
        ]);
    }
}
