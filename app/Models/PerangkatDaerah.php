<?php

namespace App\Models;

use App\Models\Informasi;
use App\Models\KategoriInformasi;
use Illuminate\Database\Eloquent\Model;

class PerangkatDaerah extends Model
{
    //
    protected $fillable = [
        'nama_perangkat_daerah',
        'labele_perangkat_daerah',
        'kategori_informasi_id',
        'slug',
        'parent_id',
        'images',
    ];
    public function kategoriInformasi()
    {
        return $this->belongsTo(KategoriInformasi::class, 'kategori_informasi_id');
    }

    public function informasis()
    {
        return $this->hasMany(Informasi::class, 'perangkat_daerah_id');
    }

    public function parent()
    {
        return $this->belongsTo(PerangkatDaerah::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(PerangkatDaerah::class, 'parent_id');
    }

    public function PermohonanInformasis()
    {
        return $this->hasMany(PermohonanInformasi::class, 'perangkat_daerah_id');
    }

}
