<?php

namespace App\Services;

use App\Models\Gejala;
use App\Models\Penyakit;

class VCIRS
{
    public function diagnose(array $selectedGejalaIds): array
    {
        // Ambil semua penyakit beserta relasi gejala + bobot pakar
        $penyakits = Penyakit::with(['gejalas' => function ($q) {
            $q->orderBy('penyakit_gejala.id');
        }])->get();

        // NS = banyaknya penyakit yang memiliki gejala tersebut
        $nsPerGejala = Gejala::withCount('penyakits')->pluck('penyakits_count', 'id');

        $hasil = [];

        foreach ($penyakits as $penyakit) {
            $gejalas = $penyakit->gejalas;

            if ($gejalas->isEmpty()) {
                continue;
            }

            // TV = jumlah gejala pada penyakit ini
            $tv = max(1, $gejalas->count());

            // 1. Susun data dasar gejala: credit, NS, CF pakar
            $temp = [];
            foreach ($gejalas as $g) {
                $gejalaId = $g->id;

                $credit = in_array($gejalaId, $selectedGejalaIds) ? 1.0 : 0.0;
                $ns = max(1, (int) ($nsPerGejala[$gejalaId] ?? 1));
                $cfPakar = (float) ($g->pivot->bobot ?? 0);

                $temp[] = [
                    'id'       => $gejalaId,
                    'model'    => $g,
                    'credit'   => $credit,
                    'ns'       => $ns,
                    'cf_pakar' => $cfPakar,
                ];
            }

            // 2. Urutkan berdasarkan NS ASCENDING, lalu id gejala
            usort($temp, function ($a, $b) {
                if ($a['ns'] === $b['ns']) {
                    return $a['id'] <=> $b['id'];
                }
                return $a['ns'] <=> $b['ns'];
            });

            // 3. Hitung VO, CD, Weight, VUR
            $vurList = [];
            $sumVur = 0.0;

            foreach ($temp as $index => $row) {
                $vo = $index + 1;                // VO
                $cd = $vo / $tv;                 // CD = VO / TV
                $weight = $row['ns'] * $cd;      // Weight = NS × CD
                $vur = $row['credit'] * $weight; // VUR = Credit × Weight

                $sumVur += $vur;

                $vurList[$row['id']] = [
                    'gejala'   => $row['model'],
                    'credit'   => $row['credit'],
                    'ns'       => $row['ns'],
                    'vo'       => $vo,
                    'cd'       => $cd,
                    'weight'   => $weight,
                    'vur'      => $vur,
                    'cf_pakar' => $row['cf_pakar'],
                    'tv'       => $tv, // DITAMBAHKAN
                ];
            }

            // 4. Hitung NUR dan RUR sesuai jurnal
            //    NUR = Σ VUR / TV
            //    RUR = NUR / TV
            $nur = $sumVur / $tv;
            $rur = $nur / $tv;

            // 5. Hitung CF kombinasi
            $cfCombined = 0.0;
            $cfFirst = true;

            foreach ($vurList as $item) {
                if ($item['credit'] <= 0 || $item['cf_pakar'] <= 0) {
                    continue;
                }

                // CF gejala berdasarkan pakar
                $cfHe = $item['cf_pakar'] * $item['credit'];

                // Diberi bobot RUR
                $cfWeighted = $cfHe * $rur;

                if ($cfFirst) {
                    $cfCombined = $cfWeighted;
                    $cfFirst = false;
                } else {
                    $cfCombined = $cfCombined + ($cfWeighted * (1.0 - $cfCombined));
                }
            }

            // 6. Konversi ke persen
            $persen = round($cfCombined * 100, 2);

            if ($persen > 0) {
                $hasil[$penyakit->id] = [
                    'penyakit' => $penyakit,
                    'percent'  => $persen,
                    'tv'       => $tv,
                    'nur'      => $nur,
                    'rur'      => $rur,
                    'detail'   => $vurList,
                ];
            }
        }

        // Urutkan penyakit berdasarkan persen tertinggi
        uasort($hasil, fn ($a, $b) => $b['percent'] <=> $a['percent']);

        return $hasil;
    }
}
