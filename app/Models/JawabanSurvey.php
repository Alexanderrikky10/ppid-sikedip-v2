<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JawabanSurvey extends Model
{
    //
    protected $table = 'jawaban_surveys';
    protected $fillable = ['responden_id', 'survey_id', 'jawaban'];

    public function responden()
    {
        return $this->belongsTo(Responden::class, 'responden_id', 'id');
    }

    public function surveyKualitas()
    {
        return $this->belongsTo(SurveyKualitas::class, 'survey_id', 'id');
    }
}