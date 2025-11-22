@extends('layouts.template')

@section('title', 'Riwayat Diagnosa')

@section('content')
<div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
    <div class="flex-grow-1">
        <h4 class="fs-18 fw-semibold m-0">Riwayat Diagnosa</h4>
    </div>
</div>

@include('components.flash')

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table id="datatable" class="table table-bordered dt-responsive table-responsive nowrap">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        @if (Auth::user()->is_admin)
                            <th>Nama</th>
                        @endif
                        <th>Gejala Dipilih</th>
                        <th>Hasil Teratas</th>
                        <th>Persentase</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($riwayat as $item)
                        @php
                            $hasilTeratas = collect($item->hasil)->sortByDesc('percent')->first();
                        @endphp
                        <tr>
                            <td>{{ \App\Helpers\Helper::formatDate($item->created_at, true) }}</td>
                            @if (Auth::user()->is_admin)
                                <td>{{ $item->nama_pasien == '' ? $item->user->nama : $item->nama_pasien }}</td>
                            @endif
                            <td>{{ count($item->gejala_terpilih) }} Gejala</td>
                            <td>{{ $hasilTeratas['nama'] }}</td>
                            <td>
                                <span class="badge @if($hasilTeratas['percent'] >= 50) bg-danger @else bg-primary @endif">
                                    {{ number_format($hasilTeratas['percent'], 2) }}%
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('diagnosa.show', $item->id) }}" class="btn btn-info btn-sm">
                                    <i class="mdi mdi-eye"></i> Detail
                                </a>
                                @if (Auth::user()->is_admin)
                                <button type="button" class="btn btn-danger btn-sm" onclick="handleDelete({{ $item->id }})">
                                    <i class="mdi mdi-delete"></i> Hapus
                                </button>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="deleteModalLabel">Konfirmasi Hapus</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            Apakah Anda yakin ingin menghapus riwayat diagnosa ini?
                        </div>
                        <div class="modal-footer">
                            <form id="deleteForm" method="POST">
                                @csrf
                                @method('DELETE')
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
@endsection

@push('styles')
<link href="{{ url('dist/assets/libs/datatables.net-bs5/css/dataTables.bootstrap5.min.css') }}" rel="stylesheet">
<link href="{{ url('dist/assets/libs/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css') }}" rel="stylesheet">
@endpush

@push('scripts')
<script src="{{ url('dist/assets/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ url('dist/assets/libs/datatables.net-bs5/js/dataTables.bootstrap5.min.js') }}"></script>
<script src="{{ url('dist/assets/libs/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>

<script>
    $(document).ready(function() {
        $('#datatable').DataTable({
            ordering: false  // Menambahkan baris ini
        });
    });
    function handleDelete(id) {
        const deleteForm = document.getElementById('deleteForm');

        // Mengubah action form ke url: domain.com/diagnosa/{id}
        deleteForm.action = `{{ url('diagnosa/hapus') }}/${id}`;

        // Tampilkan Modal
        $('#deleteModal').modal('show');
    }
</script>
@endpush
