<?php

namespace App\Http\Controllers\LayananInformasi;

use App\Http\Controllers\Controller;
use App\Models\Responden;
use App\Models\SurveyKualitas;
use App\Models\JawabanSurvey;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SurveyKualitasController extends Controller
{
    public function surveyKualitas()
    {
        // Ambil semua soal dari database
        $pertanyaan = SurveyKualitas::all();
        return view('content.layanan-informasi.survey-akses-kualitas', compact('pertanyaan'));
    }

    public function store(Request $request)
    {
        // 1. Validasi Input
        $request->validate([
            'nama' => 'required|string|max:255',
            'no_hp' => 'required',
            'usia' => 'required|numeric',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'pendidikan' => 'required',
            'pekerjaan' => 'required',
            'jawaban' => 'required|array', // Menampung array jawaban
        ]);

        try {
            DB::beginTransaction();

            // 2. Simpan Data Responden
            $responden = Responden::create([
                'nama_responden' => $request->nama,
                'usia_responden' => $request->usia,
                'pendidikan_responden' => $request->pendidikan,
                'no_telp_responden' => $request->no_hp,
                'jenis_kelamin_responden' => $request->jenis_kelamin,
                'pekerjaan_responden' => $request->pekerjaan,
            ]);

            // 3. Simpan Jawaban Survey
            foreach ($request->jawaban as $survey_id => $isi_jawaban) {
                JawabanSurvey::create([
                    'responden_id' => $responden->id,
                    'survey_id' => $survey_id,
                    'jawaban' => $isi_jawaban
                ]);
            }

            DB::commit();
            return redirect()->back()->with('success', 'Terima kasih! Jawaban Anda telah tersimpan.');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}