<?php

namespace App\Http\Controllers;

use App\Models\Gejala;
use App\Models\Konsultasi;
use App\Services\VCIRS;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class DiagnosaController extends Controller
{
    // =========================================================================
    // BAGIAN 1: API UNTUK DYNAMIC QUESTIONNAIRE (3-PHASE HYBRID LOGIC)
    // =========================================================================

    /**
     * FASE 1: Titik Mulai (G5)
     */
    public function ajaxStart()
    {
        // Reset sesi diagnosa
        session()->forget(['diagnosa_temp_answers', 'diagnosa_queue', 'diagnosa_history']);

        // Titik Mulai G5
        $gejalaAwal = DB::table('gejala')
            ->where('kode_gejala', 'G05') // Titik Mulai: G5
            ->select('id', 'kode_gejala', 'nama_gejala')
            ->get();

        return response()->json([
            'status' => 'next',
            'gejala' => $gejalaAwal,
            'message' => 'Fase 1: Pertanyaan Awal. Silakan jawab pertanyaan berikut.',
            'can_go_back' => false,
            'current_answers' => [],
        ]);
    }

    public function ajaxPrev(Request $request)
    {
        $currentAnswers = session('diagnosa_temp_answers', []);
        $history = session('diagnosa_history', []);

        if (! empty($history)) {
            $lastBatch = array_pop($history);
            foreach ($lastBatch as $id) {
                unset($currentAnswers[$id]);
            }
            session(['diagnosa_history' => $history]);
            session(['diagnosa_temp_answers' => $currentAnswers]);
        }

        return $this->processNextQuestions($currentAnswers, $history);
    }

    /**
     * FASE 2: Percabangan (Decision Tree Processing)
     */
    public function ajaxNext(Request $request)
    {
        // 1. Simpan jawaban baru ke sesi
        $jawabanBaru = $request->input('jawaban', []);
        $currentAnswers = session('diagnosa_temp_answers', []);
        $history = session('diagnosa_history', []);

        if (! empty($jawabanBaru)) {
            $history[] = array_keys($jawabanBaru);
            session(['diagnosa_history' => $history]);
        }

        $allAnswers = $currentAnswers + $jawabanBaru; // Merge jawaban
        session(['diagnosa_temp_answers' => $allAnswers]);

        return $this->processNextQuestions($allAnswers, $history);
    }

    private function processNextQuestions($allAnswers, $history)
    {
        // Build map of kode_gejala => answer (0 or 1)
        $gejalaMap = DB::table('gejala')->pluck('kode_gejala', 'id')->toArray();
        $answeredKodes = [];
        foreach ($allAnswers as $id => $val) {
            if (isset($gejalaMap[$id])) {
                $answeredKodes[$gejalaMap[$id]] = (int) $val;
            }
        }

        // Cari antrian gejala yang harus ditanyakan
        $toAskKodes = $this->determineNextQuestions($answeredKodes);

        if (empty($toAskKodes)) {
            session()->forget('diagnosa_queue');

            return response()->json([
                'status' => 'finish',
                'current_answers' => $allAnswers,
            ]);
        }

        // Convert kodes kembali ke array IDs
        $kodeToId = array_flip($gejalaMap);
        $nextIds = [];
        foreach ($toAskKodes as $kode) {
            if (isset($kodeToId[$kode])) {
                $nextIds[] = $kodeToId[$kode];
            }
        }

        // Ambil data gejala dari MySQL sesuai urutan path yang ditentukan ($nextIds)
        $questions = DB::table('gejala')
            ->whereIn('id', $nextIds)
            ->get(['id', 'kode_gejala', 'nama_gejala']);

        // Sort di PHP agar urutan akurat sesuai jalur decision tree ($toAskKodes)
        $questions = $questions->sortBy(function ($model) use ($toAskKodes) {
            return array_search($model->kode_gejala, $toAskKodes);
        })->values();

        return response()->json([
            'status' => 'next',
            'gejala' => $questions,
            'message' => 'Silakan jawab pertanyaan berikut untuk melanjutkan analisa.',
            'can_go_back' => ! empty($history),
            'current_answers' => $allAnswers,
        ]);
    }

    /**
     * Engine Rule Penentu Pertanyaan Berikutnya Beralaskan Pohon Keputusan
     */
    private function determineNextQuestions($answeredKodes)
    {
        // Titik Mulai: G5
        if (! isset($answeredKodes['G05'])) {
            return ['G05'];
        }

        // Jika G5 dijawab Ya, masuk ke cabang kiri
        if ($answeredKodes['G05'] == 1) {

            // Menuju G6 (tampilkan satu per satu)
            if (! isset($answeredKodes['G06'])) {
                return ['G06'];
            }

            // G6 lanjut ke G11
            if (! isset($answeredKodes['G11'])) {
                return ['G11'];
            }

            // G11 sudah terjawab
            if ($answeredKodes['G11'] == 1) { // Cabang Kiri (IYA) menuju G22
                if (! isset($answeredKodes['G22'])) {
                    return ['G22'];
                }

                if ($answeredKodes['G22'] == 1) { // Cabang Kiri dari G22 (IYA) -> P2
                    $pathP2 = ['G12', 'G13', 'G14', 'G15', 'G17', 'G18', 'G19', 'G20', 'G21', 'G02', 'G16'];

                    return $this->getUnansweredInPath($pathP2, $answeredKodes);
                } else { // Cabang Kanan dari G22 (Tidak) -> P3
                    $pathP3 = ['G08', 'G23', 'G24', 'G25', 'G26', 'G27', 'G28', 'G29', 'G30', 'G31', 'G32', 'G33', 'G34'];

                    return $this->getUnansweredInPath($pathP3, $answeredKodes);
                }
            } else { // Cabang Kanan dari G11 (Tidak) -> P1
                $pathP1 = ['G01', 'G02', 'G03', 'G04', 'G07', 'G08', 'G09', 'G10'];

                return $this->getUnansweredInPath($pathP1, $answeredKodes);
            }

        } else { // Jika G5 dijawab Tidak, masuk ke cabang kanan menuju G16

            if (! isset($answeredKodes['G16'])) {
                return ['G16'];
            }

            if ($answeredKodes['G16'] == 1) { // Cabang Kiri dari G16 (IYA) -> P5
                $pathP5 = ['G42', 'G43', 'G44', 'G45', 'G46', 'G47', 'G06'];

                return $this->getUnansweredInPath($pathP5, $answeredKodes);
            } else { // Cabang Kanan dari G16 (Tidak) -> P4
                $pathP4 = ['G11', 'G35', 'G36', 'G37', 'G38', 'G39', 'G40', 'G41'];

                return $this->getUnansweredInPath($pathP4, $answeredKodes);
            }
        }
    }

    private function getUnansweredInPath($path, $answeredKodes)
    {
        $toAsk = [];
        foreach ($path as $kode) {
            if (! isset($answeredKodes[$kode])) {
                $toAsk[] = $kode;
            }
        }

        return $toAsk;
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
            'gejala' => 'nullable|array',
            'gejala.*' => 'integer',
        ]);

        $ids = collect($request->input('gejala', []))->map(fn ($v) => (int) $v)->unique()->values()->all();

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
            'hasil' => $sortedHasil->map(function ($row) {
                return [
                    'penyakit_id' => $row['penyakit']->id,
                    'kode' => $row['penyakit']->kode_penyakit,
                    'nama' => $row['penyakit']->nama_penyakit,
                    'percent' => $row['percent'],
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
        if (auth()->check() && ! auth()->user()->is_admin) {
            $query->where('user_id', auth()->id());
        }
        $riwayat = $query->latest()->get();

        return view('pages.diagnosa.riwayat', compact('riwayat'));
    }

    public function show($id)
    {
        $konsultasi = Konsultasi::findOrFail($id);
        Gate::authorize('view', $konsultasi);

        $vcirs = new VCIRS;
        $hasil = $vcirs->diagnose($konsultasi->gejala_terpilih);
        $sortedHasil = collect($hasil)->sortByDesc('percent');

        return view('pages.diagnosa.hasil', [
            'konsultasi' => $konsultasi,
            'hasil' => $hasil,
            'sortedHasil' => $sortedHasil,
            'gejalas' => Gejala::all(),
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
