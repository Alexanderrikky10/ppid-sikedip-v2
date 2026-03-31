<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class PermohonanInformasi extends Model
{
    use SoftDeletes, LogsActivity;
    //
    protected $fillable = [
        'no_registrasi',
        'perangkat_daerah_id',
        'nama_pemohon',
        'jenis_permohonan',
        'tanggal_lahir',
        'jenis_kelamin',
        'no_identitas',
        'scan_identitas', // Path file KTP
        'dokumen_tambahan_path', // Path file tambahan
        'alamat_lengkap',
        'nomor_fax',
        'nomor_whatsapp',
        'alamat_email',
        'informasi_diminta',
        'alasan_permintaan',
        'cara_penyampaian_informasi',
        'tindak_lanjut',
        'status',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['nama_pemohon', 'no_identitas', 'status']) // kolom yang direkam
            ->logOnlyDirty()  // hanya rekam yang berubah
            ->dontSubmitEmptyLogs()
            ->setDescriptionForEvent(fn(string $eventName) => "Permohonan Informasi di-{$eventName}");
    }

    // relasi dengan model perangkat daerah 
    public function perangkatDaerah()
    {
        return $this->belongsTo(PerangkatDaerah::class, 'perangkat_daerah_id');
    }

    //relasi dengan model keberatan informasi
    public function keberatanInformasi()
    {
        return $this->hasMany(KeberatanInformasi::class, 'permohonan_informasi_id');
    }
}
