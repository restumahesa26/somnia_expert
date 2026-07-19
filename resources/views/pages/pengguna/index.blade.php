@extends('layouts.template')

@section('title', 'Data Pengguna')

@section('content')
    <div class="mb-4 mt-3 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <h2 class="text-2xl font-bold text-slate-800 tracking-tight">Data Pengguna</h2>
            <p class="text-slate-500 text-sm mt-0.5">Kelola akun pengguna dan admin pada sistem.</p>
        </div>
        <a href="{{ route('pengguna.create') }}"
            class="btn btn-primary shadow-md shadow-blue-500/15 px-4 py-2 text-sm flex items-center gap-1.5">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            Tambah Pengguna
        </a>
    </div>

    @include('components.flash')

    <div class="card border border-slate-100 shadow-sm rounded-2xl overflow-hidden bg-white mb-4">
        <div class="card-body p-4 pt-3">
            <div class="table-responsive">
                <table id="table-pengguna" class="table w-full align-middle">
                    <thead>
                        <tr class="bg-slate-50 text-slate-500 text-xs uppercase font-bold tracking-wider border-b border-slate-100">
                            <th class="py-3 px-3 text-center w-14">No</th>
                            <th class="py-3 px-3">Nama</th>
                            <th class="py-3 px-3">Username</th>
                            <th class="py-3 px-3">Email</th>
                            <th class="py-3 px-3 text-center w-24">Role</th>
                            <th class="py-3 px-3 text-center w-28">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-sm">
                        @foreach ($users as $i => $u)
                            <tr class="hover:bg-slate-50/80 transition-colors">
                                <td class="py-2.5 px-3 text-center font-medium text-slate-500">{{ $i + 1 }}</td>
                                <td class="py-2.5 px-3 font-medium text-slate-700">{{ $u->nama }}</td>
                                <td class="py-2.5 px-3 text-slate-600">{{ $u->username }}</td>
                                <td class="py-2.5 px-3 text-slate-600">{{ $u->email }}</td>
                                <td class="py-2.5 px-3 text-center">
                                    @if ($u->is_admin)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-red-50 text-red-700 border border-red-200">Admin</span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-slate-100 text-slate-600 border border-slate-200">User</span>
                                    @endif
                                </td>
                                <td class="py-2.5 px-3 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('pengguna.edit', $u->id) }}"
                                            class="w-8 h-8 rounded-lg bg-blue-50 text-secondary hover:bg-secondary hover:text-white flex items-center justify-center transition-colors shadow-sm"
                                            title="Edit Pengguna">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                        </a>
                                        @php
                                            $confirmMsg =
                                                $u->konsultasis_count > 0
                                                    ? 'Pengguna ini memiliki riwayat diagnosa. Menghapus pengguna juga akan menghapus semua data diagnosa miliknya. Lanjutkan hapus?'
                                                    : 'Apakah Anda yakin ingin menghapus pengguna ini?';
                                        @endphp
                                        <button type="button"
                                            class="w-8 h-8 rounded-lg bg-red-50 text-red-600 hover:bg-red-600 hover:text-white flex items-center justify-center transition-colors shadow-sm border-0 cursor-pointer"
                                            data-bs-toggle="modal" data-bs-target="#deleteModal{{ $u->id }}"
                                            title="Hapus Pengguna">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>

                                        <!-- Modal Konfirmasi Hapus -->
                                        <div class="modal fade" id="deleteModal{{ $u->id }}" tabindex="-1"
                                            aria-labelledby="deleteModalLabel{{ $u->id }}" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content border-0 shadow-2xl rounded-2xl overflow-hidden" style="white-space: normal;">
                                                    <div class="modal-header bg-slate-50 px-6 py-4 border-b border-slate-100">
                                                        <div class="flex items-center gap-3">
                                                            <div class="w-9 h-9 rounded-xl bg-red-100 text-red-600 flex items-center justify-center shrink-0">
                                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                                                </svg>
                                                            </div>
                                                            <h5 class="modal-title font-bold text-slate-800 text-base" id="deleteModalLabel{{ $u->id }}">Konfirmasi Hapus</h5>
                                                        </div>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body p-6 text-start">
                                                        <p class="text-slate-600">{{ $confirmMsg }}</p>
                                                    </div>
                                                    <div class="modal-footer bg-slate-50 px-6 py-4 border-t border-slate-100 flex justify-end gap-3">
                                                        <button type="button"
                                                            class="btn bg-white border border-slate-200 text-slate-700 hover:bg-slate-100 shadow-none px-4 py-2 text-sm font-semibold rounded-xl"
                                                            data-bs-dismiss="modal">Batal</button>
                                                        <form action="{{ route('pengguna.destroy', $u->id) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit"
                                                                class="btn bg-red-600 text-white hover:bg-red-700 shadow-md shadow-red-500/20 px-4 py-2 text-sm font-semibold rounded-xl border-0">Ya, Hapus</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
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
        $(function() {
            const table = $('#table-pengguna').DataTable({
                responsive: true,
                lengthChange: true,
                pageLength: 10,
                autoWidth: false,
                order: [[0, 'asc']],
                columnDefs: [{ orderable: false, targets: [5] }],
                language: {
                    url: 'https://cdn.datatables.net/plug-ins/1.13.8/i18n/id.json'
                }
            });

            table.on('order.dt search.dt', function() {
                let i = 1;
                table.cells(null, 0, { search: 'applied', order: 'applied' }).every(function() {
                    this.data(i++);
                });
            }).draw();
        });
    </script>
@endpush
