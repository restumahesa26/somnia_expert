@extends('layouts.template')

@section('title', 'Data Gejala')

@section('content')
<div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
    <div class="flex-grow-1">
        <h4 class="fs-18 fw-semibold m-0">Data Gejala</h4>
    </div>

    <div class="text-end">
        <ol class="breadcrumb m-0 py-0">
            <li class="breadcrumb-item"><a href="{{ route('gejala.index') }}">Gejala</a></li>
            <li class="breadcrumb-item active">Edit</li>
        </ol>
    </div>
</div>

<!-- Datatables  -->
<div class="row">
    <div class="col-12">
        <div class="card">

            <div class="card-header">
                <h5 class="card-title mb-0">Edit Gejala</h5>
            </div><!-- end card header -->

            <div class="card-body">
                <form action="{{ route('gejala.update', $item->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label for="kode_gejala" class="form-label">Kode Gejala</label>
                        <input type="text" class="form-control @error('kode_gejala') is-invalid @enderror"
                            id="kode_gejala" name="kode_gejala"
                            value="{{ old('kode_gejala', $item->kode_gejala) }}"
                            placeholder="Masukkan kode gejala.." required>
                        @error('kode_gejala')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="nama_gejala" class="form-label">Nama Gejala</label>
                        <input type="text" class="form-control @error('nama_gejala') is-invalid @enderror"
                            id="nama_gejala" name="nama_gejala"
                            value="{{ old('nama_gejala', $item->nama_gejala) }}"
                            placeholder="Masukkan nama gejala.." required>
                        @error('nama_gejala')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <a href="{{ route('gejala.index') }}" class="btn btn-secondary">Batal</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
