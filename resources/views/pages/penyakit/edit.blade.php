@extends('layouts.template')

@section('title', 'Edit Penyakit')

@section('content')
    <div class="py-3 mt-3 d-flex align-items-sm-center flex-sm-row flex-column">
        <div class="flex-grow-1">
            <h4 class="fs-18 fw-semibold m-0">Edit Penyakit</h4>
            <p class="text-muted mb-0">Perbarui data penyakit <strong>{{ $item->kode_penyakit }}</strong></p>
        </div>
        <div class="text-end">
            <a href="{{ route('penyakit.index') }}" class="btn btn-light btn-sm d-inline-flex align-items-center gap-1">
                <i class="mdi mdi-arrow-left"></i> Kembali
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('penyakit.update', $item->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="kode_penyakit" class="form-label">Kode Penyakit <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('kode_penyakit') is-invalid @enderror"
                                    id="kode_penyakit" name="kode_penyakit"
                                    value="{{ old('kode_penyakit', $item->kode_penyakit) }}"
                                    placeholder="Masukkan kode penyakit.." required>
                                @error('kode_penyakit')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-8">
                                <label for="nama_penyakit" class="form-label">Nama Penyakit <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('nama_penyakit') is-invalid @enderror"
                                    id="nama_penyakit" name="nama_penyakit"
                                    value="{{ old('nama_penyakit', $item->nama_penyakit) }}"
                                    placeholder="Masukkan nama penyakit.." required>
                                @error('nama_penyakit')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="deskripsi" class="form-label">Deskripsi <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('deskripsi') is-invalid @enderror" id="deskripsi"
                                name="deskripsi" rows="4" placeholder="Masukkan deskripsi penyakit.."
                                required>{{ old('deskripsi', $item->deskripsi) }}</textarea>
                            @error('deskripsi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="solusi" class="form-label">Solusi <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('solusi') is-invalid @enderror" id="solusi"
                                name="solusi" rows="4" placeholder="Masukkan solusi penyakit.."
                                required>{{ old('solusi', $item->solusi) }}</textarea>
                            @error('solusi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex gap-2 pt-2">
                            <button type="submit" class="btn btn-primary d-inline-flex align-items-center gap-1">
                                <i class="mdi mdi-check"></i> Simpan Perubahan
                            </button>
                            <a href="{{ route('penyakit.index') }}" class="btn btn-light">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
