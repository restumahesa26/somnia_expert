<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SomniaExpert - Sistem Pakar Gangguan Tidur</title>
    <!-- Memuat Tailwind CSS dari CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Mengatur Font Inter sebagai default -->
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap');
        body {
            font-family: 'Inter', sans-serif;
            scroll-behavior: smooth;
            /* Tema Putih dan Bersih */
            background-color: #f7f9fb;
            color: #374151; /* Teks Default (Dark Gray) */
        }

        /* Definisi Warna Kustom untuk Profesionalisme */
        .color-primary { background-color: #1a4f78; } /* Biru Tua/Navy */
        .color-accent { background-color: #3b82f6; } /* Biru Langit (Aksen) */
        .color-text-dark { color: #1a4f78; } /* Teks Header Utama */
    </style>
</head>
<body class="transition-colors duration-500">

    <!-- Modal Kebijakan Privasi -->
    <div id="privacy-modal" class="fixed inset-0 bg-gray-900 bg-opacity-50 z-50 hidden flex items-center justify-center p-4">
        <div class="bg-white rounded-xl shadow-2xl p-6 md:p-8 w-full max-w-2xl transform transition-all duration-300 scale-95 opacity-0">
            <h3 class="text-2xl font-bold color-text-dark mb-4">Kebijakan Privasi</h3>
            <div class="prose prose-sm text-gray-600 mb-6 max-h-96 overflow-y-auto">
                <p class="mb-4">Di SomniaExpert, kami sangat menghargai privasi Anda. Berikut adalah ringkasan bagaimana kami menangani informasi Anda:</p>

                <h4 class="text-lg font-semibold mb-2 color-text-dark">1. Informasi yang Kami Kumpulkan</h4>
                <ul class="list-disc pl-5 mb-4">
                    <li>Data pribadi (nama, email)</li>
                    <li>Informasi kesehatan terkait pola tidur</li>
                    <li>Riwayat diagnosis dan konsultasi</li>
                </ul>

                <h4 class="text-lg font-semibold mb-2 color-text-dark">2. Penggunaan Informasi</h4>
                <ul class="list-disc pl-5 mb-4">
                    <li>Memberikan layanan diagnosis gangguan tidur</li>
                    <li>Meningkatkan akurasi sistem pakar</li>
                    <li>Komunikasi terkait layanan</li>
                </ul>

                <h4 class="text-lg font-semibold mb-2 color-text-dark">3. Keamanan Data</h4>
                <p class="mb-4">Kami menggunakan enkripsi standar industri dan praktek keamanan terbaik untuk melindungi data Anda.</p>

                <h4 class="text-lg font-semibold mb-2 color-text-dark">4. Berbagi Informasi</h4>
                <p>Kami tidak akan pernah menjual atau membagikan informasi pribadi Anda kepada pihak ketiga tanpa izin Anda.</p>
            </div>
            <div class="flex justify-end">
                <button onclick="closePrivacyModal()" class="py-2 px-6 bg-gray-200 text-gray-800 font-semibold rounded-lg hover:bg-gray-300 transition duration-300">Tutup</button>
            </div>
        </div>
    </div>

    <!-- Modal Syarat & Ketentuan -->
    <div id="terms-modal" class="fixed inset-0 bg-gray-900 bg-opacity-50 z-50 hidden flex items-center justify-center p-4">
        <div class="bg-white rounded-xl shadow-2xl p-6 md:p-8 w-full max-w-2xl transform transition-all duration-300 scale-95 opacity-0">
            <h3 class="text-2xl font-bold color-text-dark mb-4">Syarat & Ketentuan</h3>
            <div class="prose prose-sm text-gray-600 mb-6 max-h-96 overflow-y-auto">
                <p class="mb-4">Dengan menggunakan SomniaExpert, Anda menyetujui syarat dan ketentuan berikut:</p>

                <h4 class="text-lg font-semibold mb-2 color-text-dark">1. Penggunaan Layanan</h4>
                <ul class="list-disc pl-5 mb-4">
                    <li>Layanan ini ditujukan untuk usia 18 tahun ke atas</li>
                    <li>Diagnosis bersifat preliminer dan tidak menggantikan konsultasi medis langsung</li>
                    <li>Pengguna wajib memberikan informasi yang akurat</li>
                </ul>

                <h4 class="text-lg font-semibold mb-2 color-text-dark">2. Batasan Tanggung Jawab</h4>
                <ul class="list-disc pl-5 mb-4">
                    <li>Hasil diagnosis bersifat rekomendasi, bukan diagnosis final</li>
                    <li>Konsultasi dengan profesional medis tetap disarankan</li>
                    <li>Kami tidak bertanggung jawab atas keputusan medis yang diambil pengguna</li>
                </ul>

                <h4 class="text-lg font-semibold mb-2 color-text-dark">3. Akun Pengguna</h4>
                <ul class="list-disc pl-5 mb-4">
                    <li>Pengguna bertanggung jawab atas keamanan akun</li>
                    <li>Informasi akun harus dijaga kerahasiaannya</li>
                    <li>Satu akun untuk satu pengguna</li>
                </ul>

                <h4 class="text-lg font-semibold mb-2 color-text-dark">4. Perubahan Ketentuan</h4>
                <p>Kami berhak mengubah syarat dan ketentuan dengan pemberitahuan sebelumnya kepada pengguna.</p>
            </div>
            <div class="flex justify-end">
                <button onclick="closeTermsModal()" class="py-2 px-6 bg-gray-200 text-gray-800 font-semibold rounded-lg hover:bg-gray-300 transition duration-300">Tutup</button>
            </div>
        </div>
    </div>

    <!-- Modal Pesan (Menggantikan alert()) -->
    <div id="modal-message" class="fixed inset-0 bg-gray-900 bg-opacity-50 z-50 hidden flex items-center justify-center p-4">
        <div id="modal-content" class="bg-white rounded-xl shadow-2xl p-6 md:p-8 w-full max-w-md transform transition-all duration-300 scale-95 opacity-0">
            <h3 class="text-xl font-bold color-text-dark mb-4">Akses Dibutuhkan</h3>
            <p class="text-gray-600 mb-6">Untuk melanjutkan ke diagnosis, Anda perlu masuk atau mendaftar terlebih dahulu. Halaman ini hanya untuk perkenalan.</p>
            <div class="flex justify-end">
                <button onclick="closeModal()" class="py-2 px-6 bg-gray-200 text-gray-800 font-semibold rounded-lg hover:bg-gray-300 transition duration-300">Tutup</button>
            </div>
        </div>
    </div>

    <!-- 1. HEADER / NAVIGATION BAR (Sticky) -->
    <header class="sticky top-0 z-40 bg-white shadow-md transition-colors duration-500">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex justify-between items-center">
            <!-- Logo -->
            <div class="flex items-center space-x-2">
                <img src="{{ url('dist/assets/images/logo-sm.png') }}" alt="SomniaExpert" class="h-8">
                <span class="text-2xl font-extrabold color-text-dark">SomniaExpert</span>
            </div>

            <!-- Tombol Login/Daftar atau User Menu -->
            <nav class="flex items-center space-x-4">
                @auth
                    <span class="text-gray-700 font-medium">Hai, {{ \App\Helpers\Helper::getFirstName(Auth::user()->nama) }}</span>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="py-2 px-5 color-accent text-white font-semibold rounded-lg shadow-md hover:bg-blue-700 transition duration-300 transform hover:scale-105">
                            Logout
                        </button>
                    </form>
                @else
                    <a href="{{ route('register') }}" class="text-gray-600 hover:text-gray-900 font-medium transition duration-300">Daftar</a>
                    <a href="{{ route('login') }}" class="py-2 px-5 color-accent text-white font-semibold rounded-lg shadow-md hover:bg-blue-700 transition duration-300 transform hover:scale-105">Login</a>
                @endauth
            </nav>
        </div>
    </header>

    <main>
        <!-- 2. HERO SECTION (Fokus Utama) -->
        <section class="bg-blue-50 pt-20 pb-24 md:pt-32 md:pb-40 transition-colors duration-500">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid md:grid-cols-2 gap-12 items-center">
                <!-- Konten Kiri (Teks & CTA) -->
                <div class="text-center md:text-left">
                    <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold leading-tight color-text-dark mb-6">
                        Akhiri <span class="text-blue-500">Gangguan Tidur</span> Anda. Solusi Personal, Diagnosis Akurat.
                    </h1>
                    <p class="text-lg text-gray-700 mb-10 max-w-xl mx-auto md:mx-0">
                        Sistem pakar berbasis data klinis yang dirancang untuk mendiagnosa jenis gangguan tidur spesifik pada orang dewasa dan merekomendasikan langkah perbaikan yang terpersonalisasi. Sistem ini menggunakan kombinasi metode VCIRS (Variable-Centered Intelligent Rule System) dan Certainty Factor untuk memberikan hasil diagnosis yang lebih akurat dan terpercaya.
                    </p>

                    <!-- CTA Primer -->
                    <a href="{{ route('login') }}" class="inline-block py-4 px-10 text-xl color-accent text-white font-bold rounded-xl shadow-lg hover:shadow-xl hover:bg-blue-700 transition duration-300 transform hover:scale-105 focus:outline-none focus:ring-4 focus:ring-blue-500 focus:ring-opacity-50">
                        Mulai Diagnosis Sekarang &rarr;
                    </a>
                </div>

                <!-- Konten Kanan (Visual) -->
                <div class="hidden md:block">
                    <!-- Placeholder Ilustrasi/Gambar -->
                    <div class="bg-white p-6 rounded-3xl shadow-2xl shadow-blue-200/50 transition-colors duration-500">
                        <svg class="w-full h-auto text-blue-400" viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="xMidYMid meet">
                            <!-- Placeholder untuk Ilustrasi Kualitas Tidur / Grafis Data -->
                            <rect x="10" y="25" width="80" height="50" rx="5" fill="#f0f9ff"/>
                            <circle cx="50" cy="50" r="15" fill="#3b82f6"/>
                            <path d="M50 35 L55 45 L65 40 M50 35 L45 45 L35 40" stroke="#ffffff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M50 65 L60 55 L70 65 L60 75 Z" fill="#93c5fd" opacity="0.6"/>
                            <text x="50" y="90" font-family="Inter" font-size="6" text-anchor="middle" fill="#1a4f78">Analisis Kualitas Tidur</text>
                        </svg>
                    </div>
                </div>
            </div>
        </section>

        <!-- 3. TRUST & KEUNGGULAN (Fokus pada Kredibilitas) -->
        <section class="py-20 md:py-24 bg-white transition-colors duration-500">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <h2 class="text-3xl md:text-4xl font-bold text-center color-text-dark mb-16">Mengapa Memilih SomniaExpert?</h2>

                <div class="grid md:grid-cols-3 gap-8">
                    <!-- Keunggulan 1: Akurasi -->
                    <div class="text-center p-6 bg-white rounded-xl shadow-lg border border-gray-100 hover:shadow-xl transition duration-300">
                        <div class="w-16 h-16 color-accent text-white rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.504A9.954 9.954 0 0112 21c-3.6 0-6.6-1.5-9-4.5"></path></svg>
                        </div>
                        <h3 class="text-xl font-semibold color-text-dark mb-2">Diagnosis Berbasis Pakar</h3>
                        <p class="text-gray-600">Menggunakan metode VCIRS dan Certainty Factor yang dikembangkan bersama spesialis tidur dan didukung referensi medis terbaru untuk hasil diagnosis yang lebih presisi.</p>
                    </div>

                    <!-- Keunggulan 2: Personalisasi -->
                    <div class="text-center p-6 bg-white rounded-xl shadow-lg border border-gray-100 hover:shadow-xl transition duration-300">
                        <div class="w-16 h-16 color-accent text-white rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        </div>
                        <h3 class="text-xl font-semibold color-text-dark mb-2">Rekomendasi Khusus</h3>
                        <p class="text-gray-600">Solusi tidur yang unik dan disesuaikan dengan profil kesehatan, gaya hidup, dan hasil diagnosis Anda.</p>
                    </div>

                    <!-- Keunggulan 3: Privasi -->
                    <div class="text-center p-6 bg-white rounded-xl shadow-lg border border-gray-100 hover:shadow-xl transition duration-300">
                        <div class="w-16 h-16 color-accent text-white rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                        </div>
                        <h3 class="text-xl font-semibold color-text-dark mb-2">Keamanan Data Terjamin</h3>
                        <p class="text-gray-600">Kami menjamin kerahasiaan dan keamanan penuh data medis serta riwayat diagnosis Anda.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- 4. CARA KERJA (Simple Steps) -->
        <section class="py-20 md:py-24 bg-blue-50 transition-colors duration-500">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <h2 class="text-3xl md:text-4xl font-bold text-center color-text-dark mb-16">Bagaimana Kami Membantu Anda?</h2>

                <div class="flex flex-col md:flex-row justify-center space-y-10 md:space-y-0 md:space-x-12">
                    <!-- Langkah 1 -->
                    <div class="text-center max-w-xs mx-auto md:mx-0">
                        <div class="w-12 h-12 bg-blue-500 text-white font-bold text-2xl rounded-full flex items-center justify-center mx-auto mb-4 border-4 border-white shadow-md">1</div>
                        <h3 class="text-xl font-semibold color-text-dark mb-2">Jawab Kuesioner</h3>
                        <p class="text-gray-700">Lengkapi kuesioner mendalam tentang pola dan riwayat tidur Anda. (Membutuhkan Login)</p>
                    </div>

                    <!-- Arrow -->
                    <div class="hidden md:flex items-center justify-center">
                        <svg class="w-12 h-12 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                    </div>

                    <!-- Langkah 2 -->
                    <div class="text-center max-w-xs mx-auto md:mx-0">
                        <div class="w-12 h-12 bg-blue-500 text-white font-bold text-2xl rounded-full flex items-center justify-center mx-auto mb-4 border-4 border-white shadow-md">2</div>
                        <h3 class="text-xl font-semibold color-text-dark mb-2">Analisis Sistem Pakar</h3>
                        <p class="text-gray-700">Sistem kami memproses jawaban Anda dan mengidentifikasi potensi gangguan tidur Anda.</p>
                    </div>

                    <!-- Arrow -->
                    <div class="hidden md:flex items-center justify-center">
                        <svg class="w-12 h-12 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                    </div>

                    <!-- Langkah 3 -->
                    <div class="text-center max-w-xs mx-auto md:mx-0">
                        <div class="w-12 h-12 bg-blue-500 text-white font-bold text-2xl rounded-full flex items-center justify-center mx-auto mb-4 border-4 border-white shadow-md">3</div>
                        <h3 class="text-xl font-semibold color-text-dark mb-2">Terima Solusi</h3>
                        <p class="text-gray-700">Dapatkan hasil diagnosis terperinci dan rekomendasi praktis untuk perbaikan tidur.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- 5. FINAL CTA BLOCK (Mendorong Login Ulang) -->
        <section class="py-20 md:py-24 bg-white transition-colors duration-500">
            <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 text-center bg-blue-50 rounded-2xl p-8 md:p-12 shadow-inner transition-colors duration-500">
                <h2 class="text-3xl font-bold color-text-dark mb-4">Siap Tidur Lebih Nyenyak?</h2>
                <p class="text-lg text-gray-600 mb-8">Ribuan orang telah mengambil langkah pertama. Jangan biarkan masalah tidur mengganggu kualitas hidup Anda lebih lama lagi.</p>

                <!-- Final CTA (Warna Biru Tua Primary) -->
                <a href="{{ route('register') }}" class="inline-block py-4 px-10 text-xl color-primary text-white font-bold rounded-xl shadow-lg hover:shadow-xl hover:bg-blue-900 transition duration-300 transform hover:scale-105 focus:outline-none focus:ring-4 focus:ring-blue-500 focus:ring-opacity-50">
                    Daftar dan Dapatkan Diagnosis Anda &rarr;
                </a>
            </div>
        </section>

    </main>

    <!-- 6. FOOTER -->
    <footer class="color-primary pt-12 pb-6 text-white transition-colors duration-500">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 border-t border-white border-opacity-20 pt-6 flex flex-col md:flex-row justify-between items-center">
            <p class="text-sm text-center md:text-left mb-4 md:mb-0">&copy; 2025 SomniaExpert. Semua Hak Dilindungi.</p>
            <div class="flex space-x-6 text-sm">
                <button onclick="showPrivacyModal()" class="hover:text-blue-300 transition duration-300">Kebijakan Privasi</button>
                <button onclick="showTermsModal()" class="hover:text-blue-300 transition duration-300">Syarat & Ketentuan</button>
            </div>
        </div>
    </footer>

    <script>
        // Fungsi untuk modal umum
        function showModal() {
            const modal = document.getElementById('modal-message');
            const content = document.getElementById('modal-content');
            modal.classList.remove('hidden');
            setTimeout(() => {
                content.classList.remove('scale-95', 'opacity-0');
                content.classList.add('scale-100', 'opacity-100');
            }, 10);
        }

        function closeModal() {
            const modal = document.getElementById('modal-message');
            const content = document.getElementById('modal-content');
            content.classList.remove('scale-100', 'opacity-100');
            content.classList.add('scale-95', 'opacity-0');
            setTimeout(() => {
                modal.classList.add('hidden');
            }, 300);
        }

        // Fungsi untuk modal Kebijakan Privasi
        function showPrivacyModal() {
            const modal = document.getElementById('privacy-modal');
            const content = modal.querySelector('div');
            modal.classList.remove('hidden');
            setTimeout(() => {
                content.classList.remove('scale-95', 'opacity-0');
                content.classList.add('scale-100', 'opacity-100');
            }, 10);
        }

        function closePrivacyModal() {
            const modal = document.getElementById('privacy-modal');
            const content = modal.querySelector('div');
            content.classList.remove('scale-100', 'opacity-100');
            content.classList.add('scale-95', 'opacity-0');
            setTimeout(() => {
                modal.classList.add('hidden');
            }, 300);
        }

        // Fungsi untuk modal Syarat & Ketentuan
        function showTermsModal() {
            const modal = document.getElementById('terms-modal');
            const content = modal.querySelector('div');
            modal.classList.remove('hidden');
            setTimeout(() => {
                content.classList.remove('scale-95', 'opacity-0');
                content.classList.add('scale-100', 'opacity-100');
            }, 10);
        }

        function closeTermsModal() {
            const modal = document.getElementById('terms-modal');
            const content = modal.querySelector('div');
            content.classList.remove('scale-100', 'opacity-100');
            content.classList.add('scale-95', 'opacity-0');
            setTimeout(() => {
                modal.classList.add('hidden');
            }, 300);
        }
    </script>
</body>
</html>
