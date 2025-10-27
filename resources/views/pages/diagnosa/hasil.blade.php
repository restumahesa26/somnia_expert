@extends('layouts.template')
@section('title', 'Hasil Diagnosa')
@section('content')
<h1 class="h4 mb-3 mt-3">Hasil Diagnosis</h1>

@if($konsultasi->is_admin_input && $konsultasi->nama_pasien)
<div class="card mb-3 shadow-sm">
    <div class="card-body">
        <h5 class="card-title">Data Pasien</h5>
        <div class="row">
            <div class="col-md-4">
                <p class="mb-1"><strong>Nama:</strong> {{ $konsultasi->nama_pasien }}</p>
            </div>
            <div class="col-md-4">
                <p class="mb-1"><strong>Umur:</strong> {{ $konsultasi->umur }} tahun</p>
            </div>
            <div class="col-md-4">
                <p class="mb-1">
                    <strong>Jenis Kelamin:</strong>
                    {{ $konsultasi->jenis_kelamin == 'male' ? 'Laki-laki' : 'Perempuan' }}
                </p>
            </div>
        </div>
    </div>
</div>
@endif

<!-- Penjelasan Singkat -->
<div class="alert alert-info">
    <h6 class="alert-heading fw-bold mb-2">Informasi Diagnosis:</h6>
    <p class="mb-2">Berdasarkan {{ count($konsultasi->gejala_terpilih) }} gejala yang Anda pilih, sistem telah menganalisis kemungkinan gangguan yang dialami menggunakan 2 metode perhitungan:</p>
    <ol class="mb-0">
        <li><strong>Metode VCIRS</strong> - mengukur keterkaitan gejala dengan masing-masing gangguan</li>
        <li><strong>Metode CF (Certainty Factor)</strong> - menghitung tingkat kepastian berdasarkan penilaian pakar</li>
    </ol>
</div>

<!-- Hasil Diagnosa Accordion -->
<div class="accordion mb-4" id="hasilDiagnosa">
    @foreach($sortedHasil as $index => $row)
        <div class="accordion-item">
            <h2 class="accordion-header">
                <!-- Update the accordion header button -->
                <button class="accordion-button {{ $index !== 0 ? 'collapsed' : '' }}" type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#collapse{{ $index }}"
                    aria-expanded="{{ $index === 0 ? 'true' : 'false' }}"
                    aria-controls="collapse{{ $index }}">
                    <div class="d-flex justify-content-between align-items-center w-100 me-3">
                        <span>
                            <strong>{{ $row['penyakit']->kode_penyakit }}</strong> -
                            {{ $row['penyakit']->nama_penyakit }}
                        </span>
                        <span class="badge badge-result
                            @if($row['percent'] >= 50)
                                @php
                                    $aboveFifty = collect($sortedHasil)->filter(function($item) {
                                        return $item['percent'] >= 50;
                                    });
                                    $isHighest = $row['percent'] === $aboveFifty->max('percent');
                                @endphp
                                @if($isHighest)
                                    bg-danger
                                @else
                                    bg-warning
                                @endif
                            @else
                                bg-primary
                            @endif">
                            {{ number_format($row['percent'],2) }}%
                        </span>
                    </div>
                </button>
            </h2>
            <div id="collapse{{ $index }}" class="accordion-collapse collapse {{ $index === 0 ? 'show' : '' }}"
                data-bs-parent="#hasilDiagnosa">
                <div class="accordion-body">
                    <!-- Penjelasan Proses -->
                    <div class="alert alert-light border mb-4">
                        <h6 class="fw-bold mb-2">Bagaimana cara membaca hasil ini?</h6>
                        <p class="mb-0">Perhitungan dilakukan dalam 3 tahap:</p>
                        <ol class="mb-0">
                            <li><strong>VCIRS</strong> - menghitung bobot setiap gejala berdasarkan keterkaitan dan urutannya</li>
                            <li><strong>CF</strong> - menggabungkan bobot VCIRS dengan penilaian pakar</li>
                            <li><strong>Hasil Akhir</strong> - menunjukkan persentase kemungkinan diagnosis</li>
                        </ol>
                    </div>

                    <!-- Proses VCIRS -->
                    <div class="mb-4">
                        <h6 class="fw-bold mb-3">
                            1. Proses VCIRS
                            <i class="mdi mdi-help-circle text-muted"
                               data-bs-toggle="tooltip"
                               title="Variable-Centered Intelligent Rule System - Metode untuk mengukur keterkaitan gejala">
                            </i>
                        </h6>

                        <!-- Penjelasan Komponen -->
                        <div class="card bg-light mb-3">
                            <div class="card-body">
                                <h6 class="card-title">Komponen Perhitungan:</h6>
                                <div class="row g-3">
                                    <div class="col-md-6 col-lg-4">
                                        <div class="d-flex align-items-start">
                                            <span class="badge bg-primary me-2">NS</span>
                                            <div>
                                                <strong>Number of Symptoms</strong>
                                                <p class="mb-0 small text-muted">Jumlah total gejala yang terkait dengan gangguan ini</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-lg-4">
                                        <div class="d-flex align-items-start">
                                            <span class="badge bg-primary me-2">VO</span>
                                            <div>
                                                <strong>Valid Order</strong>
                                                <p class="mb-0 small text-muted">Urutan kepentingan gejala (1 = paling penting)</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-lg-4">
                                        <div class="d-flex align-items-start">
                                            <span class="badge bg-primary me-2">TV</span>
                                            <div>
                                                <strong>Total Valid</strong>
                                                <p class="mb-0 small text-muted">Total gejala yang valid untuk gangguan ini</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-sm table-bordered diagnosa-table">
                                <thead>
                                    <tr class="table-light">
                                        <th style="min-width: 200px">Gejala</th>
                                        <th>Credit<br><small>(Input User)</small></th>
                                        <th>NS<br><small>(Jumlah Gejala)</small></th>
                                        <th>VO<br><small>(Urutan)</small></th>
                                        <th>TV<br><small>(Total)</small></th>
                                        <th>CD<br><small>(VO/TV)</small></th>
                                        <th>Weight<br><small>(NS*CD)</small></th>
                                        <th>VUR<br><small>(Credit*Weight)</small></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $totalVUR = 0;
                                        $totalTV = 0;
                                    @endphp
                                    @foreach($row['detail'] as $gejalaId => $detail)
                                        @php
                                            $totalVUR += $detail['vur'];
                                            $totalTV = $detail['tv']; // TV akan sama untuk semua row
                                        @endphp
                                        <tr @if($detail['credit'] > 0) class="table-success" @endif>
                                            <td>{{ $gejalas->firstWhere('id', $gejalaId)->nama_gejala }}</td>
                                            <td class="text-center">{{ $detail['credit'] }}</td>
                                            <td class="text-center">{{ $detail['ns'] }}</td>
                                            <td class="text-center">{{ $detail['vo'] }}</td>
                                            <td class="text-center">{{ $detail['tv'] }}</td>
                                            <td class="text-center">
                                                {{ number_format($detail['cd'], 4) }}
                                                <div class="text-detail">{{ $detail['vo'] }}/{{ $detail['tv'] }}</div>
                                            </td>
                                            <td class="text-center">
                                                {{ number_format($detail['weight'], 4) }}
                                                <div class="text-detail">{{ $detail['ns'] }}*{{ number_format($detail['cd'], 4) }}</div>
                                            </td>
                                            <td class="text-center">
                                                {{ number_format($detail['vur'], 4) }}
                                                <div class="text-detail">{{ $detail['credit'] }}*{{ number_format($detail['weight'], 4) }}</div>
                                            </td>
                                        </tr>
                                    @endforeach
                                    <tr class="table-light fw-bold">
                                        <td>Total</td>
                                        <td colspan="6" class="text-end">Σ VUR =</td>
                                        <td class="text-center">{{ number_format($totalVUR, 4) }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Perhitungan RUR -->
                    <div class="card shadow-sm mb-4">
                        <div class="card-body">
                            <h6 class="fw-bold card-title mb-3">2. Perhitungan RUR</h6>

                            <div class="alert alert-light border mb-3">
                                <p class="mb-2"><strong>Apa itu RUR?</strong></p>
                                <p class="mb-0">RUR (Rule Utility Ranking) adalah nilai yang menunjukkan seberapa kuat keterkaitan antara gejala-gejala yang dipilih dengan suatu penyakit. Nilai ini dihitung dari hasil VCIRS di atas.</p>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-sm table-bordered table-calculation mb-0">
                                    <tr>
                                        <td width="200" class="fw-medium">Total VUR</td>
                                        <td>= {{ number_format($totalVUR, 4) }}</td>
                                        <td class="small text-muted">Jumlah semua nilai VUR dari tabel VCIRS di atas</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-medium">Total TV</td>
                                        <td>= {{ $totalTV }}</td>
                                        <td class="small text-muted">Total gejala valid untuk penyakit ini</td>
                                    </tr>
                                    <tr class="table-info">
                                        <td class="fw-bold">RUR (VCIRS)</td>
                                        <td>
                                            = Total VUR / Total TV<br>
                                            = {{ number_format($totalVUR, 4) }} / {{ $totalTV }}<br>
                                            = {{ number_format($row['rur'], 4) }}
                                        </td>
                                        <td class="small text-muted">
                                            <strong>Nilai ini akan digunakan dalam perhitungan CF berikutnya</strong>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Proses CF -->
                    <div class="mb-4">
                        <h6 class="fw-bold mb-3">
                            3. Proses CF
                            <i class="mdi mdi-help-circle text-muted"
                               data-bs-toggle="tooltip"
                               title="Certainty Factor - Metode untuk menghitung tingkat kepastian">
                            </i>
                        </h6>

                        <!-- Penjelasan CF -->
                        <div class="alert alert-light border mb-3">
                            <p class="mb-2"><strong>Apa itu CF?</strong></p>
                            <p class="mb-0">CF (Certainty Factor) adalah metode untuk menghitung tingkat kepastian diagnosis dengan menggabungkan:</p>
                            <ul class="mb-0">
                                <li>Penilaian pakar (CF Pakar)</li>
                                <li>Input dari pengguna (CF User)</li>
                                <li>Hasil perhitungan VCIRS (RUR)</li>
                            </ul>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-sm table-bordered diagnosa-table">
                                <thead>
                                    <tr class="table-light">
                                        <th style="min-width: 200px">Gejala</th>
                                        <th>CF Pakar</th>
                                        <th>CF User<br><small>(Credit)</small></th>
                                        <th>CF(H,E)<br><small>(CF Pakar * CF User)</small></th>
                                        <th>CF(H,E) * RUR</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $cfCalculations = [];
                                        $cfCombined = 0;
                                        $isFirst = true;
                                    @endphp
                                    @foreach($row['detail'] as $gejalaId => $detail)
                                        @if($detail['credit'] > 0)
                                            @php
                                                $cfHE = $detail['cf_pakar'] * $detail['credit'];
                                                $cfWeighted = $cfHE * $row['rur'];

                                                if($isFirst) {
                                                    $cfCombined = $cfWeighted;
                                                    $isFirst = false;
                                                } else {
                                                    $cfCombined = $cfCombined + ($cfWeighted * (1 - $cfCombined));
                                                }

                                                $cfCalculations[] = [
                                                    'gejala' => $gejalas->firstWhere('id', $gejalaId)->nama_gejala,
                                                    'cf_pakar' => $detail['cf_pakar'],
                                                    'credit' => $detail['credit'],
                                                    'cfHE' => $cfHE,
                                                    'cfWeighted' => $cfWeighted,
                                                    'running_total' => $cfCombined
                                                ];
                                            @endphp
                                            <tr>
                                                <td>{{ $gejalas->firstWhere('id', $gejalaId)->nama_gejala }}</td>
                                                <td class="text-center">{{ number_format($detail['cf_pakar'], 2) }}</td>
                                                <td class="text-center">{{ $detail['credit'] }}</td>
                                                <td class="text-center">
                                                    {{ number_format($cfHE, 4) }}
                                                    <div class="text-detail">{{ number_format($detail['cf_pakar'], 2) }}*{{ $detail['credit'] }}</div>
                                                </td>
                                                <td class="text-center">
                                                    {{ number_format($cfWeighted, 4) }}
                                                    <div class="text-detail">{{ number_format($cfHE, 4) }}*{{ number_format($row['rur'], 4) }}</div>
                                                </td>
                                            </tr>
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Hasil Akhir -->
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h6 class="fw-bold card-title mb-3">4. Hasil Akhir</h6>

                            <div class="table-responsive">
                                <table class="table table-sm table-bordered table-calculation mb-0">
                                    <tr>
                                        <td width="200" class="fw-medium">RUR (VCIRS)</td>
                                        <td>= {{ number_format($row['rur'], 4) }}</td>
                                        <td class="small text-muted">
                                            <strong>Cara perhitungan:</strong><br>
                                            1. Total VUR = {{ number_format($totalVUR, 4) }}<br>
                                            2. Total TV = {{ $totalTV }}<br>
                                            3. RUR = Total VUR / Total TV<br>
                                            = {{ number_format($totalVUR, 4) }} / {{ $totalTV }}<br>
                                            = {{ number_format($row['rur'], 4) }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="fw-medium">CF Kombinasi</td>
                                        <td>= {{ number_format($cfCombined, 4) }}</td>
                                        <td class="small text-muted">
                                            <strong>Cara perhitungan:</strong><br>
                                            @foreach($cfCalculations as $index => $calc)
                                                @if($index == 0)
                                                    CF1 = {{ number_format($calc['cfWeighted'], 4) }}<br>
                                                @else
                                                    <div class="mt-2">
                                                        CF{{ $index + 1 }} = {{ number_format($calc['running_total'], 4) }}<br>
                                                        <span class="text-muted">&nbsp;&nbsp;= CF{{ $index }} + ({{ number_format($calc['cfWeighted'], 4) }} × (1 - {{ number_format($cfCalculations[$index-1]['running_total'], 4) }}))</span><br>
                                                        <span class="text-muted">&nbsp;&nbsp;= CF{{ $index }} + ({{ number_format($calc['cfWeighted'], 4) }} × {{ number_format(1 - $cfCalculations[$index-1]['running_total'], 4) }})</span><br>
                                                        <span class="text-muted">&nbsp;&nbsp;= {{ number_format($cfCalculations[$index-1]['running_total'], 4) }} + {{ number_format($calc['cfWeighted'] * (1 - $cfCalculations[$index-1]['running_total']), 4) }}</span>
                                                    </div>
                                                @endif
                                            @endforeach
                                        </td>
                                    </tr>
                                    <tr class="table-warning">
                                        <td class="fw-bold">Persentase Akhir</td>
                                        <td class="fw-bold">= {{ number_format($row['percent'], 2) }}%</td>
                                        <td class="small text-muted">
                                            <strong>Cara perhitungan:</strong><br>
                                            CF Kombinasi × 100<br>
                                            = {{ number_format($cfCombined, 4) }} × 100<br>
                                            = {{ number_format($row['percent'], 2) }}%
                                        </td>
                                    </tr>
                                </table>
                            </div>

                            <!-- Tambahan penjelasan umum -->
                            <div class="alert alert-light border mt-3 mb-0">
                                <p class="mb-2"><strong>Interpretasi Hasil:</strong></p>
                                <ul class="mb-0">
                                    <li>Semakin tinggi persentase, semakin besar kemungkinan diagnosis tersebut tepat</li>
                                    <li>RUR menunjukkan kekuatan hubungan antara gejala-gejala yang dipilih</li>
                                    <li>CF Kombinasi menggabungkan penilaian pakar dengan hasil VCIRS</li>
                                    <li>Hasil di atas 50% menunjukkan kemungkinan yang cukup signifikan</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Informasi Penyakit -->
                    <div class="mt-4">
                        <h6 class="fw-bold mb-3">Informasi Penyakit</h6>

                        <!-- Deskripsi -->
                        <div class="card mb-3">
                            <div class="card-header bg-light">
                                <h6 class="card-title mb-0">
                                    <i class="mdi mdi-information-outline me-1"></i> Deskripsi
                                </h6>
                            </div>
                            <div class="card-body">
                                {!! nl2br(e($row['penyakit']->deskripsi)) !!}
                            </div>
                        </div>

                        <!-- Solusi -->
                        <div class="card">
                            <div class="card-header bg-light">
                                <h6 class="card-title mb-0">
                                    <i class="mdi mdi-medical-bag me-1"></i> Solusi & Penanganan
                                </h6>
                            </div>
                            <div class="card-body">
                                {!! nl2br(e($row['penyakit']->solusi)) !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>

<!-- Catatan Tambahan -->
<div class="alert alert-warning">
    <h6 class="alert-heading fw-bold">Catatan Penting!</h6>
    <p class="mb-0">Hasil diagnosis ini hanya berupa prediksi berdasarkan gejala yang Anda pilih. Untuk diagnosis yang akurat, silakan konsultasikan dengan dokter atau profesional kesehatan.</p>
</div>

<div class="mt-4">
    <a href="{{ route('diagnosa.form') }}" class="btn btn-secondary">
        <i class="mdi mdi-refresh me-1"></i> Diagnosa Ulang
    </a>
</div>

@endsection

@push('styles')
    <!-- Datatables css -->
    <link href="{{ url('dist/assets/libs/datatables.net-bs5/css/dataTables.bootstrap5.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ url('dist/assets/libs/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css') }}" rel="stylesheet" type="text/css" />

    <style>
        .diagnosa-table th {
            text-align: center;
            vertical-align: middle !important;
            background-color: #f8f9fa;
            border-bottom: 2px solid #dee2e6;
        }
        .diagnosa-table td {
            vertical-align: middle;
        }
        .diagnosa-table .text-detail {
            font-size: 11px;
            color: #6c757d;
            margin-top: 2px;
        }
        .table-calculation td {
            padding: 12px 15px;
        }
        .badge-result {
            font-size: 14px;
            padding: 8px 12px;
        }
        /* Accordion styling */
        .accordion-button:not(.collapsed) {
            color: #fff;
            background-color: #435ebe;
        }

        .accordion-button:not(.collapsed)::after {
            filter: brightness(0) invert(1);
        }

        .accordion-button:not(.collapsed) .badge {
            border: 1px solid rgba(255,255,255,0.5);
        }

        .accordion-button:focus {
            box-shadow: none;
            border-color: rgba(67, 94, 190, 0.15);
        }
    </style>
@endpush

@push('scripts')
<script>
    // Enable tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    });

    // Initialize accordion with one-at-a-time behavior
    document.addEventListener('DOMContentLoaded', function() {
        var myCollapsibles = document.querySelectorAll('#hasilDiagnosa .accordion-collapse');

        myCollapsibles.forEach(function(collapse) {
            collapse.addEventListener('show.bs.collapse', function() {
                // Close all other open accordion items
                myCollapsibles.forEach(function(otherCollapse) {
                    if (otherCollapse !== collapse && bootstrap.Collapse.getInstance(otherCollapse)) {
                        bootstrap.Collapse.getInstance(otherCollapse).hide();
                    }
                });
            });
        });
    });
</script>
@endpush
