<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Email | SomniaExpert</title>
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
                    <h2 class="text-3xl font-bold mb-2">Periksa Email Anda</h2>
                    <p class="text-slate-500 text-sm leading-relaxed">Terima kasih telah mendaftar! Sebelum memulai, mohon verifikasi alamat email Anda dengan mengklik link yang baru saja kami kirimkan. Jika Anda tidak menerima email tersebut, kami akan mengirimkan yang baru.</p>
                </div>

                @if (session('status') == 'verification-link-sent')
                    <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-700 rounded-xl text-sm font-medium flex items-start gap-3">
                        <svg class="w-5 h-5 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Link verifikasi baru telah dikirimkan ke alamat email yang Anda berikan saat pendaftaran.
                    </div>
                @endif

                <div class="flex flex-col sm:flex-row items-center justify-between gap-4 mt-8">
                    <!-- Form Kirim Ulang -->
                    <form method="POST" action="{{ route('verification.send') }}" class="w-full sm:w-auto">
                        @csrf
                        <button type="submit" class="w-full sm:w-auto px-6 py-3.5 bg-primary text-white font-bold rounded-xl hover:bg-slate-800 transition-all shadow-lg transform hover:-translate-y-0.5 focus:outline-none focus:ring-4 focus:ring-slate-200">
                            Kirim Ulang Email Verifikasi
                        </button>
                    </form>

                    <!-- Form Logout -->
                    <form method="POST" action="{{ route('logout') }}" class="w-full sm:w-auto">
                        @csrf
                        <button type="submit" class="w-full sm:w-auto px-6 py-3.5 bg-white border border-slate-200 text-slate-700 font-bold rounded-xl hover:bg-slate-50 transition-all focus:outline-none focus:ring-4 focus:ring-slate-100">
                            Keluar
                        </button>
                    </form>
                </div>
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
                        <div class="p-4 bg-blue-500/20 rounded-full text-blue-200">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                        </div>
                    </div>
                    <h3 class="text-2xl font-bold text-white mb-4">Satu Langkah Lagi.</h3>
                    <p class="text-slate-300 leading-relaxed text-sm">Verifikasi identitas Anda untuk mendapatkan akses penuh ke fitur diagnosis dan konsultasi tidur berkualitas.</p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
