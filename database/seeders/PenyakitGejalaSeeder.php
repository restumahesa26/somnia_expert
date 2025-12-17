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

        // Helper function updated to accept Key Codes
        $seedPenyakit = function($penyakitId, $dataGejala, $keyCodes = []) use ($getGejalaId) {
            foreach ($dataGejala as $kodeGejala => $bobot) {
                PenyakitGejala::updateOrCreate(
                    [
                        'penyakit_id' => $penyakitId,
                        'gejala_id' => $getGejalaId($kodeGejala)
                    ],
                    [
                        'bobot' => $bobot,
                        // Set is_kunci based on specific list
                        'is_kunci' => in_array($kodeGejala, $keyCodes)
                    ]
                );
            }
        };

        // =========================
        // P01 - Insomnia
        // Keys: G01, G02, G03
        // =========================
        $p01 = $getPenyakitId('P01');
        $insomniaKeys = ['G01', 'G02', 'G03'];
        $insomnia = [
            'G01' => 0.95,
            'G02' => 0.95,
            'G03' => 0.95,
            'G04' => 0.90,
            'G11' => 0.90, 
            'G05' => 0.85,
            'G06' => 0.85,
            'G07' => 0.80,
            'G08' => 0.80,
            'G09' => 0.75,
            'G10' => 0.70,
        ];
        $seedPenyakit($p01, $insomnia, $insomniaKeys);

        // =========================
        // P02 - Obstructive Sleep Apnea (OSA)
        // Keys: G12, G13, G14, G15
        // =========================
        $p02 = $getPenyakitId('P02');
        $osaKeys = ['G12', 'G13', 'G14', 'G15'];
        $osa = [
            'G12' => 1.0, 
            'G13' => 0.95, 
            'G14' => 0.95, 
            'G15' => 0.95,
            'G02' => 0.90, // Sering terbangun
            'G19' => 0.90, 
            'G20' => 0.85, 
            'G11' => 0.85,
            'G05' => 0.80,
            'G18' => 0.80,
            'G06' => 0.80,
            'G22' => 0.80,
            'G16' => 0.80,
            'G21' => 0.75,
        ];
        $seedPenyakit($p02, $osa, $osaKeys);

        // =========================
        // P03 - Hipersomnia
        // Keys: G23, G24, G25
        // =========================
        $p03 = $getPenyakitId('P03');
        $hipersomniaKeys = ['G23', 'G24', 'G25'];
        $hipersomnia = [
            'G23' => 0.95,
            'G24' => 0.95, 
            'G25' => 0.95, // Naik jadi Kunci (sebelumnya 0.90 atau 0.95)
            
            'G11' => 0.95, // High weight tapi BUKAN kunci di fase awal (berdasar request)
            'G32' => 0.90,
            'G26' => 0.90,
            'G27' => 0.90,
            'G30' => 0.85,
            'G05' => 0.85,
            'G06' => 0.85,
            'G22' => 0.85,
            'G31' => 0.80,
            'G29' => 0.80,
            'G33' => 0.80,
            'G34' => 0.80,
            'G17' => 0.75,
            'G08' => 0.75,
            'G28' => 0.75,
        ];
        $seedPenyakit($p03, $hipersomnia, $hipersomniaKeys);

        // =========================
        // P04 - Sleepwalking (Parasomnia)
        // Keys: G35, G37, G36, G38
        // =========================
        $p04 = $getPenyakitId('P04');
        $sleepwalkingKeys = ['G35', 'G37', 'G36', 'G38'];
        $sleepwalking = [
            'G35' => 0.95,
            'G37' => 0.95, 
            'G36' => 0.95, 
            'G38' => 0.90, // Tetap 0.90 bobotnya, tapi jadi KEY
            'G39' => 0.85,
            'G40' => 0.85,
            'G16' => 0.85,
            'G41' => 0.80,
            'G05' => 0.80,
            'G11' => 0.75,
        ];
        $seedPenyakit($p04, $sleepwalking, $sleepwalkingKeys);

        // =========================
        // P05 - Gangguan Ritme Sirkadian
        // Keys: G42, G43, G44
        // =========================
        $p05 = $getPenyakitId('P05');
        $sirkadianKeys = ['G42', 'G43', 'G44'];
        $sirkadian = [
            'G42' => 0.95,
            'G43' => 0.95,
            'G44' => 0.90, // Bobot 0.90 tapi KEY
            
            'G45' => 0.90,
            'G46' => 0.85,
            'G47' => 0.85,
            'G06' => 0.80,
            'G16' => 0.80,
            'G05' => 0.80,
        ];
        $seedPenyakit($p05, $sirkadian, $sirkadianKeys);
    }
}