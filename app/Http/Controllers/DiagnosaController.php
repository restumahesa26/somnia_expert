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

        // 3. Jika BELUM, berarti ini baru selesai Fase I. Kita harus tentukan Skenario.
        // Logika Penentuan Skenario
        
        // Ambil definisi "Trigger" (Gejala Kunci)
        $triggers = DB::table('penyakit_gejala')
            ->select('penyakit_id', 'gejala_id')
            ->where('is_kunci', true) // UPDATE: Pakai is_kunci
            ->get()
            ->groupBy('penyakit_id');

        $detectedPenyakitIds = [];

        foreach ($triggers as $penyakitId => $items) {
            // Logic: Penyakit dianggap 'suspect' jika SEMUA atau SEBAGIAN BESAR key symptoms terpenuhi?
            // Biasanya cukup 1 atau beberapa. Mari kita pakai threshold minimal 1 key symptom = suspect.
            // Atau lebih ketat: minimal 50% dari key symptoms? 
            // Untuk sensitivitas tinggi (screening), minimal 1 "Ya" sudah cukup memicu investigasi lanjut.
            foreach ($items as $item) {
                if (isset($allAnswers[$item->gejala_id]) && $allAnswers[$item->gejala_id] == 1) {
                    $detectedPenyakitIds[] = $penyakitId;
                    break; 
                }
            }
        }
        
        $detectedPenyakitIds = array_unique($detectedPenyakitIds);
        $countDetected = count($detectedPenyakitIds);
        
        $queueIds = [];
        $message = '';

        // Skenario 1: DIAGNOSIS SPESIFIK (Scenario A)
        // Hanya 1 penyakit terdeteksi. Only ask specific symptoms defined for Phase 2.
        if ($countDetected == 1) {
            $penyakitId = $detectedPenyakitIds[0];
            $namaPenyakit = Penyakit::find($penyakitId)->nama_penyakit;
            $message = "Terindikasi " . $namaPenyakit . ". Melakukan konfirmasi detail.";

            // Ambil SEMUA gejala pendukung (apapun bobotnya) TAPI bukan kunci (karena kunci sudah ditanya)
            $queueIds = DB::table('penyakit_gejala')
                ->where('penyakit_id', $penyakitId)
                // ->where('bobot', '>=', 0.80) // Constraint dihapus agar gejala bobot kecil tetap muncul
                ->where('is_kunci', false) 
                ->pluck('gejala_id')
                ->toArray();
        } 
        // Skenario 2: KOMORBIDITAS (Scenario B)
        // Lebih dari 1 penyakit terdeteksi.
        elseif ($countDetected > 1) {
            $message = "Terdeteksi indikasi kompleks (Komorbiditas). Melakukan pemeriksaan menyeluruh.";

            // 1. Ambil SEMUA gejala pendukung dari penyakit-penyakit yang terdeteksi
            $specificIds = DB::table('penyakit_gejala')
                ->whereIn('penyakit_id', $detectedPenyakitIds)
                // ->where('bobot', '>=', 0.80) // Constraint dihapus
                ->where('is_kunci', false)
                ->pluck('gejala_id')
                ->toArray();

            // 2. Ambil gejala TUMPANG TINDIH / CROSS-CHECK (0.80 - 0.89) untuk validasi silang
            $overlapIds = DB::table('penyakit_gejala')
                ->whereBetween('bobot', [0.80, 0.89]) 
                ->pluck('gejala_id')
                ->toArray();

            $queueIds = array_unique(array_merge($specificIds, $overlapIds));
        }
        // Skenario 3: SEHAT / DIAGNOSA NEGATIF (Scenario C)
        // Tidak ada trigger yang terpenuhi.
        else {
            $message = "Tidak ada indikasi gejala berat. Melakukan pemeriksaan gejala umum.";

            // Tampilkan gejala umum/overlap (0.80 - 0.89) untuk memastikan
            $queueIds = DB::table('penyakit_gejala')
                ->whereBetween('bobot', [0.80, 0.89])
                ->pluck('gejala_id')
                ->unique()
                ->toArray();
        }

        // Filter: Hapus ID yang sudah dijawab (dari Phase 1)
        $unansweredQueue = array_diff($queueIds, array_keys($allAnswers));
        
        // Simpan antrian ke sesi agar konsisten
        session(['diagnosa_queue' => array_values($unansweredQueue)]);
        
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
