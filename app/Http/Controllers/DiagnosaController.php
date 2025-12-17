<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Gejala;
use App\Models\Penyakit;
use App\Models\Konsultasi;
use App\Services\VCIRS; // Pastikan service ini ada
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\DB;

class DiagnosaController extends Controller
{
    // =========================================================================
    // BAGIAN 1: API UNTUK DYNAMIC QUESTIONNAIRE (AJAX)
    // =========================================================================

    /**
     * Memulai sesi diagnosa.
     * Mengembalikan pertanyaan awal (Gejala Kunci).
     */
    public function ajaxStart()
    {
        session()->forget(['diagnosa_temp_answers', 'diagnosa_locked_path']);

        // 1. Ambil Gejala Kunci (Inisiasi Fase I)
        // Sesuai request: Ambil semua gejala yang bobotnya >= 0.85 (is_kunci = true)
        // Logic ini otomatis mengambil data dari tabel penyakit_gejala yang sudah di-seed.

        $gejalaAwal = DB::table('penyakit_gejala')
            ->join('gejala', 'penyakit_gejala.gejala_id', '=', 'gejala.id')
            ->where('penyakit_gejala.is_kunci', true) // Ini filter bobot >= 0.85
            ->select('gejala.id', 'gejala.kode_gejala', 'gejala.nama_gejala')
            ->distinct() // Menghindari duplikat jika 1 gejala kunci dipakai 2 penyakit
            ->orderBy('gejala.kode_gejala')
            ->get();

        return response()->json([
            'status' => 'next',
            'gejala' => $gejalaAwal,
            'message' => 'Silakan jawab pertanyaan screening awal berikut.'
        ]);
    }

    public function ajaxNext(Request $request)
    {
        $jawabanBaru = $request->input('jawaban', []);
        $currentAnswers = session('diagnosa_temp_answers', []);
        $allAnswers = $currentAnswers + $jawabanBaru;
        session(['diagnosa_temp_answers' => $allAnswers]);

        // ============================================================
        // 1. AMBIL RULES DARI DB (DINAMIS)
        // ============================================================
        $rulesRaw = DB::table('penyakit_gejala')
            ->join('penyakit', 'penyakit_gejala.penyakit_id', '=', 'penyakit.id')
            ->join('gejala', 'penyakit_gejala.gejala_id', '=', 'gejala.id')
            ->where('penyakit_gejala.is_kunci', true)
            ->select('penyakit.kode_penyakit', 'gejala.kode_gejala')
            ->get();

        $rules = $rulesRaw->mapToGroups(function ($item) {
            return [$item->kode_penyakit => $item->kode_gejala];
        })->map(function ($group) {
            return $group->toArray();
        })->toArray();

        // ============================================================
        // 2. DETEKSI SEMUA PENYAKIT (MULTI-DETECTION)
        // ============================================================

        $detectedPenyakitKodes = []; // Array untuk menampung semua penyakit yang "kena"

        foreach ($rules as $kodePenyakit => $pemicuGejalas) {
            // Ambil ID Gejala pemicu
            $idsPemicu = Gejala::whereIn('kode_gejala', $pemicuGejalas)->pluck('id')->toArray();

            // Cek apakah ada jawaban YA (1) pada salah satu pemicu
            foreach ($idsPemicu as $id) {
                if (isset($allAnswers[$id]) && $allAnswers[$id] == 1) {
                    // Jika ketemu, masukkan ke daftar terdeteksi
                    $detectedPenyakitKodes[] = $kodePenyakit;
                    break; // Lanjut ke penyakit berikutnya (tidak perlu cek pemicu lain di penyakit yg sama)
                }
            }
        }

        // Hapus duplikat (jaga-jaga)
        $detectedPenyakitKodes = array_unique($detectedPenyakitKodes);

        // ============================================================
        // 3. AKSI: AMBIL GEJALA DARI SEMUA PENYAKIT TERDETEKSI
        // ============================================================

        if (!empty($detectedPenyakitKodes)) {

            // Ambil ID Penyakit berdasarkan kode-kode yang terdeteksi
            $penyakitIds = Penyakit::whereIn('kode_penyakit', $detectedPenyakitKodes)->pluck('id');

            // Ambil SEMUA gejala dari SEMUA penyakit yang terdeteksi
            $allRelevantGejalaIds = DB::table('penyakit_gejala')
                                ->whereIn('penyakit_id', $penyakitIds)
                                ->pluck('gejala_id') // Ambil ID gejalanya saja
                                ->unique()           // Hindari duplikat (misal G11 ada di P01 dan P03)
                                ->toArray();

            // Filter: Hanya ambil yang BELUM dijawab
            // (Kita buang gejala yang ID-nya sudah ada di $allAnswers)
            $unansweredIds = array_diff($allRelevantGejalaIds, array_keys($allAnswers));

            // Jika semua pertanyaan dari penyakit-penyakit itu sudah habis terjawab -> FINISH
            if (empty($unansweredIds)) {
                return response()->json(['status' => 'finish']);
            }

            // Ambil detail pertanyaan berikutnya dari DB
            // Limit 5 pertanyaan per batch agar user tidak kaget jika banyak sekali
            $nextQuestions = Gejala::whereIn('id', $unansweredIds)
                                    ->orderBy('kode_gejala')
                                    ->take(10) // Opsional: Batasi 10 pertanyaan per halaman
                                    ->get(['id', 'kode_gejala', 'nama_gejala']);

            // Buat pesan dinamis
            $namaPenyakitStr = Penyakit::whereIn('id', $penyakitIds)->pluck('nama_penyakit')->join(', ');

            return response()->json([
                'status' => 'next',
                'gejala' => $nextQuestions,
                'message' => 'Terdeteksi indikasi: ' . $namaPenyakitStr . '. Mohon lengkapi detail berikut.'
            ]);
        }

        // Jika user menjawab TIDAK untuk semua gejala kunci awal -> FINISH
        return response()->json([
            'status' => 'finish',
            'reason' => 'No trigger met'
        ]);
    }

    // =========================================================================
    // BAGIAN 2: VIEW DAN FINAL PROCESSING (EXISTING LOGIC)
    // =========================================================================

    public function form()
    {
        // Kita tetap butuh variabel isAdmin untuk view
        $isAdmin = auth()->user() && auth()->user()->is_admin;

        // CATATAN: Kita tidak kirim $gejalas lagi karena akan diload via AJAX
        return view('pages.diagnosa.form', compact('isAdmin'));
    }

    /**
     * Proses Final (Simpan ke DB).
     * Menerima array ID gejala yang dijawab "YA" dari frontend setelah sesi tanya jawab selesai.
     */
    public function proses(Request $request, VCIRS $vcirs)
    {
        $request->validate([
            'gejala' => 'required|array', // Array ID gejala yang dijawab YA
            'gejala.*' => 'integer',
            'is_admin_input' => ['sometimes','boolean'],
            'nama_pasien'    => ['nullable','required_if:is_admin_input,true','string','max:255'],
            'umur'           => ['nullable','required_if:is_admin_input,true','integer','min:1','max:150'],
            'jenis_kelamin'  => ['nullable','required_if:is_admin_input,true','in:L,P'],
        ]);

        // Ambil ID gejala yang dipilih (User menjawab YA)
        $ids = collect($request->input('gejala', []))
            ->map(fn($v) => (int)$v)
            ->unique()
            ->values()
            ->all();

        // Panggil Service VCIRS untuk perhitungan detail (sesuai file VCIRS.php kamu)
        $hasil = $vcirs->diagnose($ids);

        // Urutkan hasil
        $sortedHasil = collect($hasil)->sortByDesc(function($row) {
            return $row['percent'];
        });

        // Siapkan data untuk disimpan
        $konsultasiData = [
            'user_id' => auth()->id(),
            'gejala_terpilih' => $ids, // Simpan ID gejala yang dijawab YA
            'is_admin_input' => auth()->check() && auth()->user()->is_admin && $request->has('is_admin_input'),
            'hasil' => $sortedHasil->map(function($row){
                return [
                    'penyakit_id' => $row['penyakit']->id,
                    'kode'        => $row['penyakit']->kode_penyakit,
                    'nama'        => $row['penyakit']->nama_penyakit,
                    'percent'     => $row['percent'],
                ];
            })->values()->all(),
        ];

        // Tambah data pasien jika input admin
        if ($konsultasiData['is_admin_input']) {
            $konsultasiData['nama_pasien'] = $request->input('nama_pasien');
            $konsultasiData['umur'] = $request->input('umur');
            $konsultasiData['jenis_kelamin'] = $request->input('jenis_kelamin');
        }

        // Simpan ke database
        $k = Konsultasi::create($konsultasiData);

        // Hapus sesi sementara
        session()->forget('diagnosa_temp_answers');

        return redirect()->route('diagnosa.show', $k->id);
    }

    public function riwayat()
    {
        $query = Konsultasi::query();

        if (auth()->check() && !auth()->user()->is_admin) {
            $query->where('user_id', auth()->id());
        }

        $riwayat = $query->latest()->get();

        return view('pages.diagnosa.riwayat', compact('riwayat'));
    }

    public function show($id)
    {
        $konsultasi = Konsultasi::findOrFail($id);
        Gate::authorize('view', $konsultasi);

        // Hitung ulang untuk tampilan detail
        $vcirs = new VCIRS();
        $hasil = $vcirs->diagnose($konsultasi->gejala_terpilih);

        $sortedHasil = collect($hasil)->sortByDesc('percent');

        return view('pages.diagnosa.hasil', [
            'konsultasi' => $konsultasi,
            'hasil' => $hasil,
            'sortedHasil' => $sortedHasil,
            'gejalas' => Gejala::all()
        ]);
    }

    public function destroy(string $id)
    {
        $konsultasi = Konsultasi::findOrFail($id);
        Gate::authorize('delete', $konsultasi);
        $konsultasi->delete();

        return redirect()->route('diagnosa.riwayat')->with('success', 'Riwayat diagnosa berhasil dihapus.');
    }
}
