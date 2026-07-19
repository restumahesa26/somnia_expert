@extends('layouts.template')

@section('title', 'Dashboard Admin')

@section('content')
    <div class="mb-4 mt-3 flex flex-col md:flex-row md:items-center md:justify-between gap-3">
        <div>
            <h2 class="text-3xl font-bold text-slate-800 tracking-tight">Dashboard Admin</h2>
            <p class="text-slate-500 mt-2">Ringkasan agregat seluruh pengguna & data screening SomniaExpert.</p>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="card bg-white mb-8 border-l-4 border-l-secondary shadow-sm">
        <div class="card-body p-3 px-4 flex flex-col md:flex-row md:items-center justify-between gap-3">
            <div class="flex items-center gap-3">
                <div
                    class="w-10 h-10 bg-blue-50 text-secondary rounded-lg flex items-center justify-center shrink-0 shadow-inner">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z">
                        </path>
                    </svg>
                </div>
                <div>
                    <h5 class="font-bold text-slate-800 text-base mb-0">Filter Data</h5>
                    <p class="text-xs text-slate-500 mb-0 mt-0.5">Sesuaikan rentang waktu statistik.</p>
                </div>
            </div>

            <div class="flex flex-col sm:flex-row items-center gap-2 w-full md:w-auto mt-3 md:mt-0">
                <label for="filter-waktu"
                    class="text-xs font-bold text-slate-500 uppercase tracking-wider whitespace-nowrap hidden sm:block">Rentang Waktu:</label>
                <select id="filter-waktu"
                    class="form-select bg-slate-50 border-slate-200 text-sm font-medium w-full sm:w-auto py-1.5 cursor-pointer focus:border-secondary focus:ring-secondary/20 shadow-sm">
                    <option value="7_hari" selected>7 Hari Terakhir</option>
                    <option value="2_minggu">2 Minggu Terakhir</option>
                    <option value="1_bulan">1 Bulan Terakhir</option>
                    <option value="custom">Kustom Rentang</option>
                </select>
            </div>
        </div>

        <div id="custom-date-container" class="hidden border-t border-slate-100 bg-slate-50 p-5 rounded-b-2xl">
            <div class="flex flex-col sm:flex-row items-end justify-end gap-4">
                <div class="w-full sm:w-auto">
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Tanggal
                        Mulai</label>
                    <input type="date" id="start-date" class="form-control text-sm py-2">
                </div>
                <div class="w-full sm:w-auto">
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Tanggal
                        Akhir</label>
                    <input type="date" id="end-date" class="form-control text-sm py-2">
                </div>
                <div class="w-full sm:w-auto">
                    <button id="btn-apply-custom"
                        class="btn btn-primary w-full sm:w-auto py-2 flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Terapkan
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Start Main Widgets -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="card relative overflow-hidden group">
            <div
                class="absolute inset-0 bg-gradient-to-br from-blue-500/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300">
            </div>
            <div class="card-body relative z-10 flex items-center gap-4">
                <div
                    class="w-14 h-14 bg-primary text-white rounded-2xl flex items-center justify-center shadow-lg shadow-blue-500/20 shrink-0">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                        </path>
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-semibold text-slate-500 mb-1">Total Pengguna</p>
                    <h4 class="text-3xl font-bold text-slate-800" id="stat-total-users">
                        <svg class="animate-spin h-6 w-6 text-primary" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                            </path>
                        </svg>
                    </h4>
                </div>
            </div>
        </div>

        <div class="card relative overflow-hidden group">
            <div
                class="absolute inset-0 bg-gradient-to-br from-secondary/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300">
            </div>
            <div class="card-body relative z-10 flex items-center gap-4">
                <div
                    class="w-14 h-14 bg-secondary text-white rounded-2xl flex items-center justify-center shadow-lg shadow-blue-500/20 shrink-0">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01">
                        </path>
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-semibold text-slate-500 mb-1">Jumlah Screening (Filter)</p>
                    <h4 class="text-3xl font-bold text-slate-800" id="stat-total-diagnoses">
                        <svg class="animate-spin h-6 w-6 text-secondary" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                            </path>
                        </svg>
                    </h4>
                </div>
            </div>
        </div>

        <div class="card relative overflow-hidden group">
            <div
                class="absolute inset-0 bg-gradient-to-br from-green-500/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300">
            </div>
            <div class="card-body relative z-10 flex items-center gap-4">
                <div
                    class="w-14 h-14 bg-green-500 text-white rounded-2xl flex items-center justify-center shadow-lg shadow-green-500/20 shrink-0">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                        </path>
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-semibold text-slate-500 mb-1">Screening Hari Ini</p>
                    <h4 class="text-3xl font-bold text-slate-800" id="stat-today-diagnoses">
                        <svg class="animate-spin h-6 w-6 text-green-500" xmlns="http://www.w3.org/2000/svg"
                            fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                            </path>
                        </svg>
                    </h4>
                </div>
            </div>
        </div>
    </div>
    <!-- End Main Widgets -->

    <!-- Trend Chart -->
    <div class="card">
        <div class="card-header border-b border-slate-100 px-6 py-5">
            <h5 class="text-lg font-bold text-slate-800">Tren Rata-rata Persentase Screening</h5>
        </div>
        <div class="card-body p-6">
            <div id="trend-chart-loader" class="flex flex-col items-center justify-center py-16">
                <svg class="animate-spin h-10 w-10 text-secondary mb-4" xmlns="http://www.w3.org/2000/svg" fill="none"
                    viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                        stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor"
                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                    </path>
                </svg>
                <p class="text-slate-500 font-medium">Menyiapkan grafik data...</p>
            </div>
            <div id="trend-chart" class="w-full" style="min-height: 280px; display: none;"></div>
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
                    customDateContainer.classList.remove('hidden');
                } else {
                    customDateContainer.classList.add('hidden');
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
                        height: 320,
                        toolbar: {
                            show: false
                        },
                        fontFamily: '"Plus Jakarta Sans", sans-serif',
                    },
                    colors: ['#3B82F6'],
                    xaxis: {
                        categories: [],
                        labels: {
                            style: {
                                colors: '#64748b'
                            }
                        },
                        axisBorder: {
                            show: false
                        },
                        axisTicks: {
                            show: false
                        }
                    },
                    yaxis: {
                        labels: {
                            style: {
                                colors: '#64748b'
                            }
                        }
                    },
                    grid: {
                        borderColor: '#f1f5f9',
                        strokeDashArray: 4,
                        yaxis: {
                            lines: {
                                show: true
                            }
                        }
                    },
                    stroke: {
                        width: 4,
                        curve: 'smooth'
                    },
                    markers: {
                        size: 5,
                        colors: ['#ffffff'],
                        strokeColors: '#3B82F6',
                        strokeWidth: 2,
                        hover: {
                            size: 7
                        }
                    },
                    tooltip: {
                        theme: 'light',
                        y: {
                            formatter: function(val) {
                                return val + "%"
                            }
                        }
                    }
                };
                trendChart = new ApexCharts(document.querySelector("#trend-chart"), options);
                trendChart.render();
            };
            initChart();

            const fetchAdminData = (filter, startDate = null, endDate = null) => {
                // Show loading indicators
                const spinner =
                    `<svg class="animate-spin h-6 w-6 text-slate-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>`;

                document.getElementById('stat-total-users').innerHTML = spinner;
                document.getElementById('stat-total-diagnoses').innerHTML = spinner;
                document.getElementById('stat-today-diagnoses').innerHTML = spinner;

                document.getElementById('trend-chart').style.display = 'none';
                document.getElementById('trend-chart-loader').style.display = 'flex';

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
                        if (data.error) {
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
