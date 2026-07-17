@extends('layouts.template')

@section('title', 'Dashboard Admin')

@section('content')
<div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
    <div class="flex-grow-1">
        <h4 class="fs-18 fw-semibold m-0">Dashboard Admin</h4>
        <p class="text-muted mb-0">Ringkasan agregat seluruh pengguna & screening.</p>
    </div>
</div>

<!-- Filter Section -->
<div class="card mb-4 border-primary border-opacity-25 shadow-sm">
    <div class="card-body d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 p-3">
        <div class="d-flex align-items-center gap-3">
            <div class="bg-primary bg-opacity-10 p-2 rounded text-primary d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                <i class="mdi mdi-filter-variant fs-3"></i>
            </div>
            <div>
                <h5 class="mb-0 fs-15 fw-semibold">Filter Data Dashboard</h5>
                <p class="text-muted mb-0 fs-13">Sesuaikan rentang waktu untuk menampilkan statistik.</p>
            </div>
        </div>
        
        <div class="d-flex align-items-center gap-2">
            <label for="filter-waktu" class="mb-0 fw-medium text-nowrap d-none d-sm-block">Rentang Waktu:</label>
            <select id="filter-waktu" class="form-select border-primary shadow-sm" style="min-width: 180px;">
                <option value="7_hari" selected>7 Hari Terakhir</option>
                <option value="2_minggu">2 Minggu Terakhir</option>
                <option value="1_bulan">1 Bulan Terakhir</option>
                <option value="custom">Kustom Rentang</option>
            </select>
        </div>
    </div>
    
    <div id="custom-date-container" class="card-footer bg-light d-none border-top">
        <div class="row g-2 align-items-end justify-content-md-end">
            <div class="col-12 col-sm-auto">
                <label class="form-label mb-1 fs-13">Tanggal Mulai</label>
                <input type="date" id="start-date" class="form-control">
            </div>
            <div class="col-12 col-sm-auto">
                <label class="form-label mb-1 fs-13">Tanggal Akhir</label>
                <input type="date" id="end-date" class="form-control">
            </div>
            <div class="col-12 col-sm-auto">
                <button id="btn-apply-custom" class="btn btn-primary shadow-sm w-100"><i class="mdi mdi-check me-1"></i> Terapkan</button>
            </div>
        </div>
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
                    <p class="text-muted mb-1">Total Pengguna Keseluruhan</p>
                    <h4 class="mb-0" id="stat-total-users">
                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                    </h4>
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
                    <p class="text-muted mb-1">Jumlah Screening (Filter)</p>
                    <h4 class="mb-0" id="stat-total-diagnoses">
                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                    </h4>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-body d-flex align-items-center">
                <div class="me-3">
                    <i class="mdi mdi-calendar-today fs-20 text-success"></i>
                </div>
                <div>
                    <p class="text-muted mb-1">Screening Hari Ini</p>
                    <h4 class="mb-0" id="stat-today-diagnoses">
                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                    </h4>
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
                <h5 class="card-title mb-0">Tren Rata-rata Persentase Screening</h5>
            </div>
            <div class="card-body">
                <div id="trend-chart-loader" class="text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
                <div id="trend-chart" class="apex-charts" style="min-height: 280px; display: none;"></div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="{{ url('dist/assets/libs/apexcharts/apexcharts.min.js') }}"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        let trendChart = null;

        const filterSelect = document.getElementById('filter-waktu');
        const customDateContainer = document.getElementById('custom-date-container');
        const btnApplyCustom = document.getElementById('btn-apply-custom');
        const startDateInput = document.getElementById('start-date');
        const endDateInput = document.getElementById('end-date');

        filterSelect.addEventListener('change', function() {
            if (this.value === 'custom') {
                customDateContainer.classList.remove('d-none');
            } else {
                customDateContainer.classList.add('d-none');
                fetchAdminData(this.value);
            }
        });

        btnApplyCustom.addEventListener('click', function() {
            if (!startDateInput.value || !endDateInput.value) {
                alert('Pilih tanggal mulai dan tanggal akhir');
                return;
            }
            fetchAdminData('custom', startDateInput.value, endDateInput.value);
        });

        // Initialize empty chart
        const initChart = () => {
            const options = {
                series: [{
                    name: 'Rata-rata Persentase',
                    data: []
                }],
                chart: {
                    type: 'line',
                    height: 280,
                    toolbar: { show: false }
                },
                xaxis: {
                    categories: []
                },
                stroke: { width: 3, curve: 'smooth' },
                markers: { size: 3 }
            };
            trendChart = new ApexCharts(document.querySelector("#trend-chart"), options);
            trendChart.render();
        };
        initChart();

        const fetchAdminData = (filter, startDate = null, endDate = null) => {
            // Show loading
            document.getElementById('stat-total-users').innerHTML = '<span class="spinner-border spinner-border-sm"></span>';
            document.getElementById('stat-total-diagnoses').innerHTML = '<span class="spinner-border spinner-border-sm"></span>';
            document.getElementById('stat-today-diagnoses').innerHTML = '<span class="spinner-border spinner-border-sm"></span>';
            
            document.getElementById('trend-chart').style.display = 'none';
            document.getElementById('trend-chart-loader').style.display = 'block';

            let url = `{{ route('dashboard.admin-data') }}?filter=${filter}`;
            if (filter === 'custom') {
                url += `&start_date=${startDate}&end_date=${endDate}`;
            }

            fetch(url, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(res => res.json())
            .then(data => {
                if(data.error) {
                    alert(data.error);
                    return;
                }
                
                // Update stats
                document.getElementById('stat-total-users').innerText = data.totalUsers;
                document.getElementById('stat-total-diagnoses').innerText = data.totalDiagnoses;
                document.getElementById('stat-today-diagnoses').innerText = data.todayDiagnoses;

                // Update chart
                trendChart.updateOptions({
                    xaxis: {
                        categories: data.chartDates
                    }
                });
                trendChart.updateSeries([{
                    name: 'Rata-rata Persentase',
                    data: data.chartSeverity
                }]);

                document.getElementById('trend-chart-loader').style.display = 'none';
                document.getElementById('trend-chart').style.display = 'block';
            })
            .catch(err => {
                console.error(err);
                alert("Gagal mengambil data dari server");
            });
        };

        // Fetch initial data
        fetchAdminData(filterSelect.value);
    });
</script>
@endpush
