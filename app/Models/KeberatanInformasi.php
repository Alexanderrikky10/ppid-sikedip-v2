<?php

namespace App\Models;

use App\Models\PermohonanInformasi;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class KeberatanInformasi extends Model
{
    //
    use SoftDeletes;
    protected $fillable = [
        'nik_pemohon',
        'permohonan_informasi_id',
        'tujuan_penggunaan_informasi',
        'nama_pemohon',
        'pekerjaan',
        'alamat_pemohon',
        'telepon_pemohon',
        'nama_kuasa',
        'alamat_kuasa',
        'telepon_kuasa',
        'surat_kuasa',
        'alasan_keberatan',
        'status',
    ];

    protected $casts = [
        'alasan_keberatan' => 'array',
    ];

    public function permohonanInformasi()
    {
        return $this->belongsTo(PermohonanInformasi::class, 'permohonan_informasi_id');
    }
}
