@extends('layouts.template')

@section('title', 'Akun Saya')

@section('content')
<div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
    <div class="flex-grow-1">
        <h4 class="fs-18 fw-semibold m-0">Akun Saya</h4>
        <p class="text-muted mb-0">Detail data akun pengguna</p>
    </div>
</div>

@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if (session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif


<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-lg-6 col-xl-6">
                <div class="card border">
                    <div class="card-header">
                        <div class="row align-items-center">
                            <div class="col">
                                <h4 class="card-title mb-0">Personal Information</h4>
                            </div><!--end col-->
                        </div>
                    </div>

                    <div class="card-body">
                        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PATCH')
                            <div class="form-group mb-3">
                                <label for="nama" class="form-label">Nama</label>
                                <input type="text" class="form-control @error('nama') is-invalid @enderror"
                                    id="nama" name="nama" value="{{ old('nama', $user->nama) }}" placeholder="Masukkan nama.." required>
                                @error('nama')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="form-group mb-3">
                                <label for="username" class="form-label">Username</label>
                                <input type="text" class="form-control @error('username') is-invalid @enderror"
                                    id="username" name="username" value="{{ old('username', $user->username) }}" placeholder="Masukkan username.." required>
                                @error('username')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="form-group mb-3">
                                <label for="email" class="form-label">Email</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="mdi mdi-email"></i></span>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror"
                                    id="email" name="email" value="{{ old('email', $user->email) }}" placeholder="Masukkan email.." required>
                                </div>
                                @error('email')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="form-group mb-3">
                                <label for="umur" class="form-label">Umur</label>
                                <input type="number" class="form-control @error('umur') is-invalid @enderror"
                                    id="umur" name="umur" value="{{ old('umur', $user->umur) }}" placeholder="Masukkan umur.." min="0" required>
                                @error('umur')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="form-group mb-3">
                                <label for="jenis_kelamin" class="form-label">Jenis Kelamin</label>
                                <select class="form-select @error('jenis_kelamin') is-invalid @enderror" id="jenis_kelamin" name="jenis_kelamin" required>
                                    <option value="" disabled {{ old('jenis_kelamin', $user->jenis_kelamin) ? '' : 'selected' }}>Pilih Jenis Kelamin</option>
                                    <option value="L" {{ old('jenis_kelamin', $user->jenis_kelamin) == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                    <option value="P" {{ old('jenis_kelamin', $user->jenis_kelamin) == 'P' ? 'selected' : '' }}>Perempuan</option>
                                </select>
                                @error('jenis_kelamin')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="form-group mb-3">
                                <label for="foto" class="form-label">Foto Profil</label>
                                <input type="file" class="form-control @error('foto') is-invalid @enderror" id="foto" name="foto" placeholder="Masukkan foto.."ph>
                                @error('foto')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="form-group row">
                                <div class="col-lg-12 col-xl-12">
                                    <button type="submit" class="btn btn-primary mb-2 mb-md-0">Simpan Perubahan</button>
                                    <button type="button" class="btn btn-danger">Batal</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-xl-6">
                <div class="card border mb-0">

                    <div class="card-header">
                        <div class="row align-items-center">
                            <div class="col">
                                <h4 class="card-title mb-0">Change Password</h4>
                            </div><!--end col-->
                        </div>
                    </div>

                    <div class="card-body mb-0">
                        <form action="{{ route('password.update') }}" method="post">
                            @csrf
                            @method('PUT')
                            <div class="form-group mb-3">
                                <label for="current_password" class="form-label">Password Lama</label>
                                <input type="password" class="form-control @error('current_password') is-invalid @enderror"
                                    id="current_password" name="current_password" placeholder="Masukkan password lama.." required>
                                @error('current_password')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="form-group mb-3">
                                <label for="password" class="form-label">Password Baru</label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror"
                                    id="password" name="password" placeholder="Masukkan password baru.." required>
                                @error('password')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="form-group mb-3">
                                <label for="password_confirmation" class="form-label">Konfirmasi Password Baru</label>
                                <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror"
                                    id="password_confirmation" name="password_confirmation" placeholder="Masukkan konfirmasi password baru.." required>
                                @error('password_confirmation')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="form-group row">
                                <div class="col-lg-12 col-xl-12">
                                    <button type="submit" class="btn btn-primary mb-2 mb-md-0">Simpan Perubahan Password</button>
                                    <button type="button" class="btn btn-danger">Batal</button>
                                </div>
                            </div>
                        </form>
                    </div><!--end card-body-->
                </div>
            </div>
        </div><!--end row-->
    </div>
</div>
@endsection
