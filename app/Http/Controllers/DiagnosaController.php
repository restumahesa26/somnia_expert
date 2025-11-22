<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Gejala;
use App\Models\Konsultasi;
use App\Services\VCIRS;
use Illuminate\Support\Facades\Gate;

class DiagnosaController extends Controller
{
    // Form konsultasi: checklist gejala (sesuaikan UI Anda)
    public function form()
    {
        $gejalas = Gejala::orderBy('kode_gejala')->get();
        $isAdmin = auth()->user()->is_admin;
        return view('pages.diagnosa.form', compact('gejalas', 'isAdmin'));
    }

    // Proses konsultasi
    public function proses(Request $request, VCIRS $vcirs)
    {
        $request->validate([
            'gejala' => 'required|array',
            'gejala.*' => 'integer',
            'is_admin_input' => ['sometimes','boolean'],
            'nama_pasien'    => ['nullable','required_if:is_admin_input,true','string','max:255'],
            'umur'           => ['nullable','required_if:is_admin_input,true','integer','min:1','max:150'],
            'jenis_kelamin'  => ['nullable','required_if:is_admin_input,true','in:L,P'],
        ]);

        $ids = collect($request->input('gejala', []))
            ->map(fn($v) => (int)$v)
            ->filter()
            ->values()
            ->all();

        $hasil = $vcirs->diagnose($ids);

        // Sort hasil by percentage
        $sortedHasil = collect($hasil)->sortByDesc(function($row) {
            return $row['percent'];
        });

        // Prepare konsultasi data
        $konsultasiData = [
            'user_id' => auth()->id(),
            'gejala_terpilih' => $ids,
            'is_admin_input' => auth()->user()->is_admin && $request->has('is_admin_input'),
            'hasil' => $sortedHasil->map(function($row){
                return [
                    'penyakit_id' => $row['penyakit']->id,
                    'kode'        => $row['penyakit']->kode_penyakit,
                    'nama'        => $row['penyakit']->nama_penyakit,
                    'percent'     => $row['percent'],
                ];
            })->values()->all(),
        ];

        // Add patient data if admin input
        if ($konsultasiData['is_admin_input']) {
            $konsultasiData['nama_pasien'] = $request->input('nama_pasien');
            $konsultasiData['umur'] = $request->input('umur');
            $konsultasiData['jenis_kelamin'] = $request->input('jenis_kelamin');
        }

        //dd($konsultasiData);

        // Create konsultasi
        $k = Konsultasi::create($konsultasiData);

        // Redirect ke halaman show dengan ID konsultasi yang baru dibuat
        return redirect()->route('diagnosa.show', $k->id);
    }

    public function riwayat()
    {
        $query = Konsultasi::query();

        if (auth()->user()->is_admin == false) {
            $query->where('user_id', auth()->id());
        }

        $riwayat = $query->latest()->get();

        return view('pages.diagnosa.riwayat', compact('riwayat'));
    }

    public function show($id)
    {
        $konsultasi = Konsultasi::findOrFail($id);

        // Authorize the request
        Gate::authorize('view', $konsultasi);

        // Recreate diagnosis result
        $vcirs = new VCIRS();
        $hasil = $vcirs->diagnose($konsultasi->gejala_terpilih);

        // Sort hasil
        $sortedHasil = collect($hasil)->sortByDesc(function($row) {
            return $row['percent'];
        });

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

        // Authorize the request
        Gate::authorize('delete', $konsultasi);

        $konsultasi->delete();

        return redirect()->route('diagnosa.riwayat')->with('success', 'Riwayat diagnosa berhasil dihapus.');
    }
}
