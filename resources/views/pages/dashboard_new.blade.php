@extends('layouts.template')

@section('title', 'Dashboard')

@push('styles')
<style>
    /* Glassmorphism & Custom Styles */
    .glass-card {
        background: rgba(255, 255, 255, 0.7);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.07);
    }

    .stat-card {
        transition: transform 0.2s;
    }

    .stat-card:hover {
        transform: translateY(-5px);
    }

    .chart-container {
        position: relative;
        min-height: 300px;
    }

    .empty-state {
        text-align: center;
        padding: 2rem;
        color: #6c757d;
    }

    .empty-state-icon {
        font-size: 3rem;
        margin-bottom: 1rem;
        color: #adb5bd;
    }

    /* Responsive Typography */
    @media (max-width: 768px) {
        .hero-title {
            font-size: 1.5rem;
        }
        .hero-subtitle {
            font-size: 1rem;
        }
    }

    /* Accessibility Focus Styles */
    .btn:focus, .nav-link:focus {
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
        outline: 2px solid transparent;
    }
</style>
@endpush

@section('content')
<!-- Hero Section -->
<div class="py-4 mb-4 glass-card rounded-3 mt-3">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h1 class="hero-title fw-bold mb-2">Dashboard Diagnosa Tidur</h1>
                <p class="hero-subtitle text-muted mb-4">
                    Pantau hasil, lanjutkan diagnosa, dan lihat progres kesehatan tidur Anda.
                </p>
                <div class="d-flex gap-2 flex-wrap">
                    <a href="{{ route('diagnosa.form') }}" class="btn btn-primary px-4" role="button">
                        <i class="mdi mdi-stethoscope me-1"></i> Mulai Diagnosa
                    </a>
                    {{--  @if(isset($inProgressSession))
                    <a href="{{ route('diagnosa.continue') }}" class="btn btn-outline-primary px-4" role="button">
                        <i class="mdi mdi-play me-1"></i> Lanjutkan Diagnosa
                    </a>
                    @endif  --}}
                </div>
                <div class="mt-3">
                    <small class="text-muted">
                        <i class="mdi mdi-shield-lock me-1"></i>
                        Data Anda dienkripsi dan hanya digunakan untuk keperluan diagnosa.
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- KPI Summary Cards -->
<div class="row g-3 mb-4">
    <!-- Last Diagnosis -->
    <div class="col-sm-6 col-lg-4">
        <div class="card glass-card stat-card h-100">
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <div class="flex-shrink-0">
                        <div class="bg-primary-subtle p-2 rounded-2">
                            <i class="mdi mdi-clipboard-text-clock text-primary fs-4"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="card-title mb-0">Diagnosa Terakhir</h6>
                    </div>
                </div>
                <h4 class="mb-0">
                    @if(isset($lastDiagnosis) && isset($lastResult))
                        {{ $lastResult['nama'] }}
                    @else
                        <span class="text-muted">Belum ada</span>
                    @endif
                </h4>
            </div>
        </div>
    </div>

    <!-- Last CF Score -->
    <div class="col-sm-6 col-lg-4">
        <div class="card glass-card stat-card h-100">
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <div class="flex-shrink-0">
                        <div class="bg-success-subtle p-2 rounded-2">
                            <i class="mdi mdi-percent text-success fs-4"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="card-title mb-0">Skor CF Terakhir</h6>
                    </div>
                </div>
                @if(isset($cfFinalPct))
                <h4 class="mb-0">
                    <span class="badge bg-success-subtle text-success">{{ number_format($cfFinalPct, 1) }}%</span>
                </h4>
                @else
                <h4 class="mb-0"><span class="text-muted">-</span></h4>
                @endif
            </div>
        </div>
    </div>



    <!-- Average Duration -->
    <div class="col-sm-6 col-lg-4">
        <div class="card glass-card stat-card h-100">
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <div class="flex-shrink-0">
                        <div class="bg-warning-subtle p-2 rounded-2">
                            <i class="mdi mdi-clock text-warning fs-4"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="card-title mb-0">Durasi Rata-rata</h6>
                    </div>
                </div>
                <h4 class="mb-0">
                    @if(isset($avgDuration))
                        {{ $avgDuration }}
                    @else
                        <span class="text-muted">-</span>
                    @endif
                </h4>
            </div>
        </div>
    </div>

    <!-- Total Saved -->
    <div class="col-sm-6 col-lg-4">
        <div class="card glass-card stat-card h-100">
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <div class="flex-shrink-0">
                        <div class="bg-primary-subtle p-2 rounded-2">
                            <i class="mdi mdi-history text-primary fs-4"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="card-title mb-0">Riwayat Tersimpan</h6>
                    </div>
                </div>
                <h4 class="mb-0">{{ $totalSaved ?? 0 }}</h4>
            </div>
        </div>
    </div>
</div>

    <!-- Charts Row -->
<div class="row mb-4">
    <!-- CF Trend Chart -->
    <div class="col-12 mb-4">
        <div class="card glass-card h-100">
            <div class="card-header border-0 bg-transparent">
                <h5 class="card-title mb-0">Tren CF Final (%) per Diagnosa</h5>
            </div>
            <div class="card-body">
                <div class="chart-container">
                    @if(isset($chartDates) && count($chartDates) > 0)
                        <canvas id="cfTrendChart"></canvas>
                    @else
                        <div class="empty-state">
                            <i class="mdi mdi-chart-line empty-state-icon d-block"></i>
                            <p>Belum ada data trend untuk ditampilkan</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div><!-- Recent Diagnoses Table -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card glass-card">
            <div class="card-header border-0 bg-transparent">
                <h5 class="card-title mb-0">Riwayat Diagnosa Terkini</h5>
            </div>
            <div class="card-body">
                @if(isset($recentDiagnoses) && count($recentDiagnoses) > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Gangguan Terdeteksi</th>
                                <th>CF Final (%)</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentDiagnoses as $diagnosis)
                            <tr>
                                <td>{{ $diagnosis->created_at->format('d M Y H:i') }}</td>
                                <td>{{ $diagnosis->detected_disorder }}</td>
                                <td>
                                    <span class="badge bg-success-subtle text-success">
                                        {{ number_format($diagnosis->cf_final * 100, 1) }}%
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $diagnosis->status_color }}-subtle text-{{ $diagnosis->status_color }}">
                                        {{ $diagnosis->status }}
                                    </span>
                                </td>
                                <td>
                                    <button type="button"
                                            class="btn btn-sm btn-primary"
                                            data-bs-toggle="modal"
                                            data-bs-target="#detailModal{{ $diagnosis->id }}">
                                        Detail
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="empty-state">
                    <i class="mdi mdi-clipboard-text empty-state-icon d-block"></i>
                    <p>Belum ada riwayat diagnosa</p>
                    <a href="{{ route('diagnosa.form') }}" class="btn btn-primary mt-2">Mulai Diagnosa Pertama</a>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Insight & Tips Card -->
<div class="row">
    <div class="col-lg-4 order-lg-2 mb-4">
        <div class="card glass-card">
            <div class="card-header border-0 bg-transparent">
                <h5 class="card-title mb-0">Insight & Tips</h5>
            </div>
            <div class="card-body">
                <ul class="list-unstyled mb-0">
                    <li class="mb-3">
                        <i class="mdi mdi-lightbulb text-warning me-2"></i>
                        Pertahankan jadwal tidur yang konsisten
                    </li>
                    <li class="mb-3">
                        <i class="mdi mdi-lightbulb text-warning me-2"></i>
                        Hindari kafein 6 jam sebelum tidur
                    </li>
                    <li class="mb-3">
                        <i class="mdi mdi-lightbulb text-warning me-2"></i>
                        Lakukan aktivitas fisik di siang hari
                    </li>
                </ul>
                <a href="" class="btn btn-light w-100 mt-3">
                    <i class="mdi mdi-book-open-page-variant me-1"></i>
                    Pelajari gangguan tidur
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Diagnosis Detail Modals -->
@if(isset($recentDiagnoses))
@foreach($recentDiagnoses as $diagnosis)
<div class="modal fade" id="detailModal{{ $diagnosis->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail Diagnosa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Gejala yang Dipilih</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($diagnosis->details as $index => $detail)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $detail->symptom }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="table-group-divider">
                            <tr>
                                <td colspan="2" class="text-end">
                                    <strong>Hasil Diagnosa:</strong>
                                    {{ $diagnosis->detected_disorder }}
                                    <span class="badge bg-{{ $diagnosis->status_color }}-subtle text-{{ $diagnosis->status_color }} ms-2">
                                        CF: {{ number_format($diagnosis->cf_final * 100, 1) }}%
                                    </span>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <a href="" class="btn btn-primary">
                    <i class="mdi mdi-download me-1"></i> Unduh PDF
                </a>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
@endforeach
@endif

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
@if(isset($chartDates) && count($chartDates) > 0)
    // CF Trend Chart
    new Chart(document.getElementById('cfTrendChart'), {
        type: 'line',
        data: {
            labels: @json($chartDates),
            datasets: [{
                label: 'CF Final (%)',
                data: @json($chartCfFinal),
                borderColor: '#0d6efd',
                backgroundColor: 'rgba(13, 110, 253, 0.1)',
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    max: 100
                }
            }
        }
    });

@endif
</script>
@endpush
