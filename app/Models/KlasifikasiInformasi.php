<?php

namespace App\Models;

use App\Models\Informasi;
use Illuminate\Database\Eloquent\Model;

class KlasifikasiInformasi extends Model
{
    //
    protected $fillable = [
        'nama_klasifikasi',
        'slug',
    ];

    public function informasis()
    {
        return $this->hasMany(Informasi::class, 'klasifikasi_informasi_id');
    }
}
