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
            'G01' => 0.85,
            'G02' => 0.85,
            'G03' => 0.85,
            'G04' => 0.7,
            'G11' => 0.7,
            'G05' => 0.5,
            'G06' => 0.5,
            'G07' => 0.5,
            'G08' => 0.2,
            'G09' => 0.2,
            'G10' => 0.2,
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
            'G12' => 1.0,
            'G13' => 0.85,
            'G14' => 0.85,
            'G15' => 0.85,
            'G02' => 0.7, // G2 di source
            'G19' => 0.7,
            'G20' => 0.7,
            'G11' => 0.5,
            'G05' => 0.5, // G5 di source
            'G18' => 0.5,
            'G06' => 0.5, // G6 di source
            'G22' => 0.5,
            'G16' => 0.2,
            'G21' => 0.2,
            'G17' => 0.2,
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
            'G23' => 0.85,
            'G11' => 0.85,
            'G24' => 0.85,
            'G25' => 0.7,
            'G32' => 0.7,
            'G26' => 0.7,
            'G30' => 0.5,
            'G27' => 0.5,
            'G05' => 0.5, // G5 di source
            'G06' => 0.5, // G6 di source
            'G22' => 0.5,
            'G31' => 0.2,
            'G29' => 0.2,
            'G33' => 0.2,
            'G34' => 0.2,
            'G08' => 0.2, // G8 di source
            'G28' => 0.2,
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
            'G35' => 0.85,
            'G37' => 0.85,
            'G36' => 0.85,
            'G38' => 0.7,
            'G39' => 0.7,
            'G40' => 0.7,
            'G16' => 0.5,
            'G41' => 0.5,
            'G05' => 0.2, // G5 di source
            'G11' => 0.2,
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
            'G42' => 0.85,
            'G43' => 0.85,
            'G44' => 0.85,
            'G45' => 0.7,
            'G46' => 0.7,
            'G47' => 0.7,
            'G06' => 0.5, // G6 di source
            'G16' => 0.5,
            'G05' => 0.5, // G5 di source
        ];
        foreach ($sirkadian as $kodeGejala => $bobot) {
            PenyakitGejala::updateOrCreate(
                ['penyakit_id' => $p05, 'gejala_id' => $getGejalaId($kodeGejala)],
                ['bobot' => $bobot]
            );
        }
    }
}
