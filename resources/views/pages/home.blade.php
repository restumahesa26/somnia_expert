<!DOCTYPE html>
<html lang="id" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SomniaExpert - Solusi Tidur Berkualitas Anda</title>
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ url('dist/assets/images/logo-sm.png') }}">
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
                        primary: '#0F172A', // Slate 900
                        secondary: '#3B82F6', // Blue 500
                        accent: '#8B5CF6', // Violet 500
                        surface: '#FFFFFF',
                        background: '#F8FAFC',
                    },
                    animation: {
                        'float': 'float 6s ease-in-out infinite',
                        'blob': 'blob 7s infinite',
                        'fade-in-up': 'fadeInUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards',
                    },
                    keyframes: {
                        float: {
                            '0%, 100%': {
                                transform: 'translateY(0)'
                            },
                            '50%': {
                                transform: 'translateY(-20px)'
                            },
                        },
                        blob: {
                            '0%': {
                                transform: 'translate(0px, 0px) scale(1)'
                            },
                            '33%': {
                                transform: 'translate(30px, -50px) scale(1.1)'
                            },
                            '66%': {
                                transform: 'translate(-20px, 20px) scale(0.9)'
                            },
                            '100%': {
                                transform: 'translate(0px, 0px) scale(1)'
                            },
                        },
                        fadeInUp: {
                            '0%': {
                                opacity: '0',
                                transform: 'translateY(20px)'
                            },
                            '100%': {
                                opacity: '1',
                                transform: 'translateY(0)'
                            },
                        }
                    }
                }
            }
        }
    </script>
    <style>
        .glass-nav {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.3);
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.5);
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.05);
        }

        .text-gradient {
            background-clip: text;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-image: linear-gradient(90deg, #3B82F6, #8B5CF6);
        }

        .delay-100 {
            animation-delay: 100ms;
        }

        .delay-200 {
            animation-delay: 200ms;
        }

        .delay-300 {
            animation-delay: 300ms;
        }
    </style>
</head>

<body class="bg-background text-primary antialiased relative overflow-x-hidden">

    <!-- Background Blobs -->
    <div
        class="absolute top-0 -left-4 w-72 h-72 bg-purple-300 rounded-full mix-blend-multiply filter blur-2xl opacity-30 animate-blob z-0">
    </div>
    <div
        class="absolute top-0 -right-4 w-72 h-72 bg-blue-300 rounded-full mix-blend-multiply filter blur-2xl opacity-30 animate-blob animation-delay-2000 z-0">
    </div>
    <div
        class="absolute -bottom-8 left-20 w-72 h-72 bg-indigo-300 rounded-full mix-blend-multiply filter blur-2xl opacity-30 animate-blob animation-delay-4000 z-0">
    </div>

    <!-- Navigation -->
    <header class="fixed w-full top-0 z-50 glass-nav transition-all duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <div class="flex items-center gap-3 cursor-pointer" onclick="window.scrollTo(0,0)">
                    <div
                        class="w-10 h-10 rounded-xl bg-gradient-to-br from-secondary to-accent flex items-center justify-center text-white shadow-lg">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z">
                            </path>
                        </svg>
                    </div>
                    <span class="text-2xl font-bold tracking-tight text-primary">Somnia<span
                            class="text-secondary">Expert</span></span>
                </div>

                <nav class="hidden md:flex items-center gap-8">
                    <a href="#how-it-works"
                        class="text-sm font-medium text-slate-600 hover:text-secondary transition-colors">Cara Kerja</a>
                    <a href="#features"
                        class="text-sm font-medium text-slate-600 hover:text-secondary transition-colors">Fitur</a>
                    @auth
                        <div class="flex items-center gap-4">
                            <span class="text-sm font-semibold text-slate-700">Hi,
                                {{ \App\Helpers\Helper::getFirstName(Auth::user()->nama) }}</span>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                    class="px-5 py-2.5 text-sm font-medium text-white bg-slate-900 rounded-full hover:bg-slate-800 transition-all shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                                    Logout
                                </button>
                            </form>
                        </div>
                    @else
                        <div class="flex items-center gap-3">
                            <a href="{{ route('login') }}"
                                class="px-5 py-2.5 text-sm font-medium text-slate-700 hover:text-secondary transition-colors">Masuk</a>
                            <a href="{{ route('register') }}"
                                class="px-6 py-2.5 text-sm font-medium text-white bg-gradient-to-r from-secondary to-accent rounded-full hover:shadow-lg hover:shadow-blue-500/30 transition-all transform hover:-translate-y-0.5">
                                Mulai Sekarang
                            </a>
                        </div>
                    @endauth
                </nav>
            </div>
        </div>
    </header>

    <main class="relative z-10 pt-32 pb-16 lg:pt-40 lg:pb-24">
        <!-- Hero Section -->
        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid lg:grid-cols-2 gap-12 lg:gap-8 items-center">
                <div class="text-center lg:text-left max-w-2xl mx-auto lg:mx-0">
                    <div
                        class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-blue-50 border border-blue-100 text-secondary text-sm font-semibold mb-6 animate-fade-in-up">
                        <span class="relative flex h-2 w-2">
                            <span
                                class="animate-ping absolute inline-flex h-full w-full rounded-full bg-secondary opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2 w-2 bg-secondary"></span>
                        </span>
                        Sistem Pakar Berbasis VCIRS
                    </div>
                    <h1
                        class="text-5xl lg:text-7xl font-extrabold tracking-tight mb-6 animate-fade-in-up delay-100 leading-[1.1]">
                        Tidur Nyenyak, <br />
                        <span class="text-gradient">Hidup Berkualitas.</span>
                    </h1>
                    <p class="text-lg text-slate-600 mb-8 animate-fade-in-up delay-200 leading-relaxed">
                        Identifikasi gangguan tidur Anda lebih dini dengan akurasi klinis. Sistem pakar cerdas kami
                        menganalisis pola tidur untuk memberikan solusi yang tepat bagi kesehatan Anda.
                    </p>
                    <div
                        class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start animate-fade-in-up delay-300">
                        <a href="{{ route('register') }}"
                            class="px-8 py-4 text-base font-semibold text-white bg-primary rounded-full hover:bg-slate-800 transition-all shadow-xl hover:shadow-2xl transform hover:-translate-y-1 flex items-center justify-center gap-2">
                            Mulai Screening <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                            </svg>
                        </a>
                        <a href="#how-it-works"
                            class="px-8 py-4 text-base font-semibold text-slate-700 bg-white border border-slate-200 rounded-full hover:bg-slate-50 transition-all flex items-center justify-center gap-2">
                            Pelajari Lebih Lanjut
                        </a>
                    </div>
                </div>

                <div class="relative hidden lg:block animate-fade-in-up delay-200">
                    <div class="relative w-full aspect-square animate-float">
                        <!-- Abstract Sleep Data Visualization -->
                        <div
                            class="absolute inset-0 bg-gradient-to-tr from-blue-100 to-purple-50 rounded-3xl transform rotate-3 scale-105">
                        </div>
                        <div
                            class="absolute inset-0 glass-card rounded-3xl p-8 flex flex-col justify-between overflow-hidden">
                            <div class="flex justify-between items-start">
                                <div>
                                    <p class="text-sm font-medium text-slate-500 mb-1">Skor Kualitas Tidur</p>
                                    <h3 class="text-4xl font-bold text-primary">85<span
                                            class="text-lg text-slate-400 font-normal">/100</span></h3>
                                </div>
                                <div
                                    class="w-12 h-12 rounded-full bg-green-100 flex items-center justify-center text-green-600">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </div>
                            </div>

                            <div class="h-32 mt-8 relative w-full flex items-end gap-2">
                                <!-- Mock Chart -->
                                <div
                                    class="w-full bg-secondary/20 rounded-t-lg h-1/3 hover:h-2/3 transition-all duration-500 cursor-pointer">
                                </div>
                                <div
                                    class="w-full bg-secondary/40 rounded-t-lg h-1/2 hover:h-3/4 transition-all duration-500 cursor-pointer">
                                </div>
                                <div
                                    class="w-full bg-secondary rounded-t-lg h-full hover:h-full transition-all duration-500 cursor-pointer relative group">
                                    <div
                                        class="absolute -top-10 left-1/2 -translate-x-1/2 bg-slate-800 text-white text-xs py-1 px-2 rounded opacity-0 group-hover:opacity-100 transition-opacity">
                                        Deep Sleep</div>
                                </div>
                                <div
                                    class="w-full bg-secondary/60 rounded-t-lg h-3/4 hover:h-full transition-all duration-500 cursor-pointer">
                                </div>
                                <div
                                    class="w-full bg-secondary/30 rounded-t-lg h-1/4 hover:h-1/2 transition-all duration-500 cursor-pointer">
                                </div>
                            </div>

                            <div
                                class="mt-6 flex items-center gap-3 bg-white/60 p-4 rounded-2xl border border-white/40">
                                <div
                                    class="w-10 h-10 rounded-full bg-accent/10 flex items-center justify-center text-accent">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-primary">Analisis Selesai</p>
                                    <p class="text-xs text-slate-500">Hasil didasarkan pada metode Certainty Factor</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- How It Works -->
        <section id="how-it-works" class="py-24 mt-12 bg-white relative">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center max-w-3xl mx-auto mb-16">
                    <h2 class="text-secondary font-semibold tracking-wide uppercase text-sm mb-3">Langkah Mudah</h2>
                    <h3 class="text-3xl md:text-4xl font-bold text-primary">Bagaimana SomniaExpert Membantu Anda?</h3>
                </div>

                <div class="grid md:grid-cols-3 gap-8 relative">
                    <!-- Connecting line -->
                    <div
                        class="hidden md:block absolute top-12 left-[15%] right-[15%] h-0.5 bg-gradient-to-r from-blue-100 via-purple-100 to-blue-100 z-0">
                    </div>

                    <!-- Step 1 -->
                    <div class="relative z-10 text-center group">
                        <div
                            class="w-24 h-24 mx-auto bg-white border border-slate-100 rounded-3xl shadow-xl shadow-blue-900/5 flex items-center justify-center mb-6 transform group-hover:-translate-y-2 transition-all duration-300">
                            <div
                                class="w-16 h-16 rounded-2xl bg-blue-50 text-secondary flex items-center justify-center text-2xl font-bold">
                                1</div>
                        </div>
                        <h4 class="text-xl font-bold text-primary mb-3">Isi Kuesioner</h4>
                        <p class="text-slate-600 text-sm leading-relaxed">Jawab beberapa pertanyaan komprehensif
                            mengenai riwayat dan kebiasaan tidur Anda.</p>
                    </div>

                    <!-- Step 2 -->
                    <div class="relative z-10 text-center group">
                        <div
                            class="w-24 h-24 mx-auto bg-white border border-slate-100 rounded-3xl shadow-xl shadow-purple-900/5 flex items-center justify-center mb-6 transform group-hover:-translate-y-2 transition-all duration-300">
                            <div
                                class="w-16 h-16 rounded-2xl bg-purple-50 text-accent flex items-center justify-center text-2xl font-bold">
                                2</div>
                        </div>
                        <h4 class="text-xl font-bold text-primary mb-3">Analisis Sistem</h4>
                        <p class="text-slate-600 text-sm leading-relaxed">Sistem pakar kami akan mengkalkulasi data
                            menggunakan metode VCIRS & Certainty Factor.</p>
                    </div>

                    <!-- Step 3 -->
                    <div class="relative z-10 text-center group">
                        <div
                            class="w-24 h-24 mx-auto bg-white border border-slate-100 rounded-3xl shadow-xl shadow-blue-900/5 flex items-center justify-center mb-6 transform group-hover:-translate-y-2 transition-all duration-300">
                            <div
                                class="w-16 h-16 rounded-2xl bg-blue-50 text-secondary flex items-center justify-center text-2xl font-bold">
                                3</div>
                        </div>
                        <h4 class="text-xl font-bold text-primary mb-3">Terima Hasil</h4>
                        <p class="text-slate-600 text-sm leading-relaxed">Dapatkan diagnosis awal yang akurat beserta
                            saran penanganan untuk meningkatkan kualitas tidur.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA Section -->
        <section class="py-20 relative overflow-hidden">
            <div class="absolute inset-0 bg-primary z-0"></div>
            <div
                class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-10 z-0">
            </div>
            <div
                class="absolute top-0 right-0 w-96 h-96 bg-secondary blur-3xl opacity-20 rounded-full translate-x-1/2 -translate-y-1/2">
            </div>
            <div
                class="absolute bottom-0 left-0 w-96 h-96 bg-accent blur-3xl opacity-20 rounded-full -translate-x-1/2 translate-y-1/2">
            </div>

            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
                <h2 class="text-4xl md:text-5xl font-bold text-white mb-6">Siap Meraih Tidur Berkualitas?</h2>
                <p class="text-lg text-slate-300 mb-10 max-w-2xl mx-auto">Jangan biarkan gangguan tidur menurunkan
                    produktivitas dan kualitas hidup Anda. Lakukan screening sekarang.</p>
                <a href="{{ route('register') }}"
                    class="inline-flex items-center gap-2 px-10 py-5 text-lg font-bold text-primary bg-white rounded-full hover:bg-slate-50 transition-all shadow-2xl hover:shadow-white/20 transform hover:-translate-y-1">
                    Mulai Screening Gratis <svg class="w-5 h-5" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                    </svg>
                </a>
            </div>
        </section>
    </main>

    <footer class="bg-white border-t border-slate-200 pt-16 pb-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-6">
                <div class="flex items-center gap-2">
                    <svg class="w-6 h-6 text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z">
                        </path>
                    </svg>
                    <span class="text-xl font-bold text-primary">Somnia<span
                            class="text-secondary">Expert</span></span>
                </div>
                <div class="flex items-center gap-6 text-sm font-medium text-slate-500">
                    <button onclick="document.getElementById('privacy-modal').classList.remove('hidden')"
                        class="hover:text-primary transition-colors">Kebijakan Privasi</button>
                    <button onclick="document.getElementById('terms-modal').classList.remove('hidden')"
                        class="hover:text-primary transition-colors">Syarat & Ketentuan</button>
                </div>
            </div>
            <div class="text-center md:text-left text-sm text-slate-400 border-t border-slate-100 pt-8">
                &copy; {{ date('Y') }} SomniaExpert. Seluruh hak cipta dilindungi.
            </div>
        </div>
    </footer>

    <!-- Modals (Simplified & Beautified) -->
    <div id="privacy-modal" class="fixed inset-0 z-[100] hidden">
        <div class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm"
            onclick="this.parentElement.classList.add('hidden')"></div>
        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-full max-w-lg">
            <div class="bg-white rounded-3xl shadow-2xl p-8 transform transition-all">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-2xl font-bold text-primary">Kebijakan Privasi</h3>
                    <button onclick="document.getElementById('privacy-modal').classList.add('hidden')"
                        class="text-slate-400 hover:text-slate-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <div class="prose prose-sm text-slate-600 max-h-96 overflow-y-auto pr-4 custom-scrollbar">
                    <p class="mb-4">Di SomniaExpert, privasi Anda adalah prioritas kami. Data pribadi dan informasi
                        kesehatan Anda dilindungi dengan enkripsi standar industri.</p>
                    <h4 class="font-bold text-slate-800 mt-4 mb-2">Penggunaan Data</h4>
                    <p>Data digunakan murni untuk analisis sistem pakar guna memberikan hasil screening yang seakurat
                        mungkin.</p>
                </div>
            </div>
        </div>
    </div>

    <div id="terms-modal" class="fixed inset-0 z-[100] hidden">
        <div class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm"
            onclick="this.parentElement.classList.add('hidden')"></div>
        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-full max-w-lg">
            <div class="bg-white rounded-3xl shadow-2xl p-8 transform transition-all">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-2xl font-bold text-primary">Syarat & Ketentuan</h3>
                    <button onclick="document.getElementById('terms-modal').classList.add('hidden')"
                        class="text-slate-400 hover:text-slate-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <div class="prose prose-sm text-slate-600 max-h-96 overflow-y-auto pr-4 custom-scrollbar">
                    <p class="mb-4">Dengan menggunakan aplikasi ini, Anda menyetujui bahwa sistem ini bersifat
                        screening awal, bukan pengganti diagnosis medis profesional.</p>
                    <h4 class="font-bold text-slate-800 mt-4 mb-2">Tanggung Jawab Pengguna</h4>
                    <p>Anda diwajibkan mengisi data dengan jujur untuk hasil yang akurat. Konsultasikan dengan dokter
                        spesialis untuk tindak lanjut.</p>
                </div>
            </div>
        </div>
    </div>

    <style>
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 4px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
    </style>
</body>

</html>
