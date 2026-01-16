<?php

namespace App\Models;

use App\Models\Jabatan;
use App\Models\PerangkatDaerah;
use Illuminate\Database\Eloquent\Model;

class Pejabat extends Model
{
    //
    protected $fillable = [
        'nama_kepala',
        'nip',
        'pangkat_kepala',
        'jabatan_id',
        'perangkat_daerah_id', // Changed from opd_id
    ];

    public function jabatan()
    {
        return $this->belongsTo(Jabatan::class, 'jabatan_id');
    }

    public function perangkatDaerah()
    {
        return $this->belongsTo(PerangkatDaerah::class, 'perangkat_daerah_id');
    }
}
