<?php

namespace App\Http\Controllers;

use App\Models\Konsultasi;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

            return view('pages.dashboard_admin');
        }

        // =======================
        // ===== DASHBOARD USER ==
        // =======================
        return view('pages.dashboard');
    }

    public function userData(Request $request)
    {
        $user = Auth::user();

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
            $lastDiagnosisDate = \Carbon\Carbon::parse($lastDiagnosis->created_at)->format('d M Y');

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

            if ($lastCF >= 80) {
                $currentStatus = 'Berisiko';
            } elseif ($lastCF >= 50) {
                $currentStatus = 'Waspada';
            }
        }

        // Mapping untuk tampilan tabel (JSON)
        $recentDiagnoses = $recentDiagnosesRaw->map(function ($d) {
            $top = collect($d->hasil)->first();
            $percent = $top ? ($top['percent'] ?? 0) : 0;
            $severity = $percent >= 80 ? 'Tinggi' : ($percent >= 50 ? 'Sedang' : 'Rendah');

            return [
                'id' => $d->id,
                'created_at' => \Carbon\Carbon::parse($d->created_at)->format('d M Y H:i'),
                'gangguan' => $top ? ($top['nama'] ?? ($top['name'] ?? 'Tidak terdeteksi')) : 'Tidak terdeteksi',
                'severity_level' => $severity,
                'percent' => $percent,
                'status_color' => $percent >= 80 ? 'danger' : ($percent >= 50 ? 'warning' : 'success'),
            ];
        });

        $totalDiagnoses = Konsultasi::where('user_id', $user->id)->count();

        // Distribusi severity
        $diagnosisDistribution = $recentDiagnoses
            ->groupBy('severity_level')
            ->map(function ($group) use ($totalDiagnoses) {
                return [
                    'count' => $group->count(),
                    'percentage' => $totalDiagnoses ? round(($group->count() / $totalDiagnoses) * 100, 2) : 0,
                ];
            });

        // Tren personal (7 entri terakhir)
        $trendData = Konsultasi::where('user_id', $user->id)
            ->latest()
            ->take(7)
            ->get()
            ->reverse();

        $chartDates = $trendData->map(fn ($d) => \Carbon\Carbon::parse($d->created_at)->format('d M'))->values();
        $chartSeverity = $trendData->map(function ($d) {
            $top = collect($d->hasil)->first();
            return $top ? ($top['percent'] ?? 0) : 0;
        })->values();

        return response()->json([
            'totalDiagnoses' => $totalDiagnoses,
            'diagnosisDistribution' => $diagnosisDistribution,
            'chartDates' => $chartDates,
            'chartSeverity' => $chartSeverity,
            'recentDiagnoses' => $recentDiagnoses,
            'lastResultName' => $lastResult ? ($lastResult['nama'] ?? ($lastResult['name'] ?? '-')) : '-',
            'lastCF' => round($lastCF, 2),
            'lastWeekTrend' => round($lastWeekTrend, 2),
            'lastDiagnosisDate' => $lastDiagnosisDate,
            'currentStatus' => $currentStatus,
            'currentStatusTrend' => round($currentStatusTrend, 2),
        ]);
    }

    private function getSeverityLevel($percent)
    {
        if ($percent >= 80) {
            return 'Tinggi';
        }
        if ($percent >= 50) {
            return 'Sedang';
        }

        return 'Rendah';
    }

    private function getStatusColor($percent)
    {
        if ($percent >= 80) {
            return 'danger';
        }
        if ($percent >= 50) {
            return 'warning';
        }

        return 'success';
    }

    public function adminData(Request $request)
    {
        if (! Auth::user()->is_admin) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $filter = $request->get('filter', '7_hari'); // default to 7 days for quick overview
        $startDate = Carbon::today()->subDays(6);
        $endDate = Carbon::today();

        if ($filter === '2_minggu') {
            $startDate = Carbon::today()->subDays(13);
        } elseif ($filter === '1_bulan') {
            $startDate = Carbon::today()->subDays(29);
        } elseif ($filter === 'custom') {
            $startDate = Carbon::parse($request->get('start_date', Carbon::today()->subDays(6)));
            $endDate = Carbon::parse($request->get('end_date', Carbon::today()));
        }

        $diffInDays = $startDate->diffInDays($endDate);

        // Adjust queries using dates
        // For total users, since it's global let's count all or filtered. The user didn't specify.
        // Let's count new users in this period and total users overall.
        $totalUsers = User::count();
        $totalDiagnoses = Konsultasi::whereBetween('created_at', [$startDate->startOfDay(), $endDate->endOfDay()])->count();
        $todayDiagnoses = Konsultasi::whereDate('created_at', Carbon::today())->count();

        // High risk (top result percent >= 80)
        $all = Konsultasi::whereBetween('created_at', [$startDate->startOfDay(), $endDate->endOfDay()])
            ->select(['id', 'user_id', 'hasil', 'created_at'])->latest()->get();

        $highRiskCount = $all->filter(function ($item) {
            $top = collect($item->hasil)->first();
            $p = $top ? ($top['percent'] ?? 0) : 0;

            return $p >= 80;
        })->count();

        // Riwayat 10 terbaru
        $recentDiagnoses = Konsultasi::with('user')->latest()->take(10)->get()->map(function ($diagnosis) {
            $top = collect($diagnosis->hasil)->first();
            $percent = $top ? $top['percent'] : 0;
            $level = $percent >= 80 ? 'Tinggi' : ($percent >= 50 ? 'Sedang' : 'Rendah');
            $badgeColor = $percent >= 80 ? 'danger' : ($percent >= 50 ? 'warning' : 'success');

            return [
                'id' => $diagnosis->id,
                'user_name' => optional($diagnosis->user)->nama ?? 'User #'.$diagnosis->user_id,
                'date' => \Carbon\Carbon::parse($diagnosis->created_at)->translatedFormat('d M Y H:i'),
                'gangguan' => $top ? $top['nama'] ?? ($top['name'] ?? '-') : '-',
                'percent' => number_format($percent, 2).'%',
                'level' => $level,
                'badge_color' => $badgeColor,
            ];
        });

        // Tren grafik (dioptimasi & difilter)
        $dates = collect(range(0, $diffInDays))->map(fn ($i) => $endDate->copy()->subDays($diffInDays - $i));
        
        $periodData = Konsultasi::whereBetween('created_at', [$startDate->startOfDay(), $endDate->endOfDay()])
            ->get()
            ->groupBy(function($item) {
                return \Carbon\Carbon::parse($item->created_at)->format('Y-m-d');
            });

        $chartDates = [];
        $chartSeverity = [];

        foreach ($dates as $date) {
            $dateStr = $date->format('Y-m-d');
            $dayData = $periodData->get($dateStr);
            
            if (!$dayData || $dayData->isEmpty()) {
                // Jangan tampilkan jika filter kustom dan tidak ada data di tanggal tersebut
                if ($filter === 'custom') {
                    continue;
                }
                $chartDates[] = $date->format('d M');
                $chartSeverity[] = 0;
            } else {
                $avg = $dayData->map(function ($d) {
                    $top = collect($d->hasil)->first();
                    return $top ? ($top['percent'] ?? 0) : 0;
                })->avg();
                
                $chartDates[] = $date->format('d M');
                $chartSeverity[] = round($avg, 2);
            }
        }

        return response()->json([
            'totalUsers' => number_format($totalUsers),
            'totalDiagnoses' => number_format($totalDiagnoses),
            'todayDiagnoses' => number_format($todayDiagnoses),
            'highRiskCount' => number_format($highRiskCount),
            'chartDates' => $chartDates,
            'chartSeverity' => $chartSeverity,
            'recentDiagnoses' => $recentDiagnoses,
        ]);
    }
}
