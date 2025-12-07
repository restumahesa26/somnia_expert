<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Penyakit;
use App\Models\Gejala;
use App\Models\PenyakitGejala;

class PenyakitGejalaSeeder extends Seeder
{
    public function run()
    {
        // Helper: ambil ID penyakit via kode
        $getPenyakitId = fn($kode) => Penyakit::where('kode_penyakit', $kode)->value('id');
        // Helper: ambil ID gejala via kode
        $getGejalaId   = fn($kode) => Gejala::where('kode_gejala', $kode)->value('id');

        // Helper function untuk memproses data agar lebih rapi
        // Otomatis set is_kunci = true jika bobot >= 0.85
        $seedPenyakit = function($penyakitId, $dataGejala) use ($getGejalaId) {
            foreach ($dataGejala as $kodeGejala => $bobot) {
                PenyakitGejala::updateOrCreate(
                    [
                        'penyakit_id' => $penyakitId,
                        'gejala_id' => $getGejalaId($kodeGejala)
                    ],
                    [
                        'bobot' => $bobot,
                        'is_kunci' => $bobot >= 0.85 // Logika penentuan gejala kunci
                    ]
                );
            }
        };

        // =========================
        // P01 - Insomnia
        // =========================
        $p01 = $getPenyakitId('P01');
        $insomnia = [
            'G01' => 0.85, // Kunci
            'G02' => 0.85, // Kunci
            'G03' => 0.85, // Kunci
            'G04' => 0.7,
            'G11' => 0.7,
            'G05' => 0.5,
            'G06' => 0.5,
            'G07' => 0.5,
            'G08' => 0.2,
            'G09' => 0.2,
            'G10' => 0.2,
        ];
        $seedPenyakit($p01, $insomnia);

        // =========================
        // P02 - Obstructive Sleep Apnea (OSA)
        // =========================
        $p02 = $getPenyakitId('P02');
        $osa = [
            'G12' => 1.0,  // Kunci
            'G13' => 0.85, // Kunci
            'G14' => 0.85, // Kunci
            'G15' => 0.85, // Kunci
            'G02' => 0.7,
            'G19' => 0.7,
            'G20' => 0.7,
            'G11' => 0.5,
            'G05' => 0.5,
            'G18' => 0.5,
            'G06' => 0.5,
            'G22' => 0.5,
            'G16' => 0.2,
            'G21' => 0.2,
            'G17' => 0.2,
        ];
        $seedPenyakit($p02, $osa);

        // =========================
        // P03 - Hipersomnia
        // =========================
        $p03 = $getPenyakitId('P03');
        $hipersomnia = [
            'G23' => 0.85, // Kunci
            'G11' => 0.85, // Kunci
            'G24' => 0.85, // Kunci
            'G25' => 0.7,
            'G32' => 0.7,
            'G26' => 0.7,
            'G30' => 0.5,
            'G27' => 0.5,
            'G05' => 0.5,
            'G06' => 0.5,
            'G22' => 0.5,
            'G31' => 0.2,
            'G29' => 0.2,
            'G33' => 0.2,
            'G34' => 0.2,
            'G08' => 0.2,
            'G28' => 0.2,
        ];
        $seedPenyakit($p03, $hipersomnia);

        // =========================
        // P04 - Sleepwalking (Parasomnia)
        // =========================
        $p04 = $getPenyakitId('P04');
        $sleepwalking = [
            'G35' => 0.85, // Kunci
            'G37' => 0.85, // Kunci
            'G36' => 0.85, // Kunci
            'G38' => 0.7,
            'G39' => 0.7,
            'G40' => 0.7,
            'G16' => 0.5,
            'G41' => 0.5,
            'G05' => 0.2,
            'G11' => 0.2,
        ];
        $seedPenyakit($p04, $sleepwalking);

        // =========================
        // P05 - Gangguan Ritme Sirkadian
        // =========================
        $p05 = $getPenyakitId('P05');
        $sirkadian = [
            'G42' => 0.85, // Kunci
            'G43' => 0.85, // Kunci
            'G44' => 0.85, // Kunci
            'G45' => 0.7,
            'G46' => 0.7,
            'G47' => 0.7,
            'G06' => 0.5,
            'G16' => 0.5,
            'G05' => 0.5,
        ];
        $seedPenyakit($p05, $sirkadian);
    }
}
