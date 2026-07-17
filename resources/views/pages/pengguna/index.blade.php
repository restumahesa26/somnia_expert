@extends('layouts.template')

@section('title', 'Data Pengguna')

@section('content')
    <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
        <div class="flex-grow-1">
            <h4 class="fs-18 fw-semibold m-0">Data Pengguna</h4>
        </div>

        <div class="text-end">
            <ol class="breadcrumb m-0 py-0">
                <li class="breadcrumb-item active">Pengguna</li>
            </ol>
        </div>
    </div>

    @include('components.flash')

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <a href="{{ route('pengguna.create') }}" class="btn btn-primary">
                        <i class="mdi mdi-plus"></i> Tambah Pengguna
                    </a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive p-3">
                        <table id="table-pengguna" class="table table-striped table-bordered align-middle w-100">
                            <thead>
                                <tr>
                                    <th style="width:60px">No</th>
                                    <th>Nama</th>
                                    <th>Username</th>
                                    <th>Email</th>
                                    <th style="width:120px">Role</th>
                                    <th style="width:160px">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $i => $u)
                                    <tr>
                                        <td>{{ $i + 1 }}</td>
                                        <td>{{ $u->nama }}</td>
                                        <td>{{ $u->username }}</td>
                                        <td>{{ $u->email }}</td>
                                        <td>
                                            @if ($u->is_admin)
                                                <span class="badge bg-danger">Admin</span>
                                            @else
                                                <span class="badge bg-secondary">User</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('pengguna.edit', $u->id) }}"
                                                class="btn btn-sm btn-primary">Edit</a>
                                            @php
                                                $confirmMsg =
                                                    $u->konsultasis_count > 0
                                                        ? 'Pengguna ini memiliki riwayat diagnosa. Menghapus pengguna juga akan menghapus semua data diagnosa miliknya. Lanjutkan hapus?'
                                                        : 'Apakah Anda yakin ingin menghapus pengguna ini?';
                                            @endphp
                                            <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal"
                                                data-bs-target="#deleteModal{{ $u->id }}">
                                                Hapus
                                            </button>

                                            <!-- Modal Konfirmasi Hapus -->
                                            <div class="modal fade" id="deleteModal{{ $u->id }}" tabindex="-1"
                                                aria-labelledby="deleteModalLabel{{ $u->id }}" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content" style="white-space: normal;">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title"
                                                                id="deleteModalLabel{{ $u->id }}">Konfirmasi Hapus
                                                            </h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                                aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body text-start">
                                                            {{ $confirmMsg }}
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary"
                                                                data-bs-dismiss="modal">Batal</button>
                                                            <form action="{{ route('pengguna.destroy', $u->id) }}"
                                                                method="POST" class="d-inline">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-danger">Ya,
                                                                    Hapus</button>
                                                            </form>
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
        </div>
    </div>
@endsection

@push('styles')
    {{-- DataTables CSS --}}
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">
@endpush

@push('scripts')
    {{-- jQuery wajib untuk DataTables --}}
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    {{-- DataTables JS --}}
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
                order: [
                    [0, 'asc']
                ],
                columnDefs: [{
                        orderable: false,
                        targets: [5]
                    } // kolom Aksi tidak bisa di-sort
                ],
                language: {
                    url: 'https://cdn.datatables.net/plug-ins/1.13.8/i18n/id.json'
                }
            });

            // Re-number kolom "No" saat sort/search
            table.on('order.dt search.dt', function() {
                let i = 1;
                table.cells(null, 0, {
                    search: 'applied',
                    order: 'applied'
                }).every(function() {
                    this.data(i++);
                });
            }).draw();
        });
    </script>
@endpush
