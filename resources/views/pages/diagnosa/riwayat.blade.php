@extends('layouts.template')

@section('title', 'Riwayat Diagnosa')

@section('content')
    <div class="mb-4 mt-3 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <h2 class="text-2xl font-bold text-slate-800 tracking-tight">Riwayat Screening</h2>
            <p class="text-slate-500 text-sm mt-0.5">Daftar riwayat hasil screening gangguan tidur yang telah dilakukan.</p>
        </div>
    </div>

    @include('components.flash')

    <div class="card border border-slate-100 shadow-sm rounded-2xl overflow-hidden bg-white mb-4">
        <div class="card-body p-4 pt-3">
            <div class="table-responsive">
                <table id="datatable" class="table w-full align-middle datatable">
                    <thead>
                        <tr class="bg-slate-50 text-slate-500 text-xs uppercase font-bold tracking-wider border-b border-slate-100">
                            <th class="py-3 px-3">Tanggal</th>
                            @if (Auth::user()->is_admin)
                                <th class="py-3 px-3">Nama</th>
                            @endif
                            <th class="py-3 px-3 text-center">Gejala Dipilih</th>
                            <th class="py-3 px-3">Hasil Teratas</th>
                            <th class="py-3 px-3 text-center">Persentase</th>
                            <th class="py-3 px-3 text-center w-28">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-sm">
                        @foreach($riwayat as $item)
                            @php
                                $hasilTeratas = collect($item->hasil)->sortByDesc('percent')->first();
                            @endphp
                            <tr class="hover:bg-slate-50/80 transition-colors">
                                <td class="py-2.5 px-3 text-slate-700 font-medium whitespace-nowrap">
                                    {{ \App\Helpers\Helper::formatDate($item->created_at, true) }}
                                </td>
                                @if (Auth::user()->is_admin)
                                    <td class="py-2.5 px-3 font-medium text-slate-700">
                                        {{ $item->nama_pasien == '' ? $item->user->nama : $item->nama_pasien }}
                                    </td>
                                @endif
                                <td class="py-2.5 px-3 text-center text-slate-600 font-medium">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-mono bg-slate-100 text-slate-700 border border-slate-200">
                                        {{ count($item->gejala_terpilih) }} Gejala
                                    </span>
                                </td>
                                <td class="py-2.5 px-3 font-semibold text-slate-800">{{ $hasilTeratas['nama'] }}</td>
                                <td class="py-2.5 px-3 text-center">
                                    @if($hasilTeratas['percent'] >= 50)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-red-50 text-red-700 border border-red-200">
                                            {{ number_format($hasilTeratas['percent'], 2) }}%
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-blue-50 text-blue-700 border border-blue-200">
                                            {{ number_format($hasilTeratas['percent'], 2) }}%
                                        </span>
                                    @endif
                                </td>
                                <td class="py-2.5 px-3 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('diagnosa.show', $item->id) }}"
                                            class="w-8 h-8 rounded-lg bg-blue-50 text-secondary hover:bg-secondary hover:text-white flex items-center justify-center transition-colors shadow-sm"
                                            title="Detail Screening">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                        </a>
                                        @if (Auth::user()->is_admin)
                                        <button type="button"
                                            class="w-8 h-8 rounded-lg bg-red-50 text-red-600 hover:bg-red-600 hover:text-white flex items-center justify-center transition-colors shadow-sm border-0 cursor-pointer"
                                            onclick="handleDelete({{ $item->id }})"
                                            title="Hapus Riwayat">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Delete Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content border-0 shadow-2xl rounded-2xl overflow-hidden">
                <div class="modal-header bg-slate-50 px-6 py-4 border-b border-slate-100">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-xl bg-red-100 text-red-600 flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                            </svg>
                        </div>
                        <h5 class="modal-title font-bold text-slate-800 text-base" id="deleteModalLabel">Konfirmasi Hapus</h5>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-6">
                    <p class="text-slate-600 mb-0">Apakah Anda yakin ingin menghapus riwayat screening ini? Tindakan ini tidak dapat dibatalkan.</p>
                </div>
                <div class="modal-footer bg-slate-50 px-6 py-4 border-t border-slate-100 flex justify-end gap-3">
                    <form id="deleteForm" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="button"
                            class="btn bg-white border border-slate-200 text-slate-700 hover:bg-slate-100 shadow-none px-4 py-2 text-sm font-semibold rounded-xl"
                            data-bs-dismiss="modal">Batal</button>
                        <button type="submit"
                            class="btn bg-red-600 text-white hover:bg-red-700 shadow-md shadow-red-500/20 px-4 py-2 text-sm font-semibold rounded-xl border-0">Hapus Riwayat</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">

    <style>
        .dataTables_wrapper .dataTables_paginate .paginate_button.active a {
            background-color: #3b82f6 !important;
            border-color: #3b82f6 !important;
            color: white !important;
            border-radius: 8px !important;
        }
    </style>
@endpush

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#datatable').DataTable({
                responsive: true,
                lengthChange: true,
                pageLength: 10,
                autoWidth: false,
                ordering: false, // Mempertahankan setting sebelumnya agar diurutkan dari backend (terbaru)
                language: {
                    url: 'https://cdn.datatables.net/plug-ins/1.13.8/i18n/id.json'
                }
            });
        });

        function handleDelete(id) {
            const deleteForm = document.getElementById('deleteForm');
            deleteForm.action = `{{ url('diagnosa/hapus') }}/${id}`;
            $('#deleteModal').modal('show');
        }
    </script>
@endpush
