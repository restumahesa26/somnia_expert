@extends('layouts.template')

@section('title', 'Data Gangguan')

@section('content')
<div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
    <div class="flex-grow-1">
        <h4 class="fs-18 fw-semibold m-0">Data Gangguan</h4>
    </div>

    <div class="text-end">
        <ol class="breadcrumb m-0 py-0">
            <li class="breadcrumb-item"><a href="{{ route('gangguan.index') }}">Gangguan</a></li>
            <li class="breadcrumb-item active">Edit</li>
        </ol>
    </div>
</div>

<!-- Form  -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Edit Gangguan</h5>
            </div>

            <div class="card-body">
                <form action="{{ route('gangguan.update', $item->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label for="kode_gangguan" class="form-label">Kode Gangguan</label>
                        <input type="text" class="form-control @error('kode_gangguan') is-invalid @enderror"
                            id="kode_gangguan" name="kode_gangguan"
                            value="{{ old('kode_gangguan', $item->kode_gangguan) }}"
                            placeholder="Masukkan kode gangguan.." required>
                        @error('kode_gangguan')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="nama_gangguan" class="form-label">Nama Gangguan</label>
                        <input type="text" class="form-control @error('nama_gangguan') is-invalid @enderror"
                            id="nama_gangguan" name="nama_gangguan"
                            value="{{ old('nama_gangguan', $item->nama_gangguan) }}"
                            placeholder="Masukkan nama gangguan.." required>
                        @error('nama_gangguan')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="deskripsi" class="form-label">Deskripsi</label>
                        <textarea class="form-control @error('deskripsi') is-invalid @enderror" id="deskripsi"
                            name="deskripsi" rows="4" placeholder="Masukkan deskripsi gangguan.."
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
                            name="solusi" rows="4" placeholder="Masukkan solusi gangguan.."
                            required>{{ old('solusi', $item->solusi) }}</textarea>
                        @error('solusi')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <a href="{{ route('gangguan.index') }}" class="btn btn-secondary">Batal</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
