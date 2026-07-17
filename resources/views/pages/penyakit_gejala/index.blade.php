@extends('layouts.template')

@section('title', 'Data Bobot Gejala')

@section('content')
    <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
        <div class="flex-grow-1">
            <h4 class="fs-18 fw-semibold m-0">Data Bobot Gejala</h4>
        </div>

        <div class="text-end">
            <ol class="breadcrumb m-0 py-0">
                <li class="breadcrumb-item active">Bobot Gejala</li>
            </ol>
        </div>
    </div>

    @include('components.flash')

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <a href="{{ route('penyakit-gejala.create') }}" class="btn btn-primary">
                        <i class="mdi mdi-plus"></i> Tambah Bobot
                    </a>
                </div>

                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <select class="form-select" id="filter_penyakit">
                                <option value="">Semua Penyakit</option>
                                @foreach ($penyakits as $penyakit)
                                    <option value="{{ $penyakit->id }}"
                                        {{ request('penyakit_id') == $penyakit->id ? 'selected' : '' }}>
                                        {{ $penyakit->nama_penyakit }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <select class="form-select" id="filter_gejala">
                                <option value="">Semua Gejala</option>
                                @foreach ($gejalas as $gejala)
                                    <option value="{{ $gejala->id }}"
                                        {{ request('gejala_id') == $gejala->id ? 'selected' : '' }}>
                                        {{ $gejala->nama_gejala }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <table id="datatable" class="table table-bordered dt-responsive table-responsive datatable">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Penyakit</th>
                                <th>Gejala</th>
                                {{-- <th class="text-center">Kunci?</th> --}}
                                <th>Bobot</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($items as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $item->penyakit->nama_penyakit }}</td>
                                    <td>{{ $item->gejala->nama_gejala }}</td>
                                    {{-- <td class="text-center">
                                        @if ($item->is_kunci)
                                            <span class="badge bg-success">Ya</span>
                                        @else
                                            <span class="badge bg-secondary">Tidak</span>
                                        @endif
                                    </td> --}}
                                    <td>{{ number_format($item->bobot, 2) }}</td>
                                    <td>
                                        <a href="{{ route('penyakit-gejala.edit', $item->id) }}"
                                            class="btn btn-info btn-sm">
                                            <span class="mdi mdi-pencil"></span>
                                        </a>
                                        <button type="button" class="btn btn-danger btn-sm"
                                            onclick="handleDelete('{{ $item->id }}', '{{ $item->penyakit->nama_penyakit }} - {{ $item->gejala->nama_gejala }}')">
                                            <span class="mdi mdi-delete"></span>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">Data Kosong</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Hapus Data</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Apakah anda yakin ingin menghapus bobot untuk <strong id="deleteItemName"></strong>?</p>
                </div>
                <div class="modal-footer">
                    <form id="deleteForm" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-danger">Hapus</button>
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
        /* Memaksa kolom ke-3 (Gejala) untuk wrap text */
        .datatable tbody td:nth-child(3) {
            white-space: normal !important;
            /* Mengizinkan teks turun ke bawah */
            word-wrap: break-word;
            /* Memotong kata jika terlalu panjang */
            min-width: 200px;
            /* Lebar minimal agar tidak terlalu gepeng */
            max-width: 400px;
            /* Lebar maksimal (opsional) */
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
                order: [
                    [0, 'asc']
                ],
                columnDefs: [{
                    orderable: false,
                    targets: [4]
                }, ],
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

        $('#filter_penyakit, #filter_gejala').on('change', function() {
            var penyakitId = $('#filter_penyakit').val();
            var gejalaId = $('#filter_gejala').val();
            var url = "{{ route('penyakit-gejala.index') }}";
            window.location.href = url + '?penyakit_id=' + penyakitId + '&gejala_id=' + gejalaId;
        });

        function handleDelete(id, name) {
            $('#deleteItemName').text(name);
            $('#deleteForm').attr('action', `{{ url('penyakit-gejala') }}/${id}`);
            $('#deleteModal').modal('show');
        }

        // Event listener untuk kedua dropdown
        $('#filter_penyakit, #filter_gejala').on('change', function() {
            // Ambil value dari masing-masing dropdown
            var penyakitId = $('#filter_penyakit').val();
            var gejalaId = $('#filter_gejala').val();

            // Ambil URL dasar halaman saat ini
            var url = "{{ route('penyakit-gejala.index') }}";

            // Redirect halaman dengan parameter query string
            window.location.href = url + '?penyakit_id=' + penyakitId + '&gejala_id=' + gejalaId;
        });
    </script>
@endpush
