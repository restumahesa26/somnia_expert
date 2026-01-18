@extends('layouts.template')

@section('title', 'Tambah Bobot Gejala')

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
    <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
        <div class="flex-grow-1">
            <h4 class="fs-18 fw-semibold m-0">Tambah Bobot Gejala</h4>
        </div>

        <div class="text-end">
            <ol class="breadcrumb m-0 py-0">
                <li class="breadcrumb-item"><a href="{{ route('penyakit-gejala.index') }}">Bobot Gejala</a></li>
                <li class="breadcrumb-item active">Tambah</li>
            </ol>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Tambah Bobot Gejala</h5>
                </div>

                <div class="card-body">
                    <form action="{{ route('penyakit-gejala.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Penyakit</label>
                            <select name="penyakit_id" class="form-select @error('penyakit_id') is-invalid @enderror"
                                required>
                                <option value="">Pilih Penyakit</option>
                                @foreach ($penyakits as $penyakit)
                                    <option value="{{ $penyakit->id }}"
                                        {{ old('penyakit_id') == $penyakit->id ? 'selected' : '' }}>
                                        {{ $penyakit->nama_penyakit }}
                                    </option>
                                @endforeach
                            </select>
                            @error('penyakit_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Gejala</label>
                            <div class="position-relative">
                                <select name="gejala_id" class="form-select @error('gejala_id') is-invalid @enderror"
                                    required id="gejala_select" disabled>
                                    <option value="">Pilih Penyakit Terlebih Dahulu</option>
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

                        @push('scripts')
                            <!-- Select2 JS -->
                            <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
                            <script>
                                document.addEventListener('DOMContentLoaded', function() {
                                    const penyakitSelect = $('select[name="penyakit_id"]');
                                    const gejalaSelect = $('#gejala_select');

                                    // Initialize Select2
                                    penyakitSelect.select2({
                                        theme: 'bootstrap-5',
                                        placeholder: 'Pilih Penyakit',
                                        allowClear: true,
                                        width: '100%'
                                    });

                                    gejalaSelect.select2({
                                        theme: 'bootstrap-5',
                                        placeholder: 'Pilih Penyakit Terlebih Dahulu',
                                        allowClear: true,
                                        width: '100%'
                                    });

                                    penyakitSelect.on('change', function() {
                                        const penyakitId = this.value;
                                        const loadingIndicator = $('#loading-gejala');

                                        // Reset and disable gejala select
                                        gejalaSelect.prop('disabled', true);

                                        if (penyakitId) {
                                            // Show loading
                                            loadingIndicator.removeClass('d-none');
                                            gejalaSelect.empty().append('<option value="">Mengambil data gejala...</option>');
                                            gejalaSelect.trigger('change');

                                            // Fetch available gejala
                                            fetch(`/get-available-gejala/${penyakitId}`)
                                                .then(response => response.json())
                                                .then(data => {
                                                    // Clear and update options
                                                    gejalaSelect.empty().append('<option value="">Pilih Gejala</option>');
                                                    if (data.length > 0) {
                                                        data.forEach(gejala => {
                                                            gejalaSelect.append(new Option(gejala.nama_gejala, gejala
                                                                .id, false, false));
                                                        });
                                                        gejalaSelect.prop('disabled', false);
                                                    } else {
                                                        gejalaSelect.append(new Option('Tidak ada gejala yang tersedia', '',
                                                            false, false));
                                                    }
                                                    gejalaSelect.trigger('change'); // Notify Select2 of changes
                                                })
                                                .catch(error => {
                                                    console.error('Error:', error);
                                                    gejalaSelect.empty().append(
                                                        '<option value="">Error loading gejala</option>');
                                                    gejalaSelect.trigger('change');
                                                })
                                                .finally(() => {
                                                    // Hide loading
                                                    loadingIndicator.addClass('d-none');
                                                });
                                        } else {
                                            loadingIndicator.addClass('d-none');
                                            gejalaSelect.empty().append('<option value="">Pilih Penyakit Terlebih Dahulu</option>');
                                            gejalaSelect.trigger('change');
                                        }
                                    });
                                });
                            </script>
                        @endpush

                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" role="switch" id="is_kunci"
                                    name="is_kunci" value="1" {{ old('is_kunci') ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_kunci">Gejala Kunci?</label>
                            </div>
                            <div class="form-text">Centang jika gejala ini merupakan gejala kunci untuk penyakit tersebut.
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Bobot</label>
                            <div class="input-group">
                                <input type="number" step="0.01" min="0.01" max="1"
                                    class="form-control @error('bobot') is-invalid @enderror" name="bobot"
                                    value="{{ old('bobot') }}" id="bobotInput" oninput="validateBobot(this)" required>
                                <span class="input-group-text">0.01 - 1.00</span>
                            </div>
                            <div class="form-text">Masukkan nilai bobot antara 0.01 hingga 1.00</div>
                            @error('bobot')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        @push('scripts')
                            <script>
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

                        <button type="submit" class="btn btn-primary">Simpan</button>
                        <a href="{{ route('penyakit-gejala.index') }}" class="btn btn-secondary">Batal</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
