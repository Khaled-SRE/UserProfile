<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FileUploadService
{
    public function upload(string $path, UploadedFile $file): array
    {
        $fileExt = $file->getClientOriginalExtension();
        $fileName = Str::random(15).'.'.$fileExt;

        // Store the file with a custom name
        $storedPath = $file->storeAs($path, $fileName, 'public');

        return [
            'url' => asset('storage/'.$storedPath),
            'path' => $storedPath,
            'name' => $file->getClientOriginalName(),
            'type' => $file->getClientMimeType(),
            'ext' => $fileExt,
        ];
    }

    public function remove(?string $path): bool
    {
        if (! $path || ! Storage::disk('public')->exists($path)) {
            return false;
        }

        return Storage::disk('public')->delete($path);
    }
}
