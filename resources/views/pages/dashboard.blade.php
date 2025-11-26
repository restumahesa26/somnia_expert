@extends('layouts.template')

@section('title', 'Dashboard')

@section('content')
<div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
    <div class="flex-grow-1">
        <h4 class="fs-18 fw-semibold m-0">Dashboard</h4>
    </div>
</div>

<!-- Start Main Widgets -->
<div class="row">
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="p-2 border border-primary border-opacity-10 bg-primary-subtle rounded-2 me-2">
                        <div class="bg-primary rounded-circle widget-size text-center">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24">
                                <path fill="#ffffff" d="M20 6c0-2.2-1.8-4-4-4s-4 1.8-4 4c0 2.2 1.8 4 4 4s4-1.8 4-4m-4 2c-1.1 0-2-.9-2-2s.9-2 2-2s2 .9 2 2s-.9 2-2 2M4 7c0 2.8 2.2 5 5 5s5-2.2 5-5s-2.2-5-5-5s-5 2.2-5 5m5 3c-1.7 0-3-1.3-3-3s1.3-3 3-3s3 1.3 3 3s-1.3 3-3 3m5.9 8.3c.3-.7.8-1.3 1.5-1.7C17.6 15.7 19.6 15 22 15v-2c-2.8 0-5.2.9-6.8 2c-.3-.7-.9-1.3-1.6-1.7C11.6 12.2 8.9 11 6 11s-5.6 1.2-7.6 2.3C1.6 14.1 1 16 1 18v4h11.1c-.1-.3-.1-.7-.1-1c0-1.8 1.5-3.3 3.3-3.3c1.8 0 3.3 1.5 3.3 3.3c0 .3 0 .7-.1 1H22v-4c-2.2 0-3.9.6-5 1.3c-.8.5-1.4 1-1.9 1.7c-.3.4-.5.9-.6 1.4c-.2.6-.3 1.1-.3 1.6h2c0-.3.1-.7.2-1c.2-.4.3-.7.5-1z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="flex-grow-1">
                        <h5>Total Screening Sekarang</h5>
                        <h3 class="mb-0 fs-22 text-dark me-3">{{ $totalDiagnoses }}</h3>
                    </div>
                    <div class="flex-shrink-0">
                        <div class="avatar avatar-sm">
                            <div class="avatar-title bg-primary-subtle text-primary rounded">
                                <i class="mdi mdi-chart-bar fs-20"></i>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="p-2 border border-secondary border-opacity-10 bg-secondary-subtle rounded-2 me-2">
                        <div class="bg-secondary rounded-circle widget-size text-center">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24">
                                <path fill="#ffffff" d="m10 17l-5-5l1.41-1.42L10 14.17l7.59-7.59L19 8m-7-6A10 10 0 0 0 2 12a10 10 0 0 0 10 10a10 10 0 0 0 10-10A10 10 0 0 0 12 2" />
                            </svg>
                        </div>
                    </div>
                    <div class="flex-grow-1">
                        <h5 class="mb-2">Hasil Terakhir</h5>
                        <h3 class="fs-16">{{ $lastResult ? $lastResult['nama'] : 'Belum ada' }}</h3>
                    </div>
                    <div class="flex-shrink-0">
                        <div class="avatar avatar-sm">
                            <div class="avatar-title bg-secondary-subtle text-secondary rounded">
                                <i class="mdi mdi-check-circle fs-20"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{--  <div class="col-md-2">
        <div class="card">
            <div class="card-body">
                <div class="widget-first">

                    <div class="d-flex align-items-center mb-2">
                        <div
                            class="p-2 border border-danger border-opacity-10 bg-danger-subtle rounded-2 me-2">
                            <div class="bg-danger rounded-circle widget-size text-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                    viewBox="0 0 24 24">
                                    <path fill="#ffffff"
                                        d="M22 19H2v2h20zM4 15c0 .5.2 1 .6 1.4s.9.6 1.4.6V6c-.5 0-1 .2-1.4.6S4 7.5 4 8zm9.5-9h-3c0-.4.1-.8.4-1.1s.6-.4 1.1-.4c.4 0 .8.1 1.1.4c.2.3.4.7.4 1.1M7 6v11h10V6h-2q0-1.2-.9-2.1C13.2 3 12.8 3 12 3q-1.2 0-2.1.9T9 6zm11 11c.5 0 1-.2 1.4-.6s.6-.9.6-1.4V8c0-.5-.2-1-.6-1.4S18.5 6 18 6z" />
                                </svg>
                            </div>
                        </div>
                        <p class="mb-0 text-dark fs-15">Tingkat Keyakinan (CF)</p>
                    </div>

                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="mb-0 fs-18 text-dark me-3">{{ number_format($lastCF, 1) }}%</h3>
                    </div>

                </div>
            </div>
        </div>
    </div>  --}}

    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="p-2 border border-warning border-opacity-10 bg-warning-subtle rounded-2 me-2">
                        <div class="bg-warning rounded-circle widget-size text-center">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24">
                                <path fill="#ffffff" d="M7 15h2c0 1.08 1.37 2 3 2s3-.92 3-2c0-1.1-1.04-1.5-3.24-2.03C9.64 12.44 7 11.78 7 9c0-1.79 1.47-3.31 3.5-3.82V3h3v2.18C15.53 5.69 17 7.21 17 9h-2c0-1.08-1.37-2-3-2s-3 .92-3 2c0 1.1 1.04 1.5 3.24 2.03C14.36 11.56 17 12.22 17 15c0 1.79-1.47 3.31-3.5 3.82V21h-3v-2.18C8.47 18.31 7 16.79 7 15" />
                            </svg>
                        </div>
                    </div>
                    <div class="flex-grow-1">
                        <h5 class="mb-2">Tanggal Screening Terakhir</h5>
                        <h3 class="fs-16">@if($lastDiagnosisDate){{ $lastDiagnosisDate->format('d M Y') }}@else-@endif</h3>
                    </div>
                    <div class="flex-shrink-0">
                        <div class="avatar avatar-sm">
                            <div class="avatar-title bg-warning-subtle text-warning rounded">
                                <i class="mdi mdi-calendar fs-20"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{--  <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="p-2 border border-warning border-opacity-10 bg-warning-subtle rounded-2 me-2">
                        <div class="bg-danger rounded-circle widget-size text-center">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24">
                                <path fill="#ffffff" d="M12 3c.6 0 1.1.2 1.5.6c.4.4.5.9.5 1.5c0 .6-.2 1.1-.6 1.5c-.4.4-.9.6-1.5.6c-.5 0-1-.2-1.4-.6c-.4-.4-.6-.9-.6-1.5c0-.6.2-1.1.6-1.5c.4-.4.9-.6 1.5-.6m0 13c2.2 0 4-1.8 4-4c0-1.5-.8-2.8-2-3.4c1.1-.5 1.9-1.3 2.4-2.2c.5-1 .8-2 .8-3.1c0-.7-.1-1.4-.3-2c-1.4.4-2.8.6-4.2.6c-.8 0-1.6-.1-2.4-.2c-.8-.1-1.6-.4-2.3-.7c-.2.7-.3 1.4-.3 2.2c0 1.1.3 2.1.8 3.1s1.3 1.7 2.4 2.2c-1.2.6-2 1.9-2 3.4c0 2.2 1.8 4 4 4m0 7c1.9 0 3.6-.4 5.1-1.1c1.5-.7 2.8-1.7 3.9-2.9s1.9-2.6 2.5-4.1c.6-1.6.9-3.2.9-5c0-1.6-.3-3.2-.8-4.7c-.5-1.6-1.3-3-2.3-4.3L20 3.3c2 2.3 3 5 3 8.2c0 1.9-.4 3.7-1.1 5.4c-.7 1.7-1.7 3.1-3 4.3c-1.3 1.3-2.7 2.2-4.4 2.9S11.4 25 9.5 25c-1.2 0-2.3-.1-3.4-.4s-2.1-.7-3.1-1.3l1.3-2.1c.9.5 1.8.8 2.8 1.1c1 .2 2 .3 2.9.3Z"/></svg>
                        </div>
                    </div>
                    <div class="flex-grow-1">
                        <h5 class="mb-2">Status Tidur Sekarang</h5>
                        <h3 class="fs-16">{{ $currentStatus }}</h3>
                    </div>
                    <div class="flex-shrink-0">
                        <div class="avatar avatar-sm">
                            <div class="avatar-title bg-{{ $currentStatus == 'Normal' ? 'success' : ($currentStatus == 'Waspada' ? 'warning' : 'danger') }}-subtle text-{{ $currentStatus == 'Normal' ? 'success' : ($currentStatus == 'Waspada' ? 'warning' : 'danger') }} rounded">
                                <i class="mdi mdi-sleep fs-20"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>  --}}

</div>
<!-- End Main Widgets -->

<!-- start row -->
<div class="row">

    <div class="col-md-12 col-xl-8">
        <div class="card">
            <div class="card-header">
                <div class="d-flex align-items-center">
                    <h5 class="card-title mb-0">Riwayat 10 Screening Terakhir</h5>
                </div>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Nama</th>
                                <th>Gangguan</th>
                                {{--  <th>Tingkat Keparahan</th>  --}}
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentDiagnoses as $diagnosis)
                            <tr>
                                <td>{{ $diagnosis->created_at->format('d M Y') }}</td>
                                <td>
                                    @if (Auth::user()->is_admin)
                                        {{ $diagnosis->nama_pasien == '' ? 'Pasien' : $diagnosis->nama_pasien }}
                                    @else
                                        {{ Auth::user()->nama }}
                                    @endif
                                </td>
                                <td>{{ $diagnosis->gangguan }}</td>
                                {{--  <td>{{ $diagnosis->severity_level }}</td>  --}}
                                <td>
                                    <span class="badge bg-{{ $diagnosis->status_color }}-subtle text-{{ $diagnosis->status_color }}">
                                        {{ number_format($diagnosis->percent, 1) }}%
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('diagnosa.show', $diagnosis->id) }}" class="btn btn-sm btn-primary">
                                        <i class="mdi mdi-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{--  <div class="col-md-12 col-xl-4">
        <div class="card">
            <div class="card-header">
                <div class="d-flex align-items-center">
                    <h5 class="card-title mb-0">Distribusi Tingkat Keparahan</h5>
                </div>
            </div>

            <div class="card-body">
                <div id="severity-distribution" class="apex-charts"></div>

                <div class="row mt-2">
                    @foreach($diagnosisDistribution as $severity => $data)
                    <div class="col">
                        <div class="d-flex justify-content-between align-items-center p-1">
                            <div>
                                <i class="mdi mdi-circle fs-12 align-middle me-1 text-{{ $severity == 'Tinggi' ? 'danger' : ($severity == 'Sedang' ? 'warning' : 'success') }}"></i>
                                <span class="align-middle fw-semibold">{{ $severity }}</span>
                            </div>
                            <span class="fw-medium text-muted float-end">{{ $data['percentage'] }}%</span>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>  --}}
</div>
@endsection

@push('scripts')
<script src="{{ url('dist/assets/libs/apexcharts/apexcharts.min.js') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var chartData = {
        dates: @json($chartDates),
        severity: @json($chartSeverity)
    };

    var distributionData = @json($diagnosisDistribution);

    // Severity Distribution Chart
    var severityOptions = {
        series: Object.values(distributionData).map(d => d.count),
        chart: {
            type: 'donut',
            height: 250
        },
        labels: Object.keys(distributionData),
        colors: ['#dc3545', '#ffc107', '#198754'],
        legend: {
            show: false
        }
    };

    new ApexCharts(document.querySelector("#severity-distribution"), severityOptions).render();
});
</script>
@endpush
