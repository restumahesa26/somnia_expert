@extends('layouts.template')

@section('title', 'Dashboard')

@section('content')
    <div class="mb-4 mt-3">
        <h2 class="text-3xl font-bold text-slate-800 tracking-tight">Halo,
            {{ \App\Helpers\Helper::getFirstName(Auth::user()->nama) }}! 👋</h2>
        <p class="text-slate-500 mt-2">Selamat datang di dashboard SomniaExpert. Berikut adalah ringkasan hasil screening
            tidur Anda.</p>
    </div>

    <!-- Start Main Widgets -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">

        <!-- Widget 1 -->
        <div class="card relative overflow-hidden group">
            <div
                class="absolute top-0 right-0 w-32 h-32 bg-primary/5 rounded-full -translate-y-1/2 translate-x-1/2 group-hover:scale-150 transition-transform duration-500">
            </div>
            <div class="card-body relative z-10 flex items-center justify-between">
                <div>
                    <p class="text-sm font-semibold text-slate-500 uppercase tracking-wider mb-1">Total Screening</p>
                    <h3 class="text-4xl font-black text-slate-800" id="total-diagnoses"><i
                            class="mdi mdi-spin mdi-loading fs-4"></i></h3>
                </div>
                <div class="w-16 h-16 bg-blue-50 text-secondary rounded-2xl flex items-center justify-center shadow-inner">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                        </path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Widget 2 -->
        <div class="card relative overflow-hidden group">
            <div
                class="absolute top-0 right-0 w-32 h-32 bg-secondary/5 rounded-full -translate-y-1/2 translate-x-1/2 group-hover:scale-150 transition-transform duration-500">
            </div>
            <div class="card-body relative z-10 flex items-center justify-between">
                <div class="flex-1 pr-2">
                    <p class="text-sm font-semibold text-slate-500 uppercase tracking-wider mb-1">Hasil Terakhir</p>
                    <h3 class="text-lg font-bold text-slate-800 mt-1 leading-tight" id="last-result"><i
                            class="mdi mdi-spin mdi-loading fs-5"></i></h3>
                </div>
                <div
                    class="w-16 h-16 bg-blue-50 text-blue-500 rounded-2xl flex items-center justify-center shadow-inner flex-shrink-0 ml-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Widget 3 -->
        <div class="card relative overflow-hidden group">
            <div
                class="absolute top-0 right-0 w-32 h-32 bg-accent/5 rounded-full -translate-y-1/2 translate-x-1/2 group-hover:scale-150 transition-transform duration-500">
            </div>
            <div class="card-body relative z-10 flex items-center justify-between">
                <div>
                    <p class="text-sm font-semibold text-slate-500 uppercase tracking-wider mb-1">Tgl Terakhir</p>
                    <h3 class="text-xl font-bold text-slate-800 mt-1" id="last-date"><i
                            class="mdi mdi-spin mdi-loading fs-5"></i></h3>
                </div>
                <div
                    class="w-16 h-16 bg-purple-50 text-accent rounded-2xl flex items-center justify-center shadow-inner flex-shrink-0 ml-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                        </path>
                    </svg>
                </div>
            </div>
        </div>

    </div>
    <!-- End Main Widgets -->

    <!-- Recent History Table -->
    <div class="card">
        <div class="card-header flex justify-between items-center">
            <h5 class="text-lg font-bold text-slate-800">Riwayat 10 Screening Terakhir</h5>
            <a href="{{ route('diagnosa.form') }}" class="btn btn-primary text-sm px-4 py-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6">
                    </path>
                </svg>
                Screening Baru
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="table w-full">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Nama</th>
                        <th>Gangguan Utama</th>
                        <th>Persentase</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody id="recent-table-body">
                    <tr>
                        <td colspan="5" class="text-center py-10">
                            <svg class="animate-spin h-8 w-8 text-secondary mx-auto mb-4" xmlns="http://www.w3.org/2000/svg"
                                fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                </path>
                            </svg>
                            <div class="text-slate-500 font-medium">Memuat data screening...</div>
                        </td>
                    </tr>
                </tbody>
            </table>
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
                    document.getElementById('last-date').textContent = data.lastDiagnosisDate ? data.lastDiagnosisDate :
                        '-';

                    // Update Table
                    const tableBody = document.getElementById('recent-table-body');
                    if (!data.recentDiagnoses || data.recentDiagnoses.length === 0) {
                        tableBody.innerHTML =
                            `<tr><td colspan="5" class="text-center py-10 text-slate-500">Belum ada riwayat screening. Mulai screening pertama Anda sekarang!</td></tr>`;
                        return;
                    }

                    const currentUserName = '{{ Auth::user()->nama }}';
                    let html = '';
                    data.recentDiagnoses.forEach(item => {
                        const detailUrl = `{{ url('diagnosa/detail') }}/${item.id}`;

                        // Map Bootstrap color to Tailwind class
                        let colorClass = 'bg-blue-100 text-blue-800';
                        if (item.status_color === 'danger') colorClass = 'bg-red-100 text-red-800';
                        if (item.status_color === 'warning') colorClass = 'bg-yellow-100 text-yellow-800';
                        if (item.status_color === 'success') colorClass = 'bg-green-100 text-green-800';

                        html += `
                <tr class="hover:bg-slate-50 transition-colors border-b border-slate-100">
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-slate-700">${item.created_at}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600">${currentUserName}</td>
                    <td class="px-6 py-4 text-sm text-slate-600 font-semibold">${item.gangguan}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold ${colorClass}">
                            ${parseFloat(item.percent).toFixed(1)}%
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <a href="${detailUrl}" class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-blue-50 text-secondary hover:bg-secondary hover:text-white transition-colors shadow-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
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
                <td colspan="5" class="text-center text-red-500 py-10 font-semibold">
                    Gagal memuat data. Silakan muat ulang halaman.
                </td>
            </tr>
        `;
                });
        }
    </script>
@endpush
