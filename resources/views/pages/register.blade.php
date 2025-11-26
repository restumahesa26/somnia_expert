@extends('layouts.auth')

@section('title', 'Register')

@section('content')
    <div class="account-page">
        <div class="container-fluid p-0">
            <div class="row align-items-center g-0 px-3 py-3 vh-100">
                <div class="col-xl-5">
                    <div class="row">
                        <div class="col-md-8 mx-auto">
                            <div class="card">
                                <div class="card-body">
                                    <div class="mb-0 p-0 p-lg-3">
                                        <div class="mb-0 border-0 p-md-4 p-lg-0">
                                            <div class="mb-4 p-0 text-lg-start text-center">
                                                <h4 class="fs-20">Create New Account</h4>
                                                <p class="text-muted mb-0">Get started with SomniaExpert</p>
                                            </div>

                                            <form method="POST" action="{{ route('register') }}" class="pt-0">
                                                @csrf
                                                <div class="mb-3">
                                                    <label class="form-label" for="nama">Nama</label>
                                                    <input type="text" class="form-control @error('nama') is-invalid @enderror" id="nama" name="nama" value="{{ old('nama') }}" required placeholder="Masukkan nama lengkap">
                                                    @error('nama')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>

                                                <div class="row mb-3">
                                                    <div class="col-6">
                                                        <label class="form-label" for="umur">Umur</label>
                                                        <input type="number" class="form-control @error('umur') is-invalid @enderror" id="umur" name="umur" value="{{ old('umur') }}" required placeholder="Masukkan umur" min="0">
                                                        @error('umur')
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                                        @enderror
                                                    </div>
                                                    <div class="col-6">
                                                        <label class="form-label" for="jenis_kelamin">Jenis Kelamin</label>
                                                        <select class="form-select @error('jenis_kelamin') is-invalid @enderror" id="jenis_kelamin" name="jenis_kelamin" required>
                                                            <option value="" selected disabled>Pilih Jenis Kelamin</option>
                                                            <option value="L" {{ old('jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                                            <option value="P" {{ old('jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan</option>
                                                        </select>
                                                        @error('jenis_kelamin')
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="mb-3">
                                                    <label class="form-label" for="username">Username</label>
                                                    <input type="text" class="form-control @error('username') is-invalid @enderror" id="username" name="username" value="{{ old('username') }}" required placeholder="Masukkan username">
                                                    @error('username')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>

                                                <div class="mb-3">
                                                    <label class="form-label" for="email">Email</label>
                                                    <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required placeholder="Masukkan email">
                                                    @error('email')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>

                                                <div class="mb-3">
                                                    <label class="form-label" for="password">Password</label>
                                                    <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required placeholder="Masukkan password">
                                                    @error('password')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>

                                                <div class="mb-3">
                                                    <label class="form-label" for="password_confirmation">Confirm Password</label>
                                                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required placeholder="Masukkan konfirmasi password">
                                                </div>

                                                <div class="mb-0 text-center">
                                                    <button class="btn btn-primary w-100" type="submit">Sign Up</button>
                                                </div>

                                                <div class="text-center mt-4">
                                                    <p class="text-muted mb-0">Already have an account? <a href="{{ route('login') }}" class="text-primary fw-semibold">Log In</a></p>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-7 d-none d-xl-inline-block">
                    <div class="account-page-bg rounded-4">
                        <div class="auth-user-review text-center">
                            <div id="carouselExampleFade" class="carousel slide carousel-fade" data-bs-ride="carousel">
                                <div class="carousel-inner">
                                    <div class="carousel-item active">
                                        <p class="prelead mb-2">Discover better sleep with SomniaExpert.</p>
                                        <h4 class="mb-1">Better Sleep</h4>
                                        <p class="mb-0">Starts Here</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
