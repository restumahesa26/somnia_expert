<?php

namespace App\Http\Controllers;

use App\Models\Konsultasi;
use App\Models\Gejala;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $isAdmin = $user->is_admin;

        if ($isAdmin) {
            // =======================
            // ===== DASHBOARD ADMIN =
            // =======================

            // Kartu ringkas
            $totalUsers = User::count();
            $totalDiagnoses = Konsultasi::count();
            $todayDiagnoses = Konsultasi::whereDate('created_at', Carbon::today())->count();

            // High risk (top result percent >= 80)
            $all = Konsultasi::select(['id','user_id','hasil','created_at'])->latest()->get();
            $highRiskCount = $all->filter(function ($item) {
                $top = collect($item->hasil)->first();
                $p = $top ? ($top['percent'] ?? 0) : 0;
                return $p >= 80;
            })->count();

            // Riwayat 10 terbaru semua user
            $recentDiagnoses = Konsultasi::with('user')->latest()->take(10)->get();

            // Distribusi severity dari 50 data terbaru
            $latest50 = Konsultasi::latest()->take(50)->get();
            $diagnosisDistribution = $latest50->map(function ($d) {
                $top = collect($d->hasil)->first();
                $percent = $top ? ($top['percent'] ?? 0) : 0;
                $severity = $percent >= 80 ? 'Tinggi' : ($percent >= 50 ? 'Sedang' : 'Rendah');
                return ['severity' => $severity];
            })->groupBy('severity')->map(function ($group) use ($latest50) {
                return [
                    'count' => $group->count(),
                    'percentage' => $latest50->count() ? round(($group->count() / $latest50->count()) * 100, 2) : 0
                ];
            });

            // Tren 7 hari: rata-rata persentase per hari
            $dates = collect(range(0,6))->map(fn($i) => Carbon::today()->subDays(6 - $i));
            $chartDates = $dates->map(fn($d) => $d->format('d M'))->all();
            $chartSeverity = $dates->map(function ($date) {
                $dayData = Konsultasi::whereDate('created_at', $date)->get();
                if ($dayData->isEmpty()) return 0;
                $avg = $dayData->map(function ($d) {
                    $top = collect($d->hasil)->first();
                    return $top ? ($top['percent'] ?? 0) : 0;
                })->avg();
                return round($avg, 2);
            })->all();

            return view('pages.dashboard_admin', [
                'totalUsers' => $totalUsers,
                'totalDiagnoses' => $totalDiagnoses,
                'highRiskCount' => $highRiskCount,
                'todayDiagnoses' => $todayDiagnoses,
                'diagnosisDistribution' => $diagnosisDistribution,
                'chartDates' => $chartDates,
                'chartSeverity' => $chartSeverity,
                'recentDiagnoses' => $recentDiagnoses,
            ]);
        }

        // =======================
        // ===== DASHBOARD USER ==
        // =======================
        // (logika existing milikmu disatukan di sini)

        // 10 diagnosa terakhir user (RAW - Eloquent)
        $recentDiagnosesRaw = Konsultasi::where('user_id', $user->id)
            ->latest()
            ->take(10)
            ->get();

        // Pakai RAW untuk last diagnosis & trend (karena butuh akses $model->hasil)
        $lastDiagnosis = $recentDiagnosesRaw->first();
        $lastResult = null;
        $lastCF = 0;
        $lastDiagnosisDate = null;
        $lastWeekTrend = 0;
        $currentStatus = 'Aman';
        $currentStatusTrend = 0;

        if ($lastDiagnosis) {
            $lastResult = collect($lastDiagnosis->hasil)->first();
            $lastCF = $lastResult ? ($lastResult['percent'] ?? 0) : 0;
            $lastDiagnosisDate = $lastDiagnosis->created_at;

            $sevenDaysAgo = Konsultasi::where('user_id', $user->id)
                ->where('created_at', '<=', now()->subDays(7))
                ->latest()
                ->first();

            if ($sevenDaysAgo) {
                $oldResult = collect($sevenDaysAgo->hasil)->first();
                $oldCF = $oldResult ? ($oldResult['percent'] ?? 0) : 0;
                $lastWeekTrend = $lastCF - $oldCF;
                $currentStatusTrend = $lastWeekTrend;
            }

            if ($lastCF >= 80) $currentStatus = 'Berisiko';
            elseif ($lastCF >= 50) $currentStatus = 'Waspada';
        }

        // BARU: mapping untuk tampilan tabel di Blade (stdClass)
        $recentDiagnoses = $recentDiagnosesRaw->map(function($d) use ($user) {
            $top = collect($d->hasil)->first();
            $percent = $top ? ($top['percent'] ?? 0) : 0;
            $severity = $percent >= 80 ? 'Tinggi' : ($percent >= 50 ? 'Sedang' : 'Rendah');

            return (object)[
                'id'             => $d->id,
                'created_at'     => $d->created_at,
                'nama_user'      => $user->name,
                'gangguan'       => $top ? ($top['nama'] ?? ($top['name'] ?? 'Tidak terdeteksi')) : 'Tidak terdeteksi',
                'severity_level' => $severity,
                'percent'        => $percent,
                'status_color'   => $percent >= 80 ? 'danger' : ($percent >= 50 ? 'warning' : 'success'),
                'is_admin_input' => $d->is_admin_input,
            ];
        });

        // Statistik personal
        $totalDiagnoses = $recentDiagnoses->count(); // pakai hasil mapping saja

        // 🔧 FIX: Distribusi severity TIDAK lagi akses $diagnosis->hasil
        $diagnosisDistribution = $recentDiagnoses
            ->groupBy('severity_level')
            ->map(function($group) use ($totalDiagnoses) {
                return [
                    'count' => $group->count(),
                    'percentage' => $totalDiagnoses ? round(($group->count() / $totalDiagnoses) * 100, 2) : 0
                ];
            });

        // Tren personal (7 entri terakhir) – tetap pakai RAW karena butuh $d->hasil
        $trendData = Konsultasi::where('user_id', $user->id)
            ->latest()
            ->take(7)
            ->get()
            ->reverse();

        $chartDates = $trendData->map(fn($d) => \Carbon\Carbon::parse($d->created_at)->format('d M'))->values();
        $chartSeverity = $trendData->map(function ($d) {
            $top = collect($d->hasil)->first();
            return $top ? ($top['percent'] ?? 0) : 0;
        })->values();

        return view('pages.dashboard', [
            'totalDiagnoses' => $totalDiagnoses,
            'diagnosisDistribution' => $diagnosisDistribution,
            'chartDates' => $chartDates,
            'chartSeverity' => $chartSeverity,
            'recentDiagnoses' => $recentDiagnoses,
            'user' => $user,
            'lastResult' => $lastResult,
            'lastCF' => $lastCF,
            'lastWeekTrend' => $lastWeekTrend,
            'lastDiagnosisDate' => $lastDiagnosisDate,
            'currentStatus' => $currentStatus,
            'currentStatusTrend' => $currentStatusTrend
        ]);
    }


    private function getSeverityLevel($percent)
    {
        if ($percent >= 80) return 'Tinggi';
        if ($percent >= 50) return 'Sedang';
        return 'Rendah';
    }

    private function getStatusColor($percent)
    {
        if ($percent >= 80) return 'danger';
        if ($percent >= 50) return 'warning';
        return 'success';
    }
}
