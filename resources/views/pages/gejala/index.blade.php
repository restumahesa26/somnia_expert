@extends('layouts.template')

@section('title', 'Data Gejala')

@section('content')
<div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
    <div class="flex-grow-1">
        <h4 class="fs-18 fw-semibold m-0">Data Gejala</h4>
    </div>

    <div class="text-end">
        <ol class="breadcrumb m-0 py-0">
            <li class="breadcrumb-item active">Gejala</li>
        </ol>
    </div>
</div>

@include('components.flash')

<!-- Datatables  -->
<div class="row">
    <div class="col-12">
        <div class="card">

            <div class="card-header">
                <a href="{{ route('gejala.create') }}" class="btn btn-primary">
                    <i class="mdi mdi-plus"></i> Tambah Gejala
                </a>
            </div><!-- end card header -->

            <div class="card-body">
                <table id="datatable" class="table table-bordered dt-responsive table-responsive nowrap">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kode</th>
                            <th>Nama</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($items as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $item->kode_gejala }}</td>
                                <td>{{ $item->nama_gejala }}</td>
                                <td>
                                    <a href="{{ route('gejala.edit', $item->id) }}" class="btn btn-info btn-sm">
                                        <span class="mdi mdi-pencil"></span>
                                    </a>
                                    <button type="button" class="btn btn-danger btn-sm"
                                        onclick="handleDelete('{{ $item->id }}', '{{ $item->nama_gejala }}')">
                                        <span class="mdi mdi-delete"></span>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center">
                                    -- Data Kosong --
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <!-- Delete Modal -->
                <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="deleteModalLabel">Konfirmasi Hapus</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                Apakah Anda yakin ingin menghapus gejala <span id="gejalaName"></span>?
                            </div>
                            <div class="modal-footer">
                                <form id="deleteForm" method="POST">
                                    @csrf
                                    @method('delete')
                                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                                    <button type="submit" class="btn btn-danger">Hapus</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">
@endpush

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>

    <script>
        $(function () {
            const table = $('#datatable').DataTable({
                responsive: true,
                lengthChange: true,
                pageLength: 10,
                autoWidth: false,
                order: [[0, 'asc']],
                columnDefs: [
                    { orderable: false, targets: [3] } // kolom Aksi tidak bisa di-sort
                ],
                language: {
                    url: 'https://cdn.datatables.net/plug-ins/1.13.8/i18n/id.json'
                }
            });

            // Re-number kolom "No" saat sort/search
            table.on('order.dt search.dt', function () {
                let i = 1;
                table.cells(null, 0, { search: 'applied', order: 'applied' }).every(function () {
                    this.data(i++);
                });
            }).draw();
        });
        function handleDelete(id, name) {
            const deleteForm = document.getElementById('deleteForm');
            const gejalaName = document.getElementById('gejalaName');

            deleteForm.action = `{{ url('gejala') }}/${id}`;
            gejalaName.textContent = name;

            $('#deleteModal').modal('show');
        }
    </script>
@endpush
