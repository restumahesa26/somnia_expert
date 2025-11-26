@extends('layouts.auth')

@section('title', 'Login')

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
                                                <h4 class="fs-20">Selamat Datang!</h4>
                                                <p class="text-muted mb-0">Sign in to continue to SomniaExpert.</p>
                                            </div>

                                            <form method="POST" action="{{ route('login') }}" class="pt-0">
                                                @csrf
                                                <div class="mb-3">
                                                    <label class="form-label" for="email">Email</label>
                                                    <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required placeholder="Enter your email">
                                                    @error('email')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>

                                                <div class="mb-3">
                                                    <label class="form-label" for="password">Password</label>
                                                    <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required placeholder="Enter your password">
                                                    @error('password')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>

                                                <div class="row justify-content-between mb-3">
                                                    <div class="col-auto">
                                                        <div class="form-check">
                                                            <input type="checkbox" class="form-check-input" id="remember" name="remember" {{ old('remember') ? 'checked' : '' }}>
                                                            <label class="form-check-label" for="remember">Remember me</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-auto">
                                                        <a href="{{ route('password.request') }}" class="text-muted">Forgot password?</a>
                                                    </div>
                                                </div>

                                                <div class="mb-0 text-center">
                                                    <button class="btn btn-primary w-100" type="submit">Log In</button>
                                                </div>

                                                <div class="text-center mt-4">
                                                    <p class="text-muted mb-0">Dont have an account? <a href="{{ route('register') }}" class="text-primary fw-semibold">Sign Up</a></p>
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
                                        <h4 class="mb-1">Sleep Better</h4>
                                        <p class="mb-0">Live Better</p>
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
