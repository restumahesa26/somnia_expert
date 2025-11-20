@extends('layouts.template')

@section('title', 'Dashboard Admin')

@section('content')
<div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
    <div class="flex-grow-1">
        <h4 class="fs-18 fw-semibold m-0">Dashboard Admin</h4>
        <p class="text-muted mb-0">Ringkasan agregat seluruh pengguna & diagnosa.</p>
    </div>
</div>

<!-- Start Main Widgets -->
<div class="row g-3">
    <div class="col-md-4">
        <div class="card">
            <div class="card-body d-flex align-items-center">
                <div class="me-3">
                    <i class="mdi mdi-account-multiple fs-20 text-primary"></i>
                </div>
                <div>
                    <p class="text-muted mb-1">Jumlah Pengguna</p>
                    <h4 class="mb-0">{{ number_format($totalUsers) }}</h4>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-body d-flex align-items-center">
                <div class="me-3">
                    <i class="mdi mdi-clipboard-text fs-20 text-info"></i>
                </div>
                <div>
                    <p class="text-muted mb-1">Jumlah Diagnosa</p>
                    <h4 class="mb-0">{{ number_format($totalDiagnoses) }}</h4>
                </div>
            </div>
        </div>
    </div>

    {{--  <div class="col-md-3">
        <div class="card">
            <div class="card-body d-flex align-items-center">
                <div class="me-3">
                    <i class="mdi mdi-alert-circle fs-20 text-danger"></i>
                </div>
                <div>
                    <p class="text-muted mb-1">Hasil ≥ 80% (Risiko Tinggi)</p>
                    <h4 class="mb-0">{{ number_format($highRiskCount) }}</h4>
                </div>
            </div>
        </div>
    </div>  --}}

    <div class="col-md-4">
        <div class="card">
            <div class="card-body d-flex align-items-center">
                <div class="me-3">
                    <i class="mdi mdi-calendar-today fs-20 text-success"></i>
                </div>
                <div>
                    <p class="text-muted mb-1">Diagnosa Hari Ini</p>
                    <h4 class="mb-0">{{ number_format($todayDiagnoses) }}</h4>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End Main Widgets -->

<div class="row mt-3">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Tren Rata-rata Persentase Diagnosa (7 Hari)</h5>
            </div>
            <div class="card-body">
                <div id="trend-chart" class="apex-charts" style="min-height: 280px;"></div>
            </div>
        </div>
    </div>
    {{--  <div class="col-xl-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Distribusi Tingkat Keparahan</h5>
            </div>
            <div class="card-body">
                <div id="severity-distribution" class="apex-charts" style="min-height: 280px;"></div>
            </div>
        </div>
    </div>  --}}
</div>

{{--  <div class="row mt-3">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Riwayat 10 Diagnosa Terbaru (Semua Pengguna)</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Nama Pengguna</th>
                                <th>Tanggal</th>
                                <th>Gangguan Tertinggi</th>
                                <th>Persentase</th>
                                <th>Tingkat</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($recentDiagnoses as $index => $diagnosis)
                                @php
                                    $top = collect($diagnosis->hasil)->first();
                                    $percent = $top ? $top['percent'] : 0;
                                    $level = $percent >= 80 ? 'Tinggi' : ($percent >= 50 ? 'Sedang' : 'Rendah');
                                @endphp
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ optional($diagnosis->user)->nama ?? 'User #' . $diagnosis->user_id }}</td>
                                    <td>{{ \Carbon\Carbon::parse($diagnosis->created_at)->translatedFormat('d M Y H:i') }}</td>
                                    <td>{{ $top ? $top['nama'] ?? ($top['name'] ?? '-') : '-' }}</td>
                                    <td>{{ number_format($percent, 2) }}%</td>
                                    <td>
                                        <span class="badge bg-{{ $percent >= 80 ? 'danger' : ($percent >= 50 ? 'warning' : 'success') }}">{{ $level }}</span>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="6" class="text-center text-muted">Belum ada data.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>  --}}

@endsection

@push('scripts')
<script src="{{ url('dist/assets/libs/apexcharts/apexcharts.min.js') }}"></script>
<script>
    var chartData = {
        dates: @json($chartDates),
        severity: @json($chartSeverity)
    };

    // Line chart for average percent per day
    var trendOptions = {
        series: [{
            name: 'Rata-rata Persentase',
            data: chartData.severity
        }],
        chart: {
            type: 'line',
            height: 280,
            toolbar: { show: false }
        },
        xaxis: {
            categories: chartData.dates
        },
        stroke: { width: 3, curve: 'smooth' },
        markers: { size: 3 }
    };
    new ApexCharts(document.querySelector("#trend-chart"), trendOptions).render();

    // Donut for severity distribution
    var distributionData = @json($diagnosisDistribution);
    var severityOptions = {
        series: Object.values(distributionData).map(d => d.count),
        chart: { type: 'donut', height: 280 },
        labels: Object.keys(distributionData),
        legend: { show: true }
    };
    new ApexCharts(document.querySelector("#severity-distribution"), severityOptions).render();
</script>
@endpush
