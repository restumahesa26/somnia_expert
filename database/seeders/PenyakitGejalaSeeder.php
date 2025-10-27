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

        // =========================
        // P01 - Insomnia (contoh)
        // =========================
        $p01 = $getPenyakitId('P01');
        $insomnia = [
            'G01' => 1.00,
            'G02' => 0.91,
            'G03' => 0.82,
            'G04' => 0.73,
            'G05' => 0.64,
            'G06' => 0.56,
            'G07' => 0.47,
            'G08' => 0.38,
            'G09' => 0.29,
            'G10' => 0.20,
        ];
        foreach ($insomnia as $kodeGejala => $bobot) {
            PenyakitGejala::updateOrCreate(
                ['penyakit_id' => $p01, 'gejala_id' => $getGejalaId($kodeGejala)],
                ['bobot' => $bobot]
            );
        }

        // =========================
        // P02 - OSA (lengkapi sesuai PDF kamu)
        // =========================
        $p02 = $getPenyakitId('P02');
        $osa = [
            'G11' => 1.00,
            'G12' => 0.90,
            'G13' => 0.80,
            'G14' => 0.70,
            'G15' => 0.60,
            'G16' => 0.50, // dst jika ada di referensi kamu
        ];
        foreach ($osa as $kodeGejala => $bobot) {
            PenyakitGejala::updateOrCreate(
                ['penyakit_id' => $p02, 'gejala_id' => $getGejalaId($kodeGejala)],
                ['bobot' => $bobot]
            );
        }

        // =========================
        // P03 - Hipersomnia (lengkapi)
        // =========================
        $p03 = $getPenyakitId('P03');
        $hipersomnia = [
            'G17' => 1.00,
            'G18' => 0.90,
            'G19' => 0.80,
            'G20' => 0.70,
            'G21' => 0.60,
            'G23' => 0.50
        ];
        foreach ($hipersomnia as $kodeGejala => $bobot) {
            PenyakitGejala::updateOrCreate(
                ['penyakit_id' => $p03, 'gejala_id' => $getGejalaId($kodeGejala)],
                ['bobot' => $bobot]
            );
        }

        // =========================
        // P04 - Sleepwalking (lengkapi)
        // =========================
        $p04 = $getPenyakitId('P04');
        $sleepwalking = [
            'G24'=>1.00,
            'G25'=>0.90,
            'G26'=>0.85,
            'G27'=>0.75,
            'G28'=>0.70,
            'G29'=>0.65,
            'G30'=>0.60,
            'G31'=>0.55,
            'G32'=>0.50
        ];
        foreach ($sleepwalking as $kodeGejala => $bobot) {
            PenyakitGejala::updateOrCreate(
                ['penyakit_id' => $p04, 'gejala_id' => $getGejalaId($kodeGejala)],
                ['bobot' => $bobot]
            );
        }

        // =========================
        // P05 - Gangguan Ritme Sirkadian (lengkapi)
        // =========================
        $p05 = $getPenyakitId('P05');
        $sirkadian = [
            'G33'=>1.00,
            'G34'=>0.90,
            'G35'=>0.85,
            'G36'=>0.80,
            'G37'=>0.75,
            'G38'=>0.70
        ];
        foreach ($sirkadian as $kodeGejala => $bobot) {
            PenyakitGejala::updateOrCreate(
                ['penyakit_id' => $p05, 'gejala_id' => $getGejalaId($kodeGejala)],
                ['bobot' => $bobot]
            );
        }
    }
}
