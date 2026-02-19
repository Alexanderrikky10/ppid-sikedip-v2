<?php

namespace App\Models;

use App\Models\JawabanSurvey;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Responden extends Model
{
    use HasFactory;

    protected $table = 'respondens';

    protected $fillable = [
        'nama_responden',
        'usia_responden',
        'pendidikan_responden',
        'no_telp_responden',
        'jenis_kelamin_responden',
        'pekerjaan_responden',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    // Relasi ke jawaban survey
    public function jawabanSurveys()
    {
        return $this->hasMany(JawabanSurvey::class, 'responden_id');
    }

    // Method untuk menghitung index kepuasan
    public function getIndexKepuasanAttribute()
    {
        $jawaban = $this->jawabanSurveys;

        if ($jawaban->isEmpty()) {
            return 0;
        }

        $totalNilai = 0;
        foreach ($jawaban as $item) {
            $nilai = match ($item->jawaban) {
                'Sangat Setuju' => 100,
                'Setuju' => 80,
                'Cukup' => 60,
                'Tidak Setuju' => 40,
                'Sangat Tidak Setuju' => 20,
                default => 0,
            };
            $totalNilai += $nilai;
        }

        return round($totalNilai / $jawaban->count(), 2);
    }
}
