<?php

namespace App\Http\Controllers\Crm;

use App\Http\Controllers\Controller;
use App\Models\Photo;
use App\Models\WorkOrder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\StreamedResponse;

class PhotoController extends Controller
{
    public function store(Request $request, WorkOrder $job): RedirectResponse
    {
        $data = $request->validate([
            'photos' => ['required', 'array', 'max:10'],
            'photos.*' => ['file', 'mimetypes:image/jpeg,image/png,image/webp,image/heic,image/heif', 'max:20480'],
            'kind' => ['required', Rule::in(array_keys(Photo::KINDS))],
            'caption' => ['nullable', 'string', 'max:255'],
        ]);

        foreach ($data['photos'] as $file) {
            $job->photos()->create([
                ...Photo::storeUpload($file, "photos/{$job->id}"),
                'kind' => $data['kind'],
                'caption' => $data['caption'] ?? null,
                'source' => 'technician',
            ]);
        }

        return back()->with('flash.banner', count($data['photos']) === 1 ? 'Photo saved.' : count($data['photos']).' photos saved.');
    }

    public function show(Photo $photo): StreamedResponse
    {
        abort_unless(Storage::disk('local')->exists($photo->path), 404);

        return Storage::disk('local')->response($photo->path, null, [
            'Content-Type' => $photo->mime,
            'Cache-Control' => 'private, max-age=86400',
        ]);
    }

    public function destroy(Photo $photo): RedirectResponse
    {
        // Soft delete: the file stays on disk with the job's records.
        $photo->delete();

        return back()->with('flash.banner', 'Photo removed.');
    }
}
