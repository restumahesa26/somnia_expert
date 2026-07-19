<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password | SomniaExpert</title>
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
                <a href="{{ route('login') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-slate-500 hover:text-primary mb-8 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Kembali ke Login
                </a>

                <div class="mb-10">
                    <h2 class="text-3xl font-bold mb-2">Reset Password</h2>
                    <p class="text-slate-500">Masukkan email Anda dan kami akan mengirimkan instruksi untuk mengatur ulang password Anda.</p>
                </div>

                @if (session('status'))
                    <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-700 rounded-xl text-sm font-medium flex items-start gap-3">
                        <svg class="w-5 h-5 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        {{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
                    @csrf
                    
                    <div>
                        <label for="email" class="block text-sm font-semibold text-slate-700 mb-2">Email Terdaftar</label>
                        <input type="email" name="email" id="email" value="{{ old('email') }}" required autofocus
                            class="w-full px-5 py-4 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-secondary/20 focus:border-secondary transition-all outline-none @error('email') border-red-500 ring-red-500/20 @enderror" 
                            placeholder="nama@email.com">
                        @error('email')
                            <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit" class="w-full py-4 bg-primary text-white font-bold rounded-xl hover:bg-slate-800 transition-all shadow-lg transform hover:-translate-y-0.5 focus:outline-none focus:ring-4 focus:ring-slate-200">
                        Kirim Link Reset Password
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
                        <div class="p-4 bg-yellow-500/20 rounded-full text-yellow-200">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                        </div>
                    </div>
                    <h3 class="text-2xl font-bold text-white mb-4">Aman & Terlindungi.</h3>
                    <p class="text-slate-300 leading-relaxed text-sm">Jangan khawatir, hal ini sering terjadi. Kami akan membantu Anda memulihkan akses ke akun Anda dengan aman.</p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
