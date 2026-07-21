@extends('layouts.template')
@section('title', 'Hasil Diagnosa')

@section('content')
    <div class="mb-4 mt-3 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <h2 class="text-2xl font-bold text-slate-800 tracking-tight">Hasil Screening</h2>
            <p class="text-slate-500 text-sm mt-0.5">Analisis sistem pakar berdasarkan gejala yang Anda alami.</p>
        </div>
        <a href="{{ route('diagnosa.form') }}"
            class="btn bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 shadow-sm px-4 py-2 text-sm flex items-center justify-center gap-2 rounded-xl font-medium transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                </path>
            </svg>
            Screening Ulang
        </a>
    </div>

    @if ($konsultasi->is_admin_input && $konsultasi->nama_pasien)
        <div class="card border border-slate-100 shadow-sm rounded-2xl bg-white mb-4">
            <div class="card-body p-4">
                <div class="flex items-center gap-2 mb-3 pb-3 border-b border-slate-100">
                    <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                    <h5 class="font-bold text-slate-800 text-base mb-0">Data Pasien</h5>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <span class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Nama
                            Lengkap</span>
                        <span class="text-slate-700 font-medium">{{ $konsultasi->nama_pasien }}</span>
                    </div>
                    <div>
                        <span class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Umur
                            Pasien</span>
                        <span class="text-slate-700 font-medium">{{ $konsultasi->umur }} tahun</span>
                    </div>
                    <div>
                        <span class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Jenis
                            Kelamin</span>
                        <span
                            class="text-slate-700 font-medium">{{ $konsultasi->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</span>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Penjelasan Singkat -->
    <div class="bg-blue-50 border-l-4 border-blue-500 rounded-r-xl p-4 mb-4 shadow-sm">
        <div class="flex items-start gap-3">
            <div class="text-blue-500 shrink-0 mt-0.5">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div>
                <h6 class="font-bold text-blue-900 text-sm mb-1">Informasi Analisis</h6>
                <p class="text-sm text-blue-800 mb-2">Berdasarkan <strong>{{ count($konsultasi->gejala_terpilih) }}
                        gejala</strong> yang Anda pilih, sistem telah menganalisis kemungkinan gangguan menggunakan 2 metode
                    perhitungan pakar:</p>
                <ol class="text-sm text-blue-800 list-decimal list-inside space-y-1 mb-0 opacity-90">
                    <li><strong>Metode VCIRS</strong>: Mengukur keterkaitan dan prioritas gejala dengan masing-masing
                        gangguan.</li>
                    <li><strong>Metode CF</strong>: Menghitung tingkat kepastian (Certainty Factor) berdasarkan penilaian
                        pakar.</li>
                </ol>
            </div>
        </div>
    </div>

    <!-- Accordion Hasil -->
    <div class="accordion custom-accordion mb-4" id="hasilDiagnosa">
        @foreach ($sortedHasil as $index => $row)
            @php
                $isTop = $index === 0;
            @endphp
            <div class="accordion-item bg-white border border-slate-100 shadow-sm rounded-2xl mb-3 overflow-hidden">
                <h2 class="accordion-header">
                    <button
                        class="accordion-button {{ !$isTop ? 'collapsed' : '' }} p-4 bg-white hover:bg-slate-50 transition-colors"
                        type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $index }}"
                        aria-expanded="{{ $isTop ? 'true' : 'false' }}" aria-controls="collapse{{ $index }}">

                        <div class="flex flex-col sm:flex-row sm:items-center justify-between w-full pr-4 gap-3">
                            <div class="flex items-center gap-3">
                                <div>
                                    <span
                                        class="text-xs font-mono font-bold text-slate-400 block leading-tight">{{ $row['penyakit']->kode_penyakit }}</span>
                                    <h5 class="font-bold text-slate-800 text-base mb-0 leading-tight">
                                        {{ $row['penyakit']->nama_penyakit }}</h5>
                                </div>
                            </div>
                            <div class="flex items-center gap-3">
                                <div class="hidden sm:block text-right">
                                    <span class="text-xs text-slate-400 block font-medium">Tingkat Kepastian</span>
                                </div>
                                <span
                                    class="inline-flex items-center px-3 py-1 rounded-full text-sm font-bold border shadow-sm {{ $row['percent'] >= ($sortedHasil->first()['percent'] ?? 0) ? 'bg-red-50 text-red-700 border-red-200' : 'bg-slate-50 text-slate-700 border-slate-200' }}">
                                    {{ number_format($row['percent'], 2) }}%
                                </span>
                            </div>
                        </div>
                    </button>
                </h2>

                <div id="collapse{{ $index }}" class="accordion-collapse collapse {{ $isTop ? 'show' : '' }}"
                    data-bs-parent="#hasilDiagnosa">
                    <div class="accordion-body p-0 border-t border-slate-100">

                        <!-- Header Detail -->
                        <div class="p-4 bg-slate-50 border-b border-slate-100 flex items-center gap-2">
                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span class="font-bold text-slate-600 text-xs uppercase tracking-wider">Detail Diagnosis &
                                Solusi</span>
                        </div>

                        <div class="px-5 py-3">
                            <!-- Info Penyakit (Deskripsi & Solusi) -->
                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-6">
                                <div class="bg-blue-50/50 border border-blue-100 rounded-xl p-4 flex flex-col">
                                    <div class="flex items-center gap-2 mb-3">
                                        <div
                                            class="w-8 h-8 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center shrink-0">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                        </div>
                                        <h6 class="font-bold text-blue-900 text-sm mb-0">Deskripsi Gangguan</h6>
                                    </div>
                                    <p class="text-sm text-slate-700 leading-relaxed mb-0 flex-grow">{!! nl2br(e($row['penyakit']->deskripsi)) !!}
                                    </p>
                                </div>
                                <div class="bg-emerald-50/50 border border-emerald-100 rounded-xl p-4 flex flex-col">
                                    <div class="flex items-center gap-2 mb-3">
                                        <div
                                            class="w-8 h-8 rounded-lg bg-emerald-100 text-emerald-600 flex items-center justify-center shrink-0">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                        </div>
                                        <h6 class="font-bold text-emerald-900 text-sm mb-0">Solusi & Penanganan</h6>
                                    </div>
                                    <p class="text-sm text-slate-700 leading-relaxed mb-0 flex-grow">
                                        {!! nl2br(e($row['penyakit']->solusi)) !!}</p>
                                </div>
                            </div>

                            <hr class="border-slate-100 my-2">

                            <!-- Rincian Kalkulasi -->
                            <h6 class="font-bold text-slate-800 text-base mb-4 flex items-center gap-2">
                                <svg class="w-5 h-5 text-secondary" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z">
                                    </path>
                                </svg>
                                Logika Perhitungan Sistem Pakar
                            </h6>

                            <!-- Proses VCIRS -->
                            <div class="mb-5">
                                <h6 class="font-bold text-slate-700 text-sm mb-3">1. Proses VCIRS <span
                                        class="text-xs font-normal text-slate-400 ml-1">(Variable-Centered Intelligent Rule
                                        System)</span></h6>

                                <div class="bg-white border border-slate-200 rounded-xl overflow-hidden shadow-sm">
                                    <div class="table-responsive">
                                        <table class="table table-custom w-full align-middle mb-0 text-sm">
                                            <thead>
                                                <tr class="bg-slate-50 text-slate-500 text-xs uppercase tracking-wider">
                                                    <th class="py-3 px-3 border-b border-slate-200">Gejala</th>
                                                    <th class="py-3 px-3 text-center border-b border-slate-200">
                                                        Credit<br><span
                                                            class="text-[9px] font-normal text-slate-400">User</span></th>
                                                    <th class="py-3 px-3 text-center border-b border-slate-200">NS<br><span
                                                            class="text-[9px] font-normal text-slate-400">Total</span></th>
                                                    <th class="py-3 px-3 text-center border-b border-slate-200">VO<br><span
                                                            class="text-[9px] font-normal text-slate-400">Urutan</span>
                                                    </th>
                                                    <th class="py-3 px-3 text-center border-b border-slate-200">TV<br><span
                                                            class="text-[9px] font-normal text-slate-400">Valid</span></th>
                                                    <th class="py-3 px-3 text-center border-b border-slate-200">CD<br><span
                                                            class="text-[9px] font-normal text-slate-400">VO/TV</span></th>
                                                    <th class="py-3 px-3 text-center border-b border-slate-200">
                                                        Weight<br><span
                                                            class="text-[9px] font-normal text-slate-400">NS*CD</span></th>
                                                    <th class="py-3 px-3 text-center border-b border-slate-200">
                                                        VUR<br><span
                                                            class="text-[9px] font-normal text-slate-400">Cr*Wt</span></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php
                                                    $totalVUR = 0;
                                                    $totalTV = 0;
                                                @endphp
                                                @foreach ($row['detail'] as $gejalaId => $detail)
                                                    @php
                                                        $totalVUR += $detail['vur'];
                                                        $totalTV = $detail['tv'];
                                                    @endphp
                                                    <tr
                                                        class="{{ $detail['credit'] > 0 ? 'bg-emerald-50/30' : '' }} hover:bg-slate-50 transition-colors">
                                                        <td
                                                            class="py-2.5 px-3 relative {{ $detail['credit'] > 0 ? 'border-l-4 border-l-emerald-500' : 'border-l-4 border-l-transparent' }}">
                                                            <div class="flex flex-col">
                                                                <div class="flex items-center gap-1.5">
                                                                    <span
                                                                        class="font-medium text-slate-700">{{ $gejalas->firstWhere('id', $gejalaId)->nama_gejala }}</span>
                                                                    @if ($detail['credit'] > 0)
                                                                        <span
                                                                            class="inline-flex items-center justify-center w-4 h-4 rounded-full bg-emerald-100 text-emerald-600"
                                                                            title="Gejala dialami pengguna">
                                                                            <svg class="w-3 h-3" fill="none"
                                                                                stroke="currentColor" viewBox="0 0 24 24">
                                                                                <path stroke-linecap="round"
                                                                                    stroke-linejoin="round"
                                                                                    stroke-width="3" d="M5 13l4 4L19 7">
                                                                                </path>
                                                                            </svg>
                                                                        </span>
                                                                    @endif
                                                                </div>
                                                                <span
                                                                    class="text-xs text-slate-400 font-mono">{{ $gejalas->firstWhere('id', $gejalaId)->kode_gejala }}</span>
                                                            </div>
                                                        </td>
                                                        <td
                                                            class="py-2.5 px-3 text-center font-bold {{ $detail['credit'] > 0 ? 'text-emerald-600' : 'text-slate-500' }}">
                                                            {{ $detail['credit'] }}</td>
                                                        <td class="py-2.5 px-3 text-center text-slate-500">
                                                            {{ $detail['ns'] }}</td>
                                                        <td class="py-2.5 px-3 text-center text-slate-500">
                                                            {{ $detail['vo'] }}</td>
                                                        <td class="py-2.5 px-3 text-center text-slate-500">
                                                            {{ $detail['tv'] }}</td>
                                                        <td class="py-2.5 px-3 text-center">
                                                            <span
                                                                class="block font-medium text-slate-600">{{ number_format($detail['cd'], 4) }}</span>
                                                        </td>
                                                        <td class="py-2.5 px-3 text-center">
                                                            <span
                                                                class="block font-medium text-slate-600">{{ number_format($detail['weight'], 4) }}</span>
                                                        </td>
                                                        <td class="py-2.5 px-3 text-center font-bold text-slate-800">
                                                            <span
                                                                class="block">{{ number_format($detail['vur'], 4) }}</span>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                            <tfoot class="bg-slate-50 border-t border-slate-200">
                                                <tr>
                                                    <td colspan="7"
                                                        class="py-3 px-3 text-right font-bold text-slate-600 text-xs uppercase tracking-wider">
                                                        Total VUR:</td>
                                                    <td class="py-3 px-3 text-center font-bold text-slate-800 text-base">
                                                        {{ number_format($totalVUR, 4) }}</td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <!-- Proses CF -->
                            <div class="mb-5">
                                <h6 class="font-bold text-slate-700 text-sm mb-3">2. Proses CF <span
                                        class="text-xs font-normal text-slate-400 ml-1">(Certainty Factor)</span> & Hasil
                                    Akhir</h6>

                                <div class="bg-white border border-slate-200 rounded-xl overflow-hidden shadow-sm mb-4">
                                    <div class="table-responsive">
                                        <table class="table table-custom w-full align-middle mb-0 text-sm">
                                            <thead>
                                                <tr class="bg-slate-50 text-slate-500 text-xs uppercase tracking-wider">
                                                    <th class="py-3 px-3 border-b border-slate-200">Gejala Terpilih
                                                        (Dipilih User)</th>
                                                    <th class="py-3 px-3 text-center border-b border-slate-200">CF Pakar
                                                    </th>
                                                    <th class="py-3 px-3 text-center border-b border-slate-200">Credit User
                                                    </th>
                                                    <th class="py-3 px-3 text-center border-b border-slate-200">
                                                        CF(H,E)<br><span
                                                            class="text-[9px] font-normal text-slate-400">Pakar*User</span>
                                                    </th>
                                                    <th
                                                        class="py-3 px-3 text-center text-blue-700 border-b border-slate-200">
                                                        CF * RUR</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php
                                                    $cfCalculations = [];
                                                    $cfCombined = 0;
                                                    $isFirst = true;
                                                @endphp
                                                @foreach ($row['detail'] as $gejalaId => $detail)
                                                    @if ($detail['credit'] > 0)
                                                        @php
                                                            $cfHE = $detail['cf_pakar'] * $detail['credit'];
                                                            $cfWeighted = $cfHE * $row['rur'];

                                                            if ($isFirst) {
                                                                $cfCombined = $cfWeighted;
                                                                $isFirst = false;
                                                            } else {
                                                                $cfCombined =
                                                                    $cfCombined + $cfWeighted * (1 - $cfCombined);
                                                            }

                                                            $cfCalculations[] = [
                                                                'running_total' => $cfCombined,
                                                            ];
                                                        @endphp
                                                        <tr class="hover:bg-slate-50 transition-colors">
                                                            <td class="py-2.5 px-3 font-medium text-slate-700">
                                                                {{ $gejalas->firstWhere('id', $gejalaId)->nama_gejala }}
                                                            </td>
                                                            <td class="py-2.5 px-3 text-center text-slate-600">
                                                                {{ number_format($detail['cf_pakar'], 2) }}</td>
                                                            <td
                                                                class="py-2.5 px-3 text-center font-medium text-emerald-600">
                                                                {{ $detail['credit'] }}</td>
                                                            <td class="py-2.5 px-3 text-center font-medium text-slate-700">
                                                                {{ number_format($cfHE, 4) }}</td>
                                                            <td class="py-2.5 px-3 text-center font-bold text-blue-700">
                                                                {{ number_format($cfWeighted, 4) }}</td>
                                                        </tr>
                                                    @endif
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <!-- Ringkasan Akhir Perhitungan -->
                                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                                    <div class="bg-white border border-slate-200 rounded-xl p-4 text-center shadow-sm">
                                        <span
                                            class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">RUR
                                            <span class="lowercase normal-case font-normal">(Total VUR / TV)</span></span>
                                        <span
                                            class="block text-xl font-bold text-slate-700">{{ number_format($row['rur'], 4) }}</span>
                                    </div>
                                    <div class="bg-white border border-slate-200 rounded-xl p-4 text-center shadow-sm">
                                        <span
                                            class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">CF
                                            Kombinasi Akhir</span>
                                        <span
                                            class="block text-xl font-bold text-slate-700">{{ number_format($cfCombined, 4) }}</span>
                                    </div>
                                    <div
                                        class="{{ $row['percent'] >= 50 ? 'bg-red-50 border-red-200 text-red-700' : 'bg-blue-50 border-blue-200 text-blue-700' }} border rounded-xl p-4 text-center shadow-sm relative overflow-hidden">
                                        <div class="absolute inset-0 opacity-10"
                                            style="background-image: radial-gradient(circle at 100% 100%, currentColor 20%, transparent 80%);">
                                        </div>
                                        <span
                                            class="block text-xs font-bold uppercase tracking-wider mb-1 opacity-80 relative z-10">Persentase
                                            Hasil</span>
                                        <span
                                            class="block text-3xl font-black relative z-10">{{ number_format($row['percent'], 2) }}%</span>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Catatan Tambahan -->
    <div class="bg-amber-50 border-l-4 border-amber-500 rounded-r-xl p-4 mb-4 shadow-sm flex items-start gap-3 mt-8">
        <div class="text-amber-500 shrink-0 mt-0.5">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                </path>
            </svg>
        </div>
        <div>
            <h6 class="font-bold text-amber-900 text-base mb-1">Catatan Penting!</h6>
            <p class="text-sm text-amber-800 mb-0 leading-relaxed">Hasil screening ini hanya berupa gambaran awal
                kemungkinan gangguan tidur yang anda alami berdasarkan gejala yang Anda pilih. <strong>Sistem pakar ini
                    bukan pengganti diagnosis medis resmi.</strong> Tahap selanjutnya, sangat disarankan untuk berkonsultasi
                dengan profesional kesehatan seperti dokter, psikolog atau psikiater untuk diagnosis pasti.</p>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .custom-accordion .accordion-button:not(.collapsed) {
            background-color: #f8fafc;
            /* bg-slate-50 */
            color: #1e293b;
            /* text-slate-800 */
            box-shadow: inset 0 -1px 0 rgba(0, 0, 0, 0.05);
        }

        .custom-accordion .accordion-button:focus {
            box-shadow: none;
            border-color: rgba(0, 0, 0, 0.1);
        }

        .custom-accordion .accordion-button::after {
            background-size: 1rem;
            transition: transform .2s ease-in-out;
        }

        /* Fix for bootstrap table border overriding Tailwind's divide-y */
        .table-custom> :not(caption)>*>* {
            border-bottom-width: 0 !important;
        }

        .table-custom td {
            border-bottom: 1px solid #f1f5f9 !important;
            /* slate-100 */
        }

        .table-custom tr:last-child td {
            border-bottom: none !important;
        }
    </style>
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var myCollapsibles = document.querySelectorAll('#hasilDiagnosa .accordion-collapse');

            myCollapsibles.forEach(function(collapse) {
                collapse.addEventListener('show.bs.collapse', function() {
                    // Close all other open accordion items
                    myCollapsibles.forEach(function(otherCollapse) {
                        if (otherCollapse !== collapse && bootstrap.Collapse.getInstance(
                                otherCollapse)) {
                            bootstrap.Collapse.getInstance(otherCollapse).hide();
                        }
                    });
                });
            });
        });
    </script>
@endpush
