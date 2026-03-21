<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class WelcomePage extends Model
{
    protected $fillable = [
        'title',
        'sub_title',
        'description',
        'media',
        'media_slides',
        'is_active',
    ];

    protected $casts = [
        'media' => 'array',
        'media_slides' => 'array',
        'is_active' => 'boolean',
    ];

    /**
     * Generate temporary URLs untuk semua gambar slider (disk minio, private)
     * Return: array of URL string
     */
    public function getMediaUrlsAttribute(): array
    {
        if (empty($this->media))
            return [];

        return collect($this->media)->map(function ($path) {
            if (!$path)
                return null;

            // Jika sudah full URL
            if (str_starts_with($path, 'http'))
                return $path;

            try {
                // Disk minio private → pakai temporaryUrl
                return Storage::disk('minio')->temporaryUrl(
                    $path,
                    now()->addMinutes(60)
                );
            } catch (\Exception $e) {
                // Fallback jika minio tidak support temporaryUrl
                return Storage::disk('minio')->url($path);
            }

        })->filter()->values()->toArray();
    }

    /**
     * Return array teks slide: [["title" => "...", "text" => "..."], ...]
     */
    public function getSlidesTextAttribute(): array
    {
        if (empty($this->media_slides))
            return [];

        return collect($this->media_slides)->map(function ($item) {
            return [
                'title' => $item['title'] ?? '',
                'text' => $item['text'] ?? '',
            ];
        })->toArray();
    }
}