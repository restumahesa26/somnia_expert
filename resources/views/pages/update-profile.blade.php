@extends('layouts.template')

@section('title', 'Akun Saya')

@section('content')
    <div class="mb-4 mt-3">
        <h2 class="text-3xl font-bold text-slate-800 tracking-tight">Pengaturan Akun</h2>
        <p class="text-slate-500 mt-2">Kelola informasi profil dan pengaturan keamanan akun Anda.</p>
    </div>

    @if (session('success'))
        <div
            class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl mb-6 flex items-center gap-3 shadow-sm">
            <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <p class="font-medium text-sm">{{ session('success') }}</p>
        </div>
    @endif

    @if (session('error'))
        <div
            class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl mb-6 flex items-center gap-3 shadow-sm">
            <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <p class="font-medium text-sm">{{ session('error') }}</p>
        </div>
    @endif

    <div class="grid grid-cols-1 xl:grid-cols-2 gap-8">

        <!-- Personal Information -->
        <div class="card h-full">
            <div class="card-header flex items-center gap-3">
                <div class="p-2 bg-blue-50 text-secondary rounded-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </div>
                <h4 class="text-lg font-bold text-slate-800">Informasi Personal</h4>
            </div>

            <div class="card-body">
                <!-- Tampilkan Foto Profil Current -->
                <div class="flex items-center gap-6 mb-8">
                    <img src="{{ Auth::user()->foto != '' ? asset('uploads/foto-profil/' . Auth::user()->foto) : asset('dist/assets/images/users/user-23.jpg') }}"
                        alt="Profile" class="w-24 h-24 rounded-2xl object-cover shadow-md border-4 border-white">
                    <div>
                        <h5 class="font-bold text-slate-800 text-lg">{{ Auth::user()->nama }}</h5>
                        <p class="text-sm text-slate-500">{{ Auth::user()->email }}</p>
                        <span class="inline-block mt-2 px-3 py-1 bg-blue-50 text-secondary text-xs font-bold rounded-full">
                            {{ Auth::user()->is_admin ? 'Administrator' : 'Pengguna' }}
                        </span>
                    </div>
                </div>

                <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                    @csrf
                    @method('PATCH')

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div>
                            <label for="nama" class="form-label">Nama Lengkap</label>
                            <input type="text"
                                class="form-control w-full @error('nama') border-red-500 ring-red-500/20 @enderror"
                                id="nama" name="nama" value="{{ old('nama', $user->nama) }}" placeholder="John Doe"
                                required>
                            @error('nama')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="username" class="form-label">Username</label>
                            <input type="text"
                                class="form-control w-full @error('username') border-red-500 ring-red-500/20 @enderror"
                                id="username" name="username" value="{{ old('username', $user->username) }}"
                                placeholder="johndoe123" required>
                            @error('username')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label for="email" class="form-label">Alamat Email</label>
                        <input type="email"
                            class="form-control w-full @error('email') border-red-500 ring-red-500/20 @enderror"
                            id="email" name="email" value="{{ old('email', $user->email) }}"
                            placeholder="nama@email.com" required>
                        @error('email')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div>
                            <label for="umur" class="form-label">Umur</label>
                            <input type="number"
                                class="form-control w-full @error('umur') border-red-500 ring-red-500/20 @enderror"
                                id="umur" name="umur" value="{{ old('umur', $user->umur) }}" placeholder="25"
                                min="0" required>
                            @error('umur')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="jenis_kelamin" class="form-label">Jenis Kelamin</label>
                            <select
                                class="form-control w-full appearance-none @error('jenis_kelamin') border-red-500 ring-red-500/20 @enderror"
                                id="jenis_kelamin" name="jenis_kelamin" required>
                                <option value="" disabled
                                    {{ old('jenis_kelamin', $user->jenis_kelamin) ? '' : 'selected' }}>Pilih Jenis Kelamin
                                </option>
                                <option value="L"
                                    {{ old('jenis_kelamin', $user->jenis_kelamin) == 'L' ? 'selected' : '' }}>Laki-laki
                                </option>
                                <option value="P"
                                    {{ old('jenis_kelamin', $user->jenis_kelamin) == 'P' ? 'selected' : '' }}>Perempuan
                                </option>
                            </select>
                            @error('jenis_kelamin')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label for="foto" class="form-label">Ganti Foto Profil (Opsional)</label>
                        <input type="file"
                            class="form-control w-full file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-secondary hover:file:bg-blue-100 transition-colors @error('foto') border-red-500 ring-red-500/20 @enderror"
                            id="foto" name="foto" accept="image/*">
                        <p class="mt-2 text-xs text-slate-500">Format yang didukung: JPG, PNG, JPEG. Ukuran maksimal: 2MB.
                        </p>
                        @error('foto')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="pt-4 border-t border-slate-100 flex gap-3">
                        <button type="submit" class="btn btn-primary w-full sm:w-auto">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                                </path>
                            </svg>
                            Simpan Perubahan
                        </button>
                        <a href="{{ route('dashboard') }}"
                            class="btn bg-slate-100 text-slate-700 hover:bg-slate-200 shadow-none border-none">Batal</a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Change Password -->
        <div class="card h-full">
            <div class="card-header flex items-center gap-3">
                <div class="p-2 bg-purple-50 text-accent rounded-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                        </path>
                    </svg>
                </div>
                <h4 class="text-lg font-bold text-slate-800">Ubah Password</h4>
            </div>

            <div class="card-body">
                <div class="mb-6">
                    <p class="text-sm text-slate-500 leading-relaxed">
                        Pastikan akun Anda menggunakan password yang panjang, acak, dan aman untuk tetap terlindungi.
                    </p>
                </div>

                <form action="{{ route('password.update') }}" method="post" class="space-y-5">
                    @csrf
                    @method('PUT')

                    <div>
                        <label for="current_password" class="form-label">Password Saat Ini</label>
                        <input type="password"
                            class="form-control w-full @error('current_password', 'updatePassword') border-red-500 ring-red-500/20 @enderror"
                            id="current_password" name="current_password" placeholder="••••••••" required>
                        @error('current_password', 'updatePassword')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password" class="form-label">Password Baru</label>
                        <input type="password"
                            class="form-control w-full @error('password', 'updatePassword') border-red-500 ring-red-500/20 @enderror"
                            id="password" name="password" placeholder="••••••••" required>
                        @error('password', 'updatePassword')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password_confirmation" class="form-label">Konfirmasi Password Baru</label>
                        <input type="password"
                            class="form-control w-full @error('password_confirmation', 'updatePassword') border-red-500 ring-red-500/20 @enderror"
                            id="password_confirmation" name="password_confirmation" placeholder="••••••••" required>
                        @error('password_confirmation', 'updatePassword')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="pt-4 border-t border-slate-100 flex gap-3">
                        <button type="submit"
                            class="btn bg-accent text-white hover:bg-purple-600 focus:ring-purple-200 w-full sm:w-auto">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                                </path>
                            </svg>
                            Perbarui Password
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
@endsection
