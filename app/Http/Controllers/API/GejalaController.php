<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Gejala;
use App\Models\PenyakitGejala;
use Illuminate\Http\Request;

class GejalaController extends Controller
{
    public function getAvailableGejala($penyakitId)
    {
        // Ambil ID gejala yang sudah memiliki bobot untuk penyakit ini
        $existingGejalaIds = PenyakitGejala::where('penyakit_id', $penyakitId)
            ->pluck('gejala_id');

        // Ambil gejala yang belum memiliki bobot untuk penyakit ini
        $availableGejala = Gejala::whereNotIn('id', $existingGejalaIds)
            ->orderBy('nama_gejala')
            ->get(['id', 'nama_gejala']);

        return response()->json($availableGejala);
    }
}
