<?php

namespace App\Models;

use App\Models\PerangkatDaerah;
use App\Models\KlasifikasiInformasi;
use App\Models\KategoriJenisInformasi;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Informasi extends Model
{
    //
    use SoftDeletes;

    protected $fillable = [
        'tahun',
        'perangkat_daerah_id', // opd_id diubah menjadi perangkat_daerah_id
        'klasifikasi_informasi_id', //klasifikasi_informasi_id
        'kategori_jenis_informasi_id', //kategori_jenis_id informasi
        'kategori_informasi_id',
        'judul_informasi',
        'ringkasan',
        'penjelasan',
        'pejabat_pj',
        'waktu_tempat',
        'pj_penerbit_informasi',
        'format_informasi',
        'waktu_penyimpanan',
        'media',
        'file_size_mb',
        'slug',
        'tanggal_publikasi',
        'views_count',
        'downloads_count',
    ];

    public function perangkatDaerah()
    {
        return $this->belongsTo(PerangkatDaerah::class, 'perangkat_daerah_id');
    }

    public function klasifikasiInformasi()
    {
        return $this->belongsTo(KlasifikasiInformasi::class, 'klasifikasi_informasi_id');
    }
    public function kategoriJenisInformasi()
    {
        return $this->belongsTo(KategoriJenisInformasi::class, 'kategori_jenis_informasi_id');
    }

    public function kategoriInformasi()
    {
        return $this->belongsTo(KategoriInformasi::class, 'kategori_informasi_id');
    }
    // Accessor untuk mengubah string format "PDF,Excel" menjadi Array
    public function getFormatArrayAttribute()
    {
        return $this->format_informasi ? array_map('trim', explode(',', $this->format_informasi)) : [];
    }

}




