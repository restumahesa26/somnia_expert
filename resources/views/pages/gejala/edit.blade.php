@extends('layouts.template')

@section('title', 'Edit Gejala')

@section('content')
    <div class="py-3 mt-3 d-flex align-items-sm-center flex-sm-row flex-column">
        <div class="flex-grow-1">
            <h4 class="fs-18 fw-semibold m-0">Edit Gejala</h4>
            <p class="text-muted mb-0">Perbarui data gejala <strong>{{ $item->kode_gejala }}</strong></p>
        </div>
        <div class="text-end">
            <a href="{{ route('gejala.index') }}" class="btn btn-light btn-sm d-inline-flex align-items-center gap-1">
                <i class="mdi mdi-arrow-left"></i> Kembali
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('gejala.update', $item->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="kode_gejala" class="form-label">Kode Gejala <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('kode_gejala') is-invalid @enderror"
                                    id="kode_gejala" name="kode_gejala" value="{{ old('kode_gejala', $item->kode_gejala) }}"
                                    placeholder="Masukkan kode gejala.." required>
                                @error('kode_gejala')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-8">
                                <label for="nama_gejala" class="form-label">Nama Gejala <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('nama_gejala') is-invalid @enderror"
                                    id="nama_gejala" name="nama_gejala" value="{{ old('nama_gejala', $item->nama_gejala) }}"
                                    placeholder="Masukkan nama gejala.." required>
                                @error('nama_gejala')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="d-flex gap-2 pt-2">
                            <button type="submit" class="btn btn-primary d-inline-flex align-items-center gap-1">
                                <i class="mdi mdi-check"></i> Simpan Perubahan
                            </button>
                            <a href="{{ route('gejala.index') }}" class="btn btn-light">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
