<?php

namespace App\Models;

use App\Models\Informasi;
use Illuminate\Database\Eloquent\Model;

class KategoriJenisInformasi extends Model
{
    //
    protected $fillable = [
        'nama_kategori',
        'slug',
    ];

    public function informasis()
    {
        return $this->hasMany(Informasi::class, 'kategori_jenis_informasi_id');
    }
}
