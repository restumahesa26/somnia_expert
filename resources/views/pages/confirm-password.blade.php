<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Konfirmasi Password | SomniaExpert</title>
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ url('dist/assets/images/logo-sm.png') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
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

    <div class="w-full max-w-6xl bg-white rounded-3xl shadow-2xl overflow-hidden flex flex-col lg:flex-row min-h-[600px]">
        
        <!-- Form Section -->
        <div class="w-full lg:w-1/2 p-8 md:p-12 lg:p-16 flex flex-col justify-center relative">
            
            <!-- Logo (Mobile only) -->
            <div class="flex items-center gap-2 mb-10 lg:hidden">
                <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-secondary to-accent flex items-center justify-center text-white">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path></svg>
                </div>
                <span class="text-xl font-bold text-primary">Somnia<span class="text-secondary">Expert</span></span>
            </div>

            <div class="max-w-md w-full mx-auto">
                <div class="mb-10">
                    <h2 class="text-3xl font-bold mb-2">Konfirmasi Keamanan</h2>
                    <p class="text-slate-500">Ini adalah area aman dari aplikasi. Harap konfirmasi password Anda sebelum melanjutkan.</p>
                </div>

                <form method="POST" action="{{ route('password.confirm') }}" class="space-y-6">
                    @csrf

                    <div>
                        <label for="password" class="block text-sm font-semibold text-slate-700 mb-2">Password Anda</label>
                        <input type="password" name="password" id="password" required autocomplete="current-password"
                            class="w-full px-5 py-4 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-secondary/20 focus:border-secondary transition-all outline-none @error('password') border-red-500 ring-red-500/20 @enderror" 
                            placeholder="••••••••">
                        @error('password')
                            <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit" class="w-full py-4 bg-primary text-white font-bold rounded-xl hover:bg-slate-800 transition-all shadow-lg transform hover:-translate-y-0.5 focus:outline-none focus:ring-4 focus:ring-slate-200">
                        Konfirmasi Password
                    </button>
                </form>
            </div>
        </div>

        <!-- Image/Visual Section -->
        <div class="hidden lg:flex w-1/2 relative bg-primary items-center justify-center p-12 overflow-hidden">
            <!-- Background Elements -->
            <div class="absolute inset-0 bg-gradient-to-br from-slate-900 via-primary to-slate-800"></div>
            <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-10"></div>
            
            <div class="absolute top-0 right-0 w-96 h-96 bg-secondary blur-[100px] opacity-20 rounded-full translate-x-1/3 -translate-y-1/3"></div>
            <div class="absolute bottom-0 left-0 w-96 h-96 bg-accent blur-[100px] opacity-20 rounded-full -translate-x-1/3 translate-y-1/3"></div>

            <div class="relative z-10 w-full max-w-lg text-center">
                <div class="flex items-center justify-center gap-3 mb-12">
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-secondary to-accent flex items-center justify-center text-white shadow-xl shadow-blue-500/30">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path></svg>
                    </div>
                    <span class="text-3xl font-bold text-white">Somnia<span class="text-secondary">Expert</span></span>
                </div>

                <div class="bg-white/10 backdrop-blur-xl border border-white/20 p-8 rounded-3xl shadow-2xl">
                    <div class="mb-6 flex justify-center">
                        <div class="p-4 bg-green-500/20 rounded-full text-green-200">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                        </div>
                    </div>
                    <h3 class="text-2xl font-bold text-white mb-4">Perlindungan Ekstra.</h3>
                    <p class="text-slate-300 leading-relaxed text-sm">Privasi dan keamanan akun Anda adalah prioritas kami. Akses ke bagian ini memerlukan verifikasi tambahan.</p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
