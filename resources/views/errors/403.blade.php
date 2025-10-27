@extends('layouts.template')

@section('title', 'Akses Ditolak')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body text-center py-5">
                    <h1 class="display-1 text-danger mb-4">403</h1>
                    <h2 class="mb-4">Akses Ditolak</h2>
                    <p class="mb-4">Maaf, Anda tidak memiliki akses untuk melihat data ini.</p>
                    <a href="{{ route('dashboard') }}" class="btn btn-primary">
                        <i class="mdi mdi-home me-1"></i> Kembali ke Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
