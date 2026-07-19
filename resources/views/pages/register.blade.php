<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar | SomniaExpert</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                    },
                    colors: {
                        primary: '#0F172A',
                        secondary: '#3B82F6',
                        accent: '#8B5CF6',
                        background: '#F8FAFC',
                    }
                }
            }
        }
    </script>
</head>

<body class="bg-background text-primary antialiased min-h-screen flex items-center justify-center p-4 py-10 lg:py-12">

    <div
        class="w-full max-w-6xl bg-white rounded-3xl shadow-2xl overflow-hidden flex flex-col lg:flex-row-reverse min-h-[700px]">

        <!-- Form Section -->
        <div
            class="w-full lg:w-1/2 p-8 md:p-12 lg:p-16 flex flex-col justify-center relative overflow-y-auto custom-scrollbar">

            <!-- Logo (Mobile only) -->
            <div class="flex items-center gap-2 mb-10 lg:hidden">
                <div
                    class="w-8 h-8 rounded-lg bg-gradient-to-br from-secondary to-accent flex items-center justify-center text-white">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z">
                        </path>
                    </svg>
                </div>
                <span class="text-xl font-bold text-primary">Somnia<span class="text-secondary">Expert</span></span>
            </div>

            <div class="max-w-md w-full mx-auto">
                <div class="mb-10">
                    <h2 class="text-3xl font-bold mb-2">Buat Akun Baru</h2>
                    <p class="text-slate-500">Mulai perjalanan tidur sehat Anda sekarang.</p>
                </div>

                <form method="POST" action="{{ route('register') }}" class="space-y-5">
                    @csrf

                    <div>
                        <label for="nama" class="block text-sm font-semibold text-slate-700 mb-2">Nama
                            Lengkap</label>
                        <input type="text" name="nama" id="nama" value="{{ old('nama') }}" required
                            autofocus
                            class="w-full px-5 py-3 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-secondary/20 focus:border-secondary transition-all outline-none @error('nama') border-red-500 ring-red-500/20 @enderror"
                            placeholder="John Doe">
                        @error('nama')
                            <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="umur" class="block text-sm font-semibold text-slate-700 mb-2">Umur</label>
                            <input type="number" name="umur" id="umur" value="{{ old('umur') }}" required
                                min="0"
                                class="w-full px-5 py-3 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-secondary/20 focus:border-secondary transition-all outline-none @error('umur') border-red-500 ring-red-500/20 @enderror"
                                placeholder="Misal: 25">
                            @error('umur')
                                <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="jenis_kelamin" class="block text-sm font-semibold text-slate-700 mb-2">Jenis
                                Kelamin</label>
                            <div class="relative">
                                <select name="jenis_kelamin" id="jenis_kelamin" required
                                    class="w-full px-5 py-3 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-secondary/20 focus:border-secondary transition-all outline-none appearance-none @error('jenis_kelamin') border-red-500 ring-red-500/20 @enderror">
                                    <option value="" disabled {{ old('jenis_kelamin') ? '' : 'selected' }}>
                                        Pilih...</option>
                                    <option value="L" {{ old('jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-laki
                                    </option>
                                    <option value="P" {{ old('jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan
                                    </option>
                                </select>
                                <div
                                    class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-slate-500">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </div>
                            </div>
                            @error('jenis_kelamin')
                                <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label for="username" class="block text-sm font-semibold text-slate-700 mb-2">Username</label>
                        <input type="text" name="username" id="username" value="{{ old('username') }}" required
                            class="w-full px-5 py-3 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-secondary/20 focus:border-secondary transition-all outline-none @error('username') border-red-500 ring-red-500/20 @enderror"
                            placeholder="johndoe123">
                        @error('username')
                            <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-semibold text-slate-700 mb-2">Email</label>
                        <input type="email" name="email" id="email" value="{{ old('email') }}" required
                            class="w-full px-5 py-3 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-secondary/20 focus:border-secondary transition-all outline-none @error('email') border-red-500 ring-red-500/20 @enderror"
                            placeholder="nama@email.com">
                        @error('email')
                            <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-semibold text-slate-700 mb-2">Password</label>
                        <input type="password" name="password" id="password" required
                            class="w-full px-5 py-3 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-secondary/20 focus:border-secondary transition-all outline-none @error('password') border-red-500 ring-red-500/20 @enderror"
                            placeholder="••••••••">
                        @error('password')
                            <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password_confirmation"
                            class="block text-sm font-semibold text-slate-700 mb-2">Konfirmasi Password</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" required
                            class="w-full px-5 py-3 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-secondary/20 focus:border-secondary transition-all outline-none"
                            placeholder="••••••••">
                    </div>

                    <button type="submit"
                        class="w-full py-4 bg-primary text-white font-bold rounded-xl hover:bg-slate-800 transition-all shadow-lg transform hover:-translate-y-0.5 focus:outline-none focus:ring-4 focus:ring-slate-200 mt-2">
                        Daftar Sekarang
                    </button>
                </form>

                <p class="mt-8 text-center text-sm font-medium text-slate-500">
                    Sudah punya akun? <a href="{{ route('login') }}"
                        class="text-secondary hover:text-blue-700 font-bold transition-colors">Masuk di sini</a>
                </p>
            </div>
        </div>

        <!-- Image/Visual Section -->
        <div class="hidden lg:flex w-1/2 relative bg-primary items-center justify-center p-12 overflow-hidden">
            <!-- Background Elements -->
            <div class="absolute inset-0 bg-gradient-to-br from-slate-900 via-primary to-slate-800"></div>
            <div
                class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-10">
            </div>

            <div
                class="absolute top-0 left-0 w-96 h-96 bg-accent blur-[100px] opacity-20 rounded-full -translate-x-1/3 -translate-y-1/3">
            </div>
            <div
                class="absolute bottom-0 right-0 w-96 h-96 bg-secondary blur-[100px] opacity-20 rounded-full translate-x-1/3 translate-y-1/3">
            </div>

            <div class="relative z-10 w-full max-w-lg text-center">
                <div class="flex items-center justify-center gap-3 mb-12">
                    <div
                        class="w-12 h-12 rounded-xl bg-gradient-to-br from-secondary to-accent flex items-center justify-center text-white shadow-xl shadow-blue-500/30">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z">
                            </path>
                        </svg>
                    </div>
                    <span class="text-3xl font-bold text-white">Somnia<span
                            class="text-secondary">Expert</span></span>
                </div>

                <div class="bg-white/10 backdrop-blur-xl border border-white/20 p-8 rounded-3xl shadow-2xl">
                    <div class="mb-6 flex justify-center">
                        <div class="p-4 bg-accent/20 rounded-full text-purple-200">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <h3 class="text-2xl font-bold text-white mb-4">Langkah Pertama<br>Menuju Perubahan.</h3>
                    <p class="text-slate-300 leading-relaxed text-sm">Bergabunglah dengan pengguna lain yang telah
                        memperbaiki kualitas tidur mereka bersama SomniaExpert.</p>
                </div>
            </div>
        </div>
    </div>
    <style>
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #e2e8f0;
            border-radius: 4px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #cbd5e1;
        }
    </style>
</body>

</html>
