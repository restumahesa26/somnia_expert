<?php

namespace App\Http\Controllers;

use App\Models\Gangguan;
use Illuminate\Http\Request;

class GangguanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $items = Gangguan::all();
        return view('pages.gangguan.index', compact('items'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.gangguan.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'kode_gangguan' => 'required|string|max:10|unique:gangguan,kode_gangguan',
            'nama_gangguan' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'solusi' => 'required|string'
        ]);

        Gangguan::create($request->all());

        return redirect()
            ->route('gangguan.index')
            ->with('success', 'Data gangguan berhasil ditambahkan');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $item = Gangguan::findOrFail($id);
        return view('pages.gangguan.edit', compact('item'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'kode_gangguan' => 'required|string|max:10|unique:gangguan,kode_gangguan,'.$id,
            'nama_gangguan' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'solusi' => 'required|string',
        ]);

        $item = Gangguan::findOrFail($id);
        $item->update($request->all());

        return redirect()
            ->route('gangguan.index')
            ->with('success', 'Data gangguan berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $item = Gangguan::findOrFail($id);
        $item->delete();

        return redirect()
            ->route('gangguan.index')
            ->with('success', 'Data gangguan berhasil dihapus');
    }
}
