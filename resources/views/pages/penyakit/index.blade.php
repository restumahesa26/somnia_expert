@extends('layouts.template')

@section('title', 'Data Penyakit')

@section('content')
    <div class="mb-4 mt-3 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <h2 class="text-2xl font-bold text-slate-800 tracking-tight">Data Penyakit</h2>
            <p class="text-slate-500 text-sm mt-0.5">Kelola data penyakit gangguan tidur dalam sistem pakar.</p>
        </div>
        <a href="{{ route('penyakit.create') }}"
            class="btn btn-primary shadow-md shadow-blue-500/15 px-4 py-2 text-sm flex items-center gap-1.5">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            Tambah Penyakit
        </a>
    </div>

    @include('components.flash')

    <div class="card border border-slate-100 shadow-sm rounded-2xl overflow-hidden bg-white mb-4">
        <div class="card-body p-4 pt-3">
            <div class="table-responsive">
                <table id="datatable" class="table w-full align-middle datatable">
                    <thead>
                        <tr class="bg-slate-50 text-slate-500 text-xs uppercase font-bold tracking-wider border-b border-slate-100">
                            <th class="py-3 px-3 text-center w-14">No</th>
                            <th class="py-3 px-3 w-28">Kode</th>
                            <th class="py-3 px-3">Nama Penyakit</th>
                            <th class="py-3 px-3 text-center w-24">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-sm">
                        @forelse ($items as $item)
                            <tr class="hover:bg-slate-50/80 transition-colors">
                                <td class="py-2.5 px-3 text-center font-medium text-slate-500">{{ $loop->iteration }}</td>
                                <td class="py-2.5 px-3">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-mono font-bold bg-slate-100 text-slate-700 border border-slate-200">
                                        {{ $item->kode_penyakit }}
                                    </span>
                                </td>
                                <td class="py-2.5 px-3 font-medium text-slate-700">{{ $item->nama_penyakit }}</td>
                                <td class="py-2.5 px-3 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('penyakit.edit', $item->id) }}"
                                            class="w-8 h-8 rounded-lg bg-blue-50 text-secondary hover:bg-secondary hover:text-white flex items-center justify-center transition-colors shadow-sm"
                                            title="Edit Penyakit">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                        </a>
                                        <button type="button"
                                            class="w-8 h-8 rounded-lg bg-red-50 text-red-600 hover:bg-red-600 hover:text-white flex items-center justify-center transition-colors shadow-sm border-0 cursor-pointer"
                                            onclick="handleDelete('{{ $item->id }}', '{{ $item->nama_penyakit }}', {{ $item->gejalas_count }})"
                                            title="Hapus Penyakit">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-6 text-slate-400 font-medium text-sm">
                                    Belum ada data penyakit. Silakan tambah data penyakit baru.
                                </td>
                            </tr>
                        @endforelse
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
                    <p class="text-slate-600 mb-2">Apakah Anda yakin ingin menghapus penyakit <strong id="penyakitName" class="text-slate-800"></strong>?</p>
                    <div id="warningPenyakitGejala"
                        class="hidden bg-amber-50 border border-amber-200 rounded-xl p-3.5 mt-3 text-amber-800 text-sm flex items-start gap-2.5">
                        <svg class="w-5 h-5 text-amber-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                        <span><strong>Peringatan:</strong> Menghapus penyakit ini akan menghapus data pada bobot gejala yang terkait dengannya.</span>
                    </div>
                </div>
                <div class="modal-footer bg-slate-50 px-6 py-4 border-t border-slate-100 flex justify-end gap-3">
                    <form id="deleteForm" method="POST">
                        @csrf
                        @method('delete')
                        <button type="button"
                            class="btn bg-white border border-slate-200 text-slate-700 hover:bg-slate-100 shadow-none px-4 py-2 text-sm font-semibold rounded-xl"
                            data-bs-dismiss="modal">Batal</button>
                        <button type="submit"
                            class="btn bg-red-600 text-white hover:bg-red-700 shadow-md shadow-red-500/20 px-4 py-2 text-sm font-semibold rounded-xl border-0">Hapus Penyakit</button>
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
        $(function() {
            const table = $('#datatable').DataTable({
                responsive: true,
                lengthChange: true,
                pageLength: 10,
                autoWidth: false,
                order: [[0, 'asc']],
                columnDefs: [{ orderable: false, targets: [3] }],
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

        function handleDelete(id, name, relationCount) {
            const deleteForm = document.getElementById('deleteForm');
            const penyakitName = document.getElementById('penyakitName');
            const warningEl = document.getElementById('warningPenyakitGejala');

            deleteForm.action = `{{ url('penyakit') }}/${id}`;
            penyakitName.textContent = name;

            if (relationCount > 0) {
                warningEl.classList.remove('hidden');
            } else {
                warningEl.classList.add('hidden');
            }

            $('#deleteModal').modal('show');
        }
    </script>
@endpush
