<?php

namespace App\Models;

use App\Models\JawabanSurvey;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SurveyKualitas extends Model
{
    use HasFactory;

    protected $table = 'survey_kualitas';

    protected $fillable = [
        'pertanyaan',
    ];

    /**
     * Relasi ke jawaban survey
     */
    public function jawabanSurveys(): HasMany
    {
        return $this->hasMany(JawabanSurvey::class, 'survey_id');
    }

    /**
     * Get statistik jawaban untuk pertanyaan ini
     */
    public function getStatistikJawabanAttribute()
    {
        $jawaban = $this->jawabanSurveys;
        $total = $jawaban->count();

        if ($total === 0) {
            return [
                'total' => 0,
                'sangat_setuju' => 0,
                'setuju' => 0,
                'cukup' => 0,
                'tidak_setuju' => 0,
                'sangat_tidak_setuju' => 0,
                'rata_rata' => 0,
            ];
        }

        $stats = [
            'total' => $total,
            'sangat_setuju' => $jawaban->where('jawaban', 'Sangat Setuju')->count(),
            'setuju' => $jawaban->where('jawaban', 'Setuju')->count(),
            'cukup' => $jawaban->where('jawaban', 'Cukup')->count(),
            'tidak_setuju' => $jawaban->where('jawaban', 'Tidak Setuju')->count(),
            'sangat_tidak_setuju' => $jawaban->where('jawaban', 'Sangat Tidak Setuju')->count(),
        ];

        // Hitung rata-rata (1-5)
        $totalNilai = ($stats['sangat_setuju'] * 5) +
            ($stats['setuju'] * 4) +
            ($stats['cukup'] * 3) +
            ($stats['tidak_setuju'] * 2) +
            ($stats['sangat_tidak_setuju'] * 1);

        $stats['rata_rata'] = $totalNilai / $total;

        return $stats;
    }
}