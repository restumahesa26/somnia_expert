@extends('layouts.template')

@section('title', 'Riwayat Diagnosa')

@section('content')
<div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
    <div class="flex-grow-1">
        <h4 class="fs-18 fw-semibold m-0">Riwayat Diagnosa</h4>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table id="datatable" class="table table-bordered dt-responsive table-responsive nowrap">
                <thead>
                    <tr>
                        <th>Tanggal</th>
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
</script>
@endpush
