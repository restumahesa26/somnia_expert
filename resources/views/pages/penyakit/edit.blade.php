@extends('layouts.template')

@section('title', 'Data Penyakit')

@section('content')
<div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
    <div class="flex-grow-1">
        <h4 class="fs-18 fw-semibold m-0">Data Penyakit</h4>
    </div>

    <div class="text-end">
        <ol class="breadcrumb m-0 py-0">
            <li class="breadcrumb-item"><a href="{{ route('penyakit.index') }}">Penyakit</a></li>
            <li class="breadcrumb-item active">Edit</li>
        </ol>
    </div>
</div>

<!-- Form  -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Edit Penyakit</h5>
            </div>

            <div class="card-body">
                <form action="{{ route('penyakit.update', $item->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label for="kode_penyakit" class="form-label">Kode Penyakit</label>
                        <input type="text" class="form-control @error('kode_penyakit') is-invalid @enderror"
                            id="kode_penyakit" name="kode_penyakit"
                            value="{{ old('kode_penyakit', $item->kode_penyakit) }}"
                            placeholder="Masukkan kode penyakit.." required>
                        @error('kode_penyakit')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="nama_penyakit" class="form-label">Nama Penyakit</label>
                        <input type="text" class="form-control @error('nama_penyakit') is-invalid @enderror"
                            id="nama_penyakit" name="nama_penyakit"
                            value="{{ old('nama_penyakit', $item->nama_penyakit) }}"
                            placeholder="Masukkan nama penyakit.." required>
                        @error('nama_penyakit')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="deskripsi" class="form-label">Deskripsi</label>
                        <textarea class="form-control @error('deskripsi') is-invalid @enderror" id="deskripsi"
                            name="deskripsi" rows="4" placeholder="Masukkan deskripsi penyakit.."
                            required>{{ old('deskripsi', $item->deskripsi) }}</textarea>
                        @error('deskripsi')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="solusi" class="form-label">Solusi</label>
                        <textarea class="form-control @error('solusi') is-invalid @enderror" id="solusi"
                            name="solusi" rows="4" placeholder="Masukkan solusi penyakit.."
                            required>{{ old('solusi', $item->solusi) }}</textarea>
                        @error('solusi')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <a href="{{ route('penyakit.index') }}" class="btn btn-secondary">Batal</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
