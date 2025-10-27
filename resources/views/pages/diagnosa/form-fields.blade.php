@if(auth()->user()->is_admin)
    <div class="card mb-4">
        <div class="card-body">
            <h5 class="card-title mb-3">Data Pasien</h5>

            <div class="form-check mb-3">
                <input type="checkbox" class="form-check-input" id="is_admin_input" name="is_admin_input" value="1">
                <label class="form-check-label" for="is_admin_input">Input untuk pasien lain</label>
            </div>

            <div id="patientFields" style="display: none;">
                <div class="mb-3">
                    <label for="nama_pasien" class="form-label">Nama Pasien</label>
                    <input type="text" class="form-control" id="nama_pasien" name="nama_pasien" placeholder="Masukkan nama pasien">
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="umur" class="form-label">Umur</label>
                        <input type="number" class="form-control" id="umur" name="umur" placeholder="Masukkan umur">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="jenis_kelamin" class="form-label">Jenis Kelamin</label>
                        <select class="form-select" id="jenis_kelamin" name="jenis_kelamin" required>
                            <option value="L">Laki-laki</option>
                            <option value="P">Perempuan</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.getElementById('is_admin_input').addEventListener('change', function() {
            const patientFields = document.getElementById('patientFields');
            patientFields.style.display = this.checked ? 'block' : 'none';

            // Reset fields when unchecked
            if (!this.checked) {
                document.getElementById('nama_pasien').value = '';
                document.getElementById('umur').value = '';
                document.getElementById('jenis_kelamin').value = '';
            }
        });
    </script>
    @endpush
@endif
