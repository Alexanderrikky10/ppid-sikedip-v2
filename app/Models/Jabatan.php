<?php

namespace App\Models;

use App\Models\Pejabat;
use Illuminate\Database\Eloquent\Model;

class Jabatan extends Model
{
    //
    protected $fillable = [
        'nama_jabatan',
        'kode_jabatan',
        'slug',
    ];

    public function pejabat()
    {
        return $this->hasMany(Pejabat::class, 'jabatan_id');
    }
}
