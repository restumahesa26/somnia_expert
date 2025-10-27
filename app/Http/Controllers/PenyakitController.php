<?php

namespace App\Http\Controllers;

use App\Models\Penyakit;
use Illuminate\Http\Request;

class PenyakitController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $items = Penyakit::all();
        return view('pages.penyakit.index', compact('items'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.penyakit.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'kode_penyakit' => 'required|string|max:10|unique:penyakit,kode_penyakit',
            'nama_penyakit' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'solusi' => 'required|string'
        ]);

        Penyakit::create($request->all());

        return redirect()
            ->route('penyakit.index')
            ->with('success', 'Data penyakit berhasil ditambahkan');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $item = Penyakit::findOrFail($id);
        return view('pages.penyakit.edit', compact('item'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'kode_penyakit' => 'required|string|max:10|unique:penyakit,kode_penyakit,'.$id,
            'nama_penyakit' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'solusi' => 'required|string',
        ]);

        $item = Penyakit::findOrFail($id);
        $item->update($request->all());

        return redirect()
            ->route('penyakit.index')
            ->with('success', 'Data penyakit berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $item = Penyakit::findOrFail($id);
        $item->delete();

        return redirect()
            ->route('penyakit.index')
            ->with('success', 'Data penyakit berhasil dihapus');
    }
}
