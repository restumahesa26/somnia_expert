<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PenyakitGejala;
use App\Models\Penyakit;
use App\Models\Gejala;
use Illuminate\Validation\Rule;

class PenyakitGejalaController extends Controller
{
    // Tampilkan daftar (dengan filter optional)
    public function index(Request $request)
    {
        $query = PenyakitGejala::with(['penyakit','gejala'])
            ->when($request->penyakit_id, fn($q)=>$q->where('penyakit_id',$request->penyakit_id))
            ->when($request->gejala_id, fn($q)=>$q->where('gejala_id',$request->gejala_id))
            ->orderBy('penyakit_id')->orderByDesc('bobot');

        if ($request->wantsJson()) {
            return response()->json($query->paginate(20));
        }

        return view('pages.penyakit_gejala.index', [
            'items' => $query->get(),
            'penyakits' => Penyakit::orderBy('nama_penyakit')->get(),
            'gejalas' => Gejala::orderBy('nama_gejala')->get(),
        ]);
    }

    public function create()
    {
        return view('pages.penyakit_gejala.create', [
            'penyakits' => Penyakit::orderBy('nama_penyakit')->get(),
            'gejalas'   => Gejala::orderBy('nama_gejala')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'penyakit_id' => ['required','exists:penyakit,id'],
            'gejala_id'   => ['required','exists:gejala,id',
                Rule::unique('penyakit_gejala')->where(fn($q)=>$q->where('penyakit_id',$request->penyakit_id))
            ],
            'bobot'       => ['required','numeric','between:0,1.0'],
            'is_kunci'    => ['boolean'],
        ]);

        // Set default false if not present (checkbox unchecked)
        $data['is_kunci'] = $request->has('is_kunci');

        $item = PenyakitGejala::create($data);

        return $request->wantsJson()
            ? response()->json($item->load(['penyakit','gejala']), 201)
            : redirect()->route('penyakit-gejala.index')->with('success','Bobot berhasil ditambahkan');
    }

    public function edit(PenyakitGejala $penyakit_gejala) // Changed from $penyakit_gejalum
    {
        return view('pages.penyakit_gejala.edit', [
            'item' => $penyakit_gejala->load(['penyakit','gejala']),
            'penyakits' => Penyakit::orderBy('nama_penyakit')->get(),
            'gejalas'   => Gejala::orderBy('nama_gejala')->get(),
        ]);
    }

    public function update(Request $request, PenyakitGejala $penyakit_gejala) // Changed from $penyakit_gejalum
    {
        $data = $request->validate([
            'penyakit_id' => ['required','exists:penyakit,id'],
            'gejala_id'   => ['required','exists:gejala,id',
                Rule::unique('penyakit_gejala')
                    ->ignore($penyakit_gejala->id)
                    ->where(fn($q)=>$q->where('penyakit_id',$request->penyakit_id))
            ],
            'bobot'       => ['required','numeric','between:0,1.0'],
            'is_kunci'    => ['boolean'],
        ]);

        // Set default false if not present (checkbox unchecked)
        $data['is_kunci'] = $request->has('is_kunci');

        $penyakit_gejala->update($data);

        return $request->wantsJson()
            ? response()->json($penyakit_gejala->load(['penyakit','gejala']))
            : redirect()->route('penyakit-gejala.index')->with('success','Bobot berhasil diperbarui');
    }

    public function destroy(PenyakitGejala $penyakit_gejala) // Changed from $penyakit_gejalum
    {
        $penyakit_gejala->delete();

        return request()->wantsJson()
            ? response()->json(['message'=>'Deleted'])
            : redirect()->route('penyakit-gejala.index')->with('success','Bobot berhasil dihapus');
    }
}
