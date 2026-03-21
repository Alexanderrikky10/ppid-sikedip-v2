<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class BerandaContent extends Model
{
    protected $fillable = [
        'title',
        'description',
        'media',
        'is_active',
        // 'media_type', // Pastikan kolom ini ada jika ingin logika pemisah video/image
    ];

    protected $casts = [
        'media' => 'array',
        'is_active' => 'boolean',
    ];

    // Kita tidak perlu $appends jika hanya ingin memanggilnya secara manual
    // Tapi pastikan disk 'minio' sudah terkonfigurasi di config/filesystems.php
    public function getMediaUrlsAttribute()
    {
        if (!$this->media || !is_array($this->media))
            return [];

        return collect($this->media)->map(function ($path) {
            $cleanPath = str_replace('\\', '/', $path);

            // Gunakan temporaryUrl jika gambar tidak muncul dengan url() biasa
            // Ini akan memberikan akses selama 60 menit
            return Storage::disk('minio')->temporaryUrl($cleanPath, now()->addMinutes(60));
        })->all();
    }
}