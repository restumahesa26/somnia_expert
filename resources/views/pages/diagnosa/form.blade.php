@extends('layouts.template')
@section('title', 'Diagnosa Baru')
@section('content')
<div class="diagnosa-container mt-3">
    <!-- Progress Bar -->
    <div class="progress-container mb-4">
        <div class="progress" style="height: 10px;">
            <div class="progress-bar" role="progressbar" style="width: 0%" id="progressBar"></div>
        </div>
        <div class="text-center mt-2">
            <span class="badge bg-primary" id="progressText">0 dari {{ count($gejalas) }} gejala</span>
        </div>
    </div>

    <!-- Header Section -->
    <div class="text-center mb-4">
        <h1 class="h3 mb-2">Konsultasi Gangguan Tidur</h1>
        <p class="text-muted">Pilih gejala yang Anda alami saat ini dengan teliti</p>
    </div>

    <!-- Main Form -->
    <form method="post" action="{{ route('diagnosa.proses') }}" id="diagnosaForm">
        @csrf

        @if($isAdmin)
        <div class="card mb-4 shadow-sm">
            <div class="card-body">
                <h4 class="mb-3">Data Pasien</h4>
                <div class="form-check mb-3">
                    <input type="checkbox" class="form-check-input" id="is_admin_input" name="is_admin_input" @if(old('is_admin_input') == true) checked @endif>
                    <label class="form-check-label" for="is_admin_input">Input data pasien</label>
                </div>
                <div id="patientData" style="@if(old('is_admin_input') == true) display: block @else display: none @endif">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="nama_pasien" class="form-label">Nama Pasien</label>
                            <input type="text" class="form-control @error('nama_pasien') is-invalid @enderror"
                                id="nama_pasien" name="nama_pasien" value="{{ old('nama_pasien') }}" placeholder="Masukkan nama pasien..">
                            @error('nama_pasien')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="umur" class="form-label">Umur</label>
                            <input type="number" class="form-control @error('umur') is-invalid @enderror"
                                id="umur" name="umur" value="{{ old('umur') }}" min="1" max="150" placeholder="Masukkan umur..">
                            @error('umur')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="jenis_kelamin" class="form-label">Jenis Kelamin</label>
                            <select class="form-select @error('jenis_kelamin') is-invalid @enderror"
                                id="jenis_kelamin" name="jenis_kelamin" required>
                                <option value="L" {{ old('jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="P" {{ old('jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                            @error('jenis_kelamin')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <div class="row justify-content-center">
            <div class="col-12">
                <!-- Gejala List -->
                @foreach($gejalas as $g)
                <div class="gejala-item mb-3">
                    <div class="card shadow-sm card-hover" style="border-radius: 10px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); padding: 1rem; background-color: #fff;">
                        <div class="card-body">
                            <div class="gejala-options">
                                <div class="d-flex align-items-center mb-2">
                                    <div class="gejala-number me-3">
                                        <span class="badge bg-primary">{{ $loop->iteration }}</span>
                                    </div>
                                    <div>
                                        <div class="mb-1">
                                            <span class="badge bg-light text-primary">{{ $g->kode_gejala }}</span>
                                        </div>
                                        <span class="gejala-text">{{ $g->nama_gejala }}</span>
                                    </div>
                                </div>
                                <div class="gejala-buttons mt-2">
                                    <div class="btn-group w-100" role="group" aria-label="Pilihan gejala">
                                        <input type="checkbox"
                                            class="btn-check"
                                            name="gejala[]"
                                            value="{{ $g->id }}"
                                            id="g{{ $g->id }}_ya"
                                            onchange="handleYesAnswer({{ $g->id }})"
                                            style="display: none;">
                                        <label class="btn btn-outline-success w-50" for="g{{ $g->id }}_ya">Ya</label>

                                        <input type="radio"
                                            class="btn-check"
                                            name="answer_{{ $g->id }}"
                                            value="0"
                                            id="g{{ $g->id }}_tidak"
                                            onchange="handleNoAnswer({{ $g->id }})">
                                        <label class="btn btn-outline-danger w-50" for="g{{ $g->id }}_tidak">Tidak</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach

                <!-- Submit Button -->
                <div class="text-center mt-4 mb-4">
                    <button type="submit" class="btn btn-primary btn-lg px-4" id="submitBtn" disabled>
                        <i class="mdi mdi-stethoscope me-1"></i>
                        Mulai Diagnosis
                    </button>
                </div>@push('styles')
<style>
    .diagnosa-container {
        max-width: 800px;
        margin: 0 auto;
    }

    .progress-container {
        background: white;
        padding: 1rem;
        border-radius: 10px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .progress {
        border-radius: 10px;
        background-color: #e9ecef;
    }

    .progress-bar {
        transition: width 0.3s ease;
        border-radius: 10px;
    }

    .card-hover {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        border: 1px solid rgba(0,0,0,0.08);
    }

    .card-hover:hover {
        transform: translateY(-2px);
        box-shadow: 0 0.125rem 0.25rem rgba(0,0,0,0.075);
    }

    .gejala-item .card {
        border-radius: 8px;
    }

    .gejala-item .form-check {
        margin-bottom: 0;
    }

    .gejala-text {
        color: #495057;
        font-size: 1rem;
        display: block;
        line-height: 1.5;
    }

    .gejala-number .badge {
        width: 28px;
        height: 28px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.9rem;
    }

    .gejala-options {
        position: relative;
    }

    .gejala-answered .gejala-text {
        font-weight: 500;
    }

    .gejala-answered[data-answered="1"] .gejala-text {
        color: #198754;
    }

    .gejala-answered[data-answered="0"] .gejala-text {
        color: #dc3545;
    }

    .btn-group {
        gap: 8px;
    }

    .btn-check + .btn {
        flex: 1;
        padding: 8px 16px;
        border-radius: 6px !important;
    }

    .btn-outline-success:hover {
        background-color: #19875420;
        color: #198754;
    }

    .btn-outline-danger:hover {
        background-color: #dc354520;
        color: #dc3545;
    }

    /* Animation for cards */
    .gejala-item {
        opacity: 0;
        transform: translateY(20px);
        animation: fadeInUp 0.5s forwards;
    }

    @keyframes fadeInUp {
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Staggered animation delays */
    .gejala-item:nth-child(1) { animation-delay: 0.1s; }
    .gejala-item:nth-child(2) { animation-delay: 0.2s; }
    .gejala-item:nth-child(3) { animation-delay: 0.3s; }
    .gejala-item:nth-child(4) { animation-delay: 0.4s; }
    .gejala-item:nth-child(5) { animation-delay: 0.5s; }
    .gejala-item:nth-child(6) { animation-delay: 0.6s; }
    .gejala-item:nth-child(7) { animation-delay: 0.7s; }
    .gejala-item:nth-child(8) { animation-delay: 0.8s; }
    .gejala-item:nth-child(9) { animation-delay: 0.9s; }
    .gejala-item:nth-child(10) { animation-delay: 1s; }
    .gejala-item:nth-child(n+11) { animation-delay: 1.1s; }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const isAdminInput = document.getElementById('is_admin_input');
        const patientData = document.getElementById('patientData');

        if (isAdminInput) {
            isAdminInput.addEventListener('change', function() {
                patientData.style.display = this.checked ? 'block' : 'none';
            });
        }

        updateProgress();
        setupForm();
    });

    function setupForm() {
        const form = document.getElementById('diagnosaForm');

        // Handle form submission
        form.addEventListener('submit', function(e) {
            e.preventDefault();

            // Validasi semua gejala harus dijawab
            const totalGejala = document.querySelectorAll('.gejala-item').length;
            const answeredGejala = document.querySelectorAll('.gejala-answered').length;

            if (answeredGejala < totalGejala) {
                alert('Mohon jawab semua gejala sebelum melanjutkan diagnosis');

                // Scroll ke gejala pertama yang belum dijawab
                const firstUnanswered = Array.from(document.querySelectorAll('.gejala-item'))
                    .find(item => !item.querySelector('.gejala-answered'));

                if (firstUnanswered) {
                    const offset = 150;
                    const itemTop = firstUnanswered.offsetTop - offset;
                    window.scrollTo({
                        top: itemTop,
                        behavior: 'smooth'
                    });
                }
                return;
            }

            // Submit form jika semua gejala sudah dijawab
            form.submit();
        });
    }

    function handleNoAnswer(gejalaId) {
        // Uncheck the "Ya" checkbox when "Tidak" is selected
        const yaCheckbox = document.getElementById(`g${gejalaId}_ya`);
        if (yaCheckbox) {
            yaCheckbox.checked = false;
        }
        updateProgress(gejalaId, false);
    }

    function handleYesAnswer(gejalaId) {
        // Uncheck the "Tidak" radio when "Ya" is selected
        const tidakRadio = document.getElementById(`g${gejalaId}_tidak`);
        if (tidakRadio) {
            tidakRadio.checked = false;
        }
        updateProgress(gejalaId, true);
    }

    function updateProgress(gejalaId, isYes) {
        if (gejalaId) {  // Only update specific item if gejalaId is provided
            // Update gejala item appearance
            const gejalaItem = document.querySelector(`#g${gejalaId}_ya`).closest('.gejala-item');
            const gejalaOptions = gejalaItem.querySelector('.gejala-options');

            gejalaOptions.classList.add('gejala-answered');
            gejalaOptions.setAttribute('data-answered', isYes ? '1' : '0');
        }

        // Count answered questions
        const totalGejala = document.querySelectorAll('.gejala-item').length;
        const answeredGejala = document.querySelectorAll('.gejala-answered').length;

        // Update progress bar and submit button
        const percentage = (answeredGejala / totalGejala) * 100;
        const progressBar = document.getElementById('progressBar');
        const progressText = document.getElementById('progressText');
        const submitBtn = document.getElementById('submitBtn');

        progressBar.style.width = percentage + '%';
        progressText.textContent = answeredGejala + ' dari ' + totalGejala + ' gejala';

        // Enable submit button only if all questions are answered
        submitBtn.disabled = answeredGejala < totalGejala;

        if (gejalaId) {  // Only scroll if gejalaId is provided
            // Scroll to next unanswered question if available
            const gejalaItem = document.querySelector(`#g${gejalaId}_ya`).closest('.gejala-item');
            const gejalaItems = Array.from(document.querySelectorAll('.gejala-item'));
            const currentIndex = gejalaItems.indexOf(gejalaItem);

            const nextUnanswered = gejalaItems.slice(currentIndex + 1).find(item =>
                !item.querySelector('.gejala-answered')
            );

            if (nextUnanswered) {
                const offset = 150;
                const itemTop = nextUnanswered.offsetTop - offset;
                window.scrollTo({
                    top: itemTop,
                    behavior: 'smooth'
                });
            }
        }
    }
</script>
@endpush
@endsection
