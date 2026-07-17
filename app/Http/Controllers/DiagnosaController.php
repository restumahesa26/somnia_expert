<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Gejala;
use App\Models\Penyakit;
use App\Models\Konsultasi;
use App\Services\VCIRS;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\DB;

class DiagnosaController extends Controller
{
    // =========================================================================
    // BAGIAN 1: API UNTUK DYNAMIC QUESTIONNAIRE (3-PHASE HYBRID LOGIC)
    // =========================================================================

    /**
     * FASE I: Screening / Trigger Phase
     * Menanyakan gejala yang ditandai sebagai Gejala Kunci (is_kunci = true).
     */
    public function ajaxStart()
    {
        // Reset sesi diagnosa
        session()->forget(['diagnosa_temp_answers', 'diagnosa_queue']);

        // 1. Ambil Gejala Kunci (Berdasarkan flag is_kunci di DB)
        // Logic baru: Tidak lagi hardcode bobot >= 0.95, tapi ikut seeder
        $gejalaAwal = DB::table('penyakit_gejala')
            ->join('gejala', 'penyakit_gejala.gejala_id', '=', 'gejala.id')
            ->where('penyakit_gejala.is_kunci', true) 
            ->select('gejala.id', 'gejala.kode_gejala', 'gejala.nama_gejala')
            ->distinct()
            ->orderBy('gejala.kode_gejala')
            ->get();
        
        return response()->json([
            'status' => 'next',
            'gejala' => $gejalaAwal,
            'message' => 'Fase I: Screening Awal. Silakan jawab pertanyaan berikut.'
        ]);
    }

    /**
     * FASE II: Konfirmasi & Percabangan (Branching Phase)
     * Menentukan Scenario A, B, atau C berdasarkan jawaban Fase I.
     */
    public function ajaxNext(Request $request)
    {
        // 1. Simpan jawaban baru ke sesi
        $jawabanBaru = $request->input('jawaban', []);
        $currentAnswers = session('diagnosa_temp_answers', []);
        $allAnswers = $currentAnswers + $jawabanBaru; // Merge jawaban
        session(['diagnosa_temp_answers' => $allAnswers]);

        // 2. Cek apakah kita sudah punya "Antrian Pertanyaan" (Queue) di sesi
        // Jika SUDAH, berarti kita sedang berada di tengah-tengah Fase II
        if (session()->has('diagnosa_queue')) {
            return $this->processQueue($allAnswers);
        }

        // 3. Jika BELUM, berarti ini baru selesai Fase I. Kita harus tentukan Prioritas Penyakit.
        // TAHAP 2: Prioritas Penyakit (Logic)
        
        // Ambil definisi "Trigger" (Gejala Kunci) dan grouping by Penyakit
        $triggers = DB::table('penyakit_gejala')
            ->select('penyakit_id', 'gejala_id')
            ->where('is_kunci', true) 
            ->get()
            ->groupBy('penyakit_id');

        $penyakitScores = [];

        // Hitung match count tiap penyakit
        foreach ($triggers as $penyakitId => $items) {
            $matchCount = 0;
            foreach ($items as $item) {
                // Cek apakah user menjawab YA (1) untuk gejala kunci ini
                if (isset($allAnswers[$item->gejala_id]) && $allAnswers[$item->gejala_id] == 1) {
                    $matchCount++;
                }
            }
            
            // Hanya masukkan penyakit yang memiliki setidaknya 1 gejala kunci terpenuhi
            if ($matchCount > 0) {
                $penyakitScores[$penyakitId] = $matchCount;
            }
        }
        
        $queueIds = [];
        $message = '';

        // Jika ada penyakit yang terdeteksi (Skor > 0)
        if (!empty($penyakitScores)) {
            // Urutkan penyakit berdasarkan score tertinggi (Desc)
            arsort($penyakitScores);
            
            // Nama penyakit prioritas utama untuk pesan
            $topPenyakitId = array_key_first($penyakitScores);
            $namaPenyakit = Penyakit::find($topPenyakitId)->nama_penyakit;
            $message = "Berdasarkan gejala awal, sistem mendeteksi indikasi ke arah $namaPenyakit dan lainnya. Melanjutkan diagnosa mendalam.";

            // TAHAP 3: Pertanyaan Lanjutan (Dinamis)
            // Ambil sisa gejala (non-key) untuk penyakit yang terdeteksi, urut sesuai prioritas penyakit
            foreach ($penyakitScores as $penyakitId => $score) {
                // Ambil semua gejala non-kunci untuk penyakit ini, urutkan by bobot agar yang terpenting ditanya duluan
                $symptoms = DB::table('penyakit_gejala')
                    ->where('penyakit_id', $penyakitId)
                    ->where('is_kunci', false)
                    ->orderBy('bobot', 'desc')
                    ->pluck('gejala_id')
                    ->toArray();
                
                // Masukkan ke antrian (array_unique nanti akan handle duplikat, keeping first occurrence)
                $queueIds = array_merge($queueIds, $symptoms);
            }
            
            // Hapus duplikat (Gejala yang sama mungkin muncul di penyakit prioritas rendah, 
            // tapi karena sudah masuk via penyakit prioritas tinggi, ia tetap di posisi atas)
            $queueIds = array_unique($queueIds);

        } else {
            // TAHAP ALTERNATIF: Tidak ada gejala kunci yg dipilih (Scenario C / Zero Match)
            // User tidak memilih satupun gejala kunci. 
            // Logic: Tampilkan gejala umum/overlap (bobot menengah) untuk antisipasi, atau stop.
            // Sesuai request: "Jangan tampilkan ... kecuali saran logic lain".
            // Kita tetap tampilkan screening umum (overlap) untuk safety.
            
            $message = "Gejala kunci tidak terdeteksi. Melakukan pemeriksaan gejala umum.";

            $queueIds = DB::table('penyakit_gejala')
                ->whereBetween('bobot', [0.80, 0.89]) // Gejala overlap / umum
                ->pluck('gejala_id')
                ->unique()
                ->toArray();
        }

        // Filter: Hapus ID yang sudah dijawab (dari Phase 1)
        // Note: array_values untuk re-index 0,1,2...
        $unansweredQueue = array_values(array_diff($queueIds, array_keys($allAnswers)));
        
        // Simpan antrian ke sesi
        session(['diagnosa_queue' => $unansweredQueue]);
        
        // Panggil fungsi processing queue
        return $this->processQueue($allAnswers, $message);
    }

    /**
     * Memproses antrian pertanyaan dan mengirim batch berikutnya ke User.
     */
    private function processQueue($allAnswers, $customMessage = null)
    {
        $queue = session('diagnosa_queue', []);

        // Filter ulang (just in case)
        $queue = array_values(array_diff($queue, array_keys($allAnswers)));
        session(['diagnosa_queue' => $queue]); // Update sesi

        if (empty($queue)) {
            return response()->json(['status' => 'finish']);
        }

        // Ambil batch pertanyaan (misal 5 atau 10 sekaligus)
        $nextBatchIds = array_slice($queue, 0, 10);
        
        $questions = Gejala::whereIn('id', $nextBatchIds)
            ->orderBy('kode_gejala') // Urutkan supaya rapi
            ->get(['id', 'kode_gejala', 'nama_gejala']);

        return response()->json([
            'status' => 'next',
            'gejala' => $questions,
            'message' => $customMessage ?? 'Silakan lengkapi pertanyaan detail berikut.'
        ]);
    }

    // =========================================================================
    // BAGIAN 2: VIEW DAN FINAL PROCESSING
    // =========================================================================

    public function form()
    {
        $isAdmin = auth()->user() && auth()->user()->is_admin;
        return view('pages.diagnosa.form', compact('isAdmin'));
    }

    public function proses(Request $request, VCIRS $vcirs)
    {
        $request->validate([
            'gejala' => 'required|array',
            'gejala.*' => 'integer',
        ]);

        $ids = collect($request->input('gejala', []))->map(fn($v)=>(int)$v)->unique()->values()->all();

        // Hitung VCIRS
        $hasil = $vcirs->diagnose($ids);
        
        // Urutkan
        $sortedHasil = collect($hasil)->sortByDesc('percent');

        // Note: Untuk Scenario C (Sehat), biasanya percent akan sangat kecil atau 0.
        // Kita tetap simpan hasilnya.

        $konsultasiData = [
            'user_id' => auth()->id(),
            'gejala_terpilih' => $ids,
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

        if ($konsultasiData['is_admin_input']) {
            $konsultasiData['nama_pasien'] = $request->input('nama_pasien');
            $konsultasiData['umur'] = $request->input('umur');
            $konsultasiData['jenis_kelamin'] = $request->input('jenis_kelamin');
        }

        $k = Konsultasi::create($konsultasiData);
        session()->forget(['diagnosa_temp_answers', 'diagnosa_queue']);

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
