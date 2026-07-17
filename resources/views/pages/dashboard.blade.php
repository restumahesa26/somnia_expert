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
                        <h3 class="mb-0 fs-22 text-dark me-3" id="total-diagnoses"><i class="mdi mdi-spin mdi-loading fs-4"></i></h3>
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
                        <h3 class="fs-16" id="last-result"><i class="mdi mdi-spin mdi-loading fs-5"></i></h3>
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
                        <h3 class="fs-16" id="last-date"><i class="mdi mdi-spin mdi-loading fs-5"></i></h3>
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
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="recent-table-body">
                            <tr>
                                <td colspan="5" class="text-center py-4">
                                    <div class="spinner-border text-primary" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                    <div class="mt-2 text-muted">Memuat data screening...</div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    loadDashboardData();
});

function loadDashboardData() {
    fetch('{{ route('dashboard.user-data') }}', {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        // Update Widgets
        document.getElementById('total-diagnoses').textContent = data.totalDiagnoses;
        document.getElementById('last-result').textContent = data.lastResultName;
        document.getElementById('last-date').textContent = data.lastDiagnosisDate ? data.lastDiagnosisDate : '-';

        // Update Table
        const tableBody = document.getElementById('recent-table-body');
        if (!data.recentDiagnoses || data.recentDiagnoses.length === 0) {
            tableBody.innerHTML = `<tr><td colspan="5" class="text-center">-- Belum ada riwayat screening --</td></tr>`;
            return;
        }

        const currentUserName = '{{ Auth::user()->nama }}';
        let html = '';
        data.recentDiagnoses.forEach(item => {
            const detailUrl = `{{ url('diagnosa/detail') }}/${item.id}`;
            html += `
                <tr>
                    <td>${item.created_at}</td>
                    <td>${currentUserName}</td>
                    <td>${item.gangguan}</td>
                    <td>
                        <span class="badge bg-${item.status_color}-subtle text-${item.status_color}">
                            ${parseFloat(item.percent).toFixed(1)}%
                        </span>
                    </td>
                    <td>
                        <a href="${detailUrl}" class="btn btn-sm btn-primary">
                            <i class="mdi mdi-eye"></i>
                        </a>
                    </td>
                </tr>
            `;
        });
        tableBody.innerHTML = html;
    })
    .catch(error => {
        console.error('Error fetching dashboard data:', error);
        document.getElementById('recent-table-body').innerHTML = `
            <tr>
                <td colspan="5" class="text-center text-danger py-3">
                    Gagal memuat data. Silakan muat ulang halaman.
                </td>
            </tr>
        `;
    });
}
</script>
@endpush
