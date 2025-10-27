@extends('layouts.auth')

@section('title', 'Forgot Password')

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
                                            <div class="mb-4 p-0">
                                                <h4 class="fs-20">Reset Password</h4>
                                                <p class="text-muted mb-0">Enter your email and well send you instructions to reset your password.</p>
                                            </div>

                                            @if (session('status'))
                                                <div class="alert alert-success" role="alert">
                                                    {{ session('status') }}
                                                </div>
                                            @endif

                                            <form method="POST" action="{{ route('password.email') }}" class="pt-0">
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

                                                <div class="mb-0 text-center">
                                                    <button class="btn btn-primary w-100" type="submit">Reset Password</button>
                                                </div>

                                                <div class="text-center mt-4">
                                                    <p class="text-muted mb-0">Remember your password? <a href="{{ route('login') }}" class="text-primary fw-semibold">Log In</a></p>
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
                                        <p class="prelead mb-2">Don't worry, it happens to the best of us. We'll help you get back to your account.</p>
                                        <h4 class="mb-1">Account Recovery</h4>
                                        <p class="mb-0">Made Simple</p>
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
