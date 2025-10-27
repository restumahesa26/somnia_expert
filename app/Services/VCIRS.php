<?php
namespace App\Services;

use App\Models\Gejala;
use App\Models\Penyakit;

class VCIRS
{
    public function diagnose(array $selectedGejalaIds): array
    {
        // Ambil semua penyakit beserta gejala & bobot
        $penyakits = Penyakit::with(['gejalas' => function($q) {
            $q->orderBy('penyakit_gejala.id');
        }])->get();

        $nsPerGejala = Gejala::withCount('penyakits')->pluck('penyakits_count','id');

        $hasil = [];

        foreach ($penyakits as $penyakit) {
            $gejalas = $penyakit->gejalas;
            $tv = max(1, $gejalas->count());

            // Hitung VUR untuk tiap gejala
            $vurList = [];
            foreach ($gejalas as $index => $g) {
                $gejalaId = $g->id;
                // Ubah credit menjadi bobot dari input user
                $credit = in_array($gejalaId, $selectedGejalaIds) ? 1.0 : 0.0;

                $ns = max(1, (int)($nsPerGejala[$gejalaId] ?? 1));
                $vo = $index + 1;
                $cd = $vo / $tv;

                $weight = $ns * $cd;
                $vur = $credit * $weight;

                $vurList[$gejalaId] = [
                    'credit' => $credit,
                    'ns' => $ns,
                    'vo' => $vo,
                    'tv' => $tv,
                    'cd' => $cd,
                    'weight' => $weight,
                    'vur' => $vur,
                    // Ambil bobot dari pivot table sebagai CF pakar
                    'cf_pakar' => (float)$g->pivot->bobot,
                ];
            }

            // Hitung NUR & RUR
            $sumVur = array_sum(array_column($vurList, 'vur'));
            $nur = $sumVur / $tv;
            $rur = $nur;

            // Hitung CF kombinasi dengan metode CF
            $cfCombined = 0.0;
            $cfFirst = true;

            foreach ($vurList as $gejalaId => $it) {
                if ($it['credit'] <= 0) continue;

                $cfHe = $it['cf_pakar'] * $it['credit'];
                $cfWeighted = $cfHe * $rur;

                // Gabungkan CF dengan metode kombinasi
                if ($cfFirst) {
                    $cfCombined = $cfWeighted;
                    $cfFirst = false;
                } else {
                    $cfCombined = $cfCombined + ($cfWeighted * (1.0 - $cfCombined));
                }
            }

            // Hitung persentase akhir
            $persen = round($cfCombined * 100, 2);

            // Hanya masukkan ke hasil jika ada gejala yang cocok
            if ($persen > 0) {
                $hasil[$penyakit->id] = [
                    'penyakit' => $penyakit,
                    'percent' => $persen,
                    'rur' => $rur,
                    'detail' => $vurList,
                ];
            }
        }

        // Urutkan berdasarkan persentase tertinggi
        uasort($hasil, fn($a, $b) => $b['percent'] <=> $a['percent']);

        return $hasil;
    }
}
