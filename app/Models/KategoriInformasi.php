<?php

namespace App\Models;

use App\Models\Informasi;
use App\Models\PerangkatDaerah;
use Illuminate\Database\Eloquent\Model;

class KategoriInformasi extends Model
{
    //
    protected $fillable = [
        'nama_kategori',
        'slug',
    ];
    public function perangkatDaerahs()
    {
        return $this->hasMany(PerangkatDaerah::class, 'kategori_informasi_id');
    }

    public function informasis()
    {
        return $this->hasMany(Informasi::class, 'kategori_informasi_id');
    }
}
