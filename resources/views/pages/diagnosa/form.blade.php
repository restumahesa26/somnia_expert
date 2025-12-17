@extends('layouts.template')
@section('title', 'Diagnosa Interaktif')

@section('content')
<div class="diagnosa-container mt-4">

    <div class="text-center mb-5">
        <h2 class="fw-bold text-primary"><i class="mdi mdi-doctor me-2"></i>Konsultasi Gangguan Tidur</h2>
        <p class="text-muted">Jawablah pertanyaan berikut sesuai dengan kondisi yang Anda alami.</p>
    </div>

    <div id="loadingSpinner" class="text-center py-5" style="display: none;">
        <div class="spinner-border text-primary" role="status" style="width: 3rem; height: 3rem;">
            <span class="visually-hidden">Loading...</span>
        </div>
        <p class="mt-2 text-muted">Menganalisa jawaban Anda...</p>
    </div>

    <form method="post" action="{{ route('diagnosa.proses') }}" id="diagnosaForm">
        @csrf

        @if($isAdmin)
        <div class="card mb-4 shadow-sm border-0 bg-light">
            <div class="card-body">
                <h5 class="card-title mb-3"><i class="mdi mdi-account-details me-1"></i> Data Pasien</h5>
                <div class="form-check mb-3">
                    <input type="checkbox" class="form-check-input" id="is_admin_input" name="is_admin_input" value="1">
                    <label class="form-check-label" for="is_admin_input">Input data manual</label>
                </div>
                <div id="patientData" style="display: none;">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Nama Pasien</label>
                            <input type="text" class="form-control" name="nama_pasien" placeholder="Nama lengkap">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Umur</label>
                            <input type="number" class="form-control" name="umur" placeholder="Tahun">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Jenis Kelamin</label>
                            <select class="form-select" name="jenis_kelamin">
                                <option value="L">Laki-laki</option>
                                <option value="P">Perempuan</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <div id="question-container">
        </div>

        <div id="final-answers-container"></div>

        <div class="text-center mt-4 mb-5" id="action-buttons">
            <button type="button" class="btn btn-primary btn-lg px-5 rounded-pill shadow" id="btnLanjut" onclick="submitBatch()">
                Lanjut <i class="mdi mdi-arrow-right ms-1"></i>
            </button>
        </div>
    </form>
</div>
@endsection

@push('styles')
<style>
    .diagnosa-container { max-width: 700px; margin: 0 auto; }

    /* Animasi Kartu */
    .fade-in-up {
        animation: fadeInUp 0.5s ease-out forwards;
        opacity: 0;
        transform: translateY(20px);
    }

    @keyframes fadeInUp {
        to { opacity: 1; transform: translateY(0); }
    }

    /* Style Pilihan Ya/Tidak */
    .btn-group-custom .btn-check:checked + .btn-outline-success {
        background-color: #198754; color: white; box-shadow: 0 4px 6px rgba(25, 135, 84, 0.3);
    }
    .btn-group-custom .btn-check:checked + .btn-outline-danger {
        background-color: #dc3545; color: white; box-shadow: 0 4px 6px rgba(220, 53, 69, 0.3);
    }
    .card-question {
        border-radius: 15px;
        transition: all 0.3s;
        border: 1px solid #eee;
    }
    .card-question:hover {
        box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        transform: translateY(-2px);
    }
</style>
@endpush

@push('scripts')
<script>
    // Variabel Global
    let currentBatchIds = []; // Menyimpan ID gejala yang sedang tampil
    let allYesAnswers = [];   // Menyimpan semua ID gejala yang dijawab 'YA'
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    document.addEventListener('DOMContentLoaded', function() {
        // 1. Tampilkan pertanyaan awal saat halaman dimuat
        loadQuestions('{{ route("diagnosa.start") }}', {});

        // Logic toggle form admin
        const isAdminInput = document.getElementById('is_admin_input');
        if(isAdminInput){
            isAdminInput.addEventListener('change', function() {
                document.getElementById('patientData').style.display = this.checked ? 'block' : 'none';
            });
        }
    });

    // Fungsi Utama: Mengambil Pertanyaan dari Server
    function loadQuestions(url, dataPayload) {
        showLoading(true);

        fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify(dataPayload)
        })
        .then(response => response.json())
        .then(result => {
            showLoading(false);

            if (result.status === 'finish') {
                finishDiagnosa();
            } else {
                renderQuestions(result.gejala);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showLoading(false);
            alert('Terjadi kesalahan koneksi. Silakan coba lagi.');
        });
    }

    // Fungsi Render: Menampilkan Kartu Pertanyaan ke HTML
    function renderQuestions(gejalas) {
        const container = document.getElementById('question-container');
        container.innerHTML = ''; // Bersihkan pertanyaan lama
        currentBatchIds = [];     // Reset ID batch ini

        // Jika tidak ada gejala yang dikembalikan (error handling)
        if(gejalas.length === 0) {
            finishDiagnosa();
            return;
        }

        gejalas.forEach((g, index) => {
            currentBatchIds.push(g.id);

            // HTML Template untuk setiap kartu pertanyaan
            const html = `
                <div class="card card-question mb-3 fade-in-up" style="animation-delay: ${index * 0.1}s">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-3">
                            <span class="badge bg-primary me-2">${g.kode_gejala}</span>
                            <h5 class="card-title mb-0 fs-6 text-dark">${g.nama_gejala}</h5>
                        </div>
                        <div class="btn-group w-100 btn-group-custom" role="group">
                            <input type="radio" class="btn-check" name="temp_ans_${g.id}" id="yes_${g.id}" value="1">
                            <label class="btn btn-outline-success py-2" for="yes_${g.id}">
                                <i class="mdi mdi-check"></i> Ya
                            </label>

                            <input type="radio" class="btn-check" name="temp_ans_${g.id}" id="no_${g.id}" value="0">
                            <label class="btn btn-outline-danger py-2" for="no_${g.id}">
                                <i class="mdi mdi-close"></i> Tidak
                            </label>
                        </div>
                    </div>
                </div>
            `;
            container.insertAdjacentHTML('beforeend', html);
        });

        // Scroll smooth ke atas container pertanyaan
        container.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }

    // Fungsi Submit Batch (Tombol Lanjut)
    function submitBatch() {
        let currentAnswers = {};
        let isComplete = true;

        // Validasi: Cek apakah semua pertanyaan di layar sudah dijawab
        currentBatchIds.forEach(id => {
            const yes = document.getElementById(`yes_${id}`);
            const no = document.getElementById(`no_${id}`);

            if (!yes.checked && !no.checked) {
                isComplete = false;
                // Beri highlight merah sebentar (opsional)
                yes.closest('.card').style.borderColor = 'red';
            } else {
                yes.closest('.card').style.borderColor = '#eee';

                // Simpan jawaban (1 atau 0)
                const val = yes.checked ? 1 : 0;
                currentAnswers[id] = val;

                // Jika YA, simpan ke array global untuk dikirim di akhir
                if (val === 1) {
                    allYesAnswers.push(id);
                }
            }
        });

        if (!isComplete) {
            alert('Mohon jawab semua pertanyaan yang tampil sebelum melanjutkan.');
            return;
        }

        // Panggil Controller untuk analisa selanjutnya
        loadQuestions('{{ route("diagnosa.next") }}', { jawaban: currentAnswers });
    }

    // Fungsi Finish: Submit Form Akhir ke Controller 'proses'
    function finishDiagnosa() {
        const form = document.getElementById('diagnosaForm');
        const hiddenContainer = document.getElementById('final-answers-container');

        // Buat input hidden untuk setiap jawaban YA
        // Format: <input type="hidden" name="gejala[]" value="ID">
        allYesAnswers.forEach(id => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'gejala[]';
            input.value = id;
            hiddenContainer.appendChild(input);
        });

        // Tampilkan loading terakhir sebelum redirect
        showLoading(true);
        document.getElementById('question-container').innerHTML = '';
        document.getElementById('action-buttons').style.display = 'none';

        // Submit form sesungguhnya
        form.submit();
    }

    function showLoading(show) {
        const spinner = document.getElementById('loadingSpinner');
        const container = document.getElementById('question-container');
        const btn = document.getElementById('btnLanjut');

        if (show) {
            spinner.style.display = 'block';
            container.style.opacity = '0.5';
            btn.disabled = true;
        } else {
            spinner.style.display = 'none';
            container.style.opacity = '1';
            btn.disabled = false;
        }
    }
</script>
@endpush
