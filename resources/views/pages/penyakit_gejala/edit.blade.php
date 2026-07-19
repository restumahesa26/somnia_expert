@extends('layouts.template')

@section('title', 'Edit Bobot Gejala')

@push('styles')
    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css"
        rel="stylesheet" />
    <style>
        .select2-container--bootstrap-5 .select2-selection {
            min-height: 38px;
        }
    </style>
@endpush

@section('content')
    <div class="py-3 mt-3 d-flex align-items-sm-center flex-sm-row flex-column">
        <div class="flex-grow-1">
            <h4 class="fs-18 fw-semibold m-0">Edit Bobot Gejala</h4>
            <p class="text-muted mb-0">Perbarui relasi dan nilai bobot antara penyakit dan gejala</p>
        </div>
        <div class="text-end">
            <a href="{{ route('penyakit-gejala.index') }}" class="btn btn-light btn-sm d-inline-flex align-items-center gap-1">
                <i class="mdi mdi-arrow-left"></i> Kembali
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('penyakit-gejala.update', $item->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Penyakit <span class="text-danger">*</span></label>
                                <select name="penyakit_id" class="form-select @error('penyakit_id') is-invalid @enderror"
                                    required id="penyakit_select">
                                    <option value="">Pilih Penyakit</option>
                                    @foreach ($penyakits as $penyakit)
                                        <option value="{{ $penyakit->id }}"
                                            {{ old('penyakit_id', $item->penyakit_id) == $penyakit->id ? 'selected' : '' }}>
                                            {{ $penyakit->nama_penyakit }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('penyakit_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Gejala <span class="text-danger">*</span></label>
                                <div class="position-relative">
                                    <select name="gejala_id" class="form-select @error('gejala_id') is-invalid @enderror"
                                        required id="gejala_select">
                                        <option value="">Pilih Gejala</option>
                                        @foreach ($gejalas as $gejala)
                                            <option value="{{ $gejala->id }}"
                                                {{ old('gejala_id', $item->gejala_id) == $gejala->id ? 'selected' : '' }}>
                                                {{ $gejala->nama_gejala }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div id="loading-gejala"
                                        class="position-absolute top-50 end-0 translate-middle-y me-2 d-none">
                                        <div class="spinner-border spinner-border-sm text-primary" role="status">
                                            <span class="visually-hidden">Loading...</span>
                                        </div>
                                    </div>
                                </div>
                                @error('gejala_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Bobot <span class="text-danger">*</span></label>
                            <div class="input-group" style="max-width: 300px;">
                                <input type="number" step="0.01" min="0.01" max="1"
                                    class="form-control @error('bobot') is-invalid @enderror" name="bobot"
                                    value="{{ old('bobot', $item->bobot) }}" id="bobotInput" oninput="validateBobot(this)"
                                    required>
                                <span class="input-group-text bg-light text-muted">0.01 - 1.00</span>
                            </div>
                            <div class="form-text">Masukkan nilai bobot kepastian (CF) antara 0.01 hingga 1.00</div>
                            @error('bobot')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex gap-2 pt-2">
                            <button type="submit" class="btn btn-primary d-inline-flex align-items-center gap-1">
                                <i class="mdi mdi-check"></i> Simpan Perubahan
                            </button>
                            <a href="{{ route('penyakit-gejala.index') }}" class="btn btn-light">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize Select2
            $('#penyakit_select').select2({
                theme: 'bootstrap-5',
                placeholder: 'Pilih Penyakit',
                width: '100%'
            });

            $('#gejala_select').select2({
                theme: 'bootstrap-5',
                placeholder: 'Pilih Gejala',
                width: '100%'
            });
        });

        function validateBobot(input) {
            // Hapus karakter non-numerik kecuali titik
            input.value = input.value.replace(/[^0-9.]/g, '');

            let value = parseFloat(input.value);

            // Jika nilai kosong, biarkan
            if (input.value === '') return;

            // Batasi hanya 2 angka di belakang koma
            if (input.value.includes('.') && input.value.split('.')[1].length > 2) {
                input.value = value.toFixed(2);
            }

            // Validasi range
            if (value <= 0) {
                input.value = '0.01';
            } else if (value > 1) {
                input.value = '1.00';
            }
        }

        // Validasi saat form disubmit
        document.querySelector('form').addEventListener('submit', function(e) {
            const bobotInput = document.getElementById('bobotInput');
            const value = parseFloat(bobotInput.value);

            if (value <= 0 || value > 1) {
                e.preventDefault();
                alert('Nilai bobot harus antara 0.01 dan 1.00');
                bobotInput.focus();
            }
        });
    </script>
@endpush
