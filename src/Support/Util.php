<?php

namespace MeaCms\Menu\Support;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class Util
{
    /**
     * Generate a publicly accessible URL for an image stored on the public disk.
     */
    public static function pictureUrl(?string $filename): ?string
    {
        if ($filename && Storage::disk('public')->exists($filename)) {
            return asset('storage/' . ltrim($filename, '/'));
        }

        return null;
    }

    /**
     * Convert an image/file to a Base64 data URI.
     */
    public static function fileBase64(?string $path, string $disk = 'public'): ?string
    {
        if (!$path) {
            return null;
        }

        $path = ltrim($path, '/');

        if (Storage::disk($disk)->exists($path)) {
            $contents = Storage::disk($disk)->get($path);
            $mime = Storage::disk($disk)->mimeType($path);

            return 'data:' . $mime . ';base64,' . base64_encode($contents);
        }

        $publicStoragePath = public_path('storage/' . $path);
        if (File::exists($publicStoragePath)) {
            $contents = File::get($publicStoragePath);
            $mime = File::mimeType($publicStoragePath);

            return 'data:' . $mime . ';base64,' . base64_encode($contents);
        }

        $publicPath = public_path($path);
        if (File::exists($publicPath)) {
            $contents = File::get($publicPath);
            $mime = File::mimeType($publicPath);

            return 'data:' . $mime . ';base64,' . base64_encode($contents);
        }

        return null;
    }

    /**
     * Convert a file size in bytes to a human-readable format.
     */
    public static function humanFileSize(int|float $bytes, int $decimals = 2): string
    {
        $sizeUnits = ['B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];

        $factor = floor((strlen((string) $bytes) - 1) / 3);

        return sprintf(
            "%.{$decimals}f",
            $bytes / pow(1024, $factor)
        ) . ' ' . $sizeUnits[$factor];
    }
}
