<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Gejala;

class GejalaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $items = Gejala::all();

        return view('pages.gejala.index', compact('items'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.gejala.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'kode_gejala' => 'required|unique:gejala,kode_gejala',
            'nama_gejala' => 'required',
        ]);

        Gejala::create($request->all());

        return redirect()->route('gejala.index')->with('success', 'Gejala created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $item = Gejala::findOrFail($id);

        return view('pages.gejala.edit', compact('item'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'kode_gejala' => 'required|unique:gejala,kode_gejala,' . $id,
            'nama_gejala' => 'required',
        ]);

        $item = Gejala::findOrFail($id);
        $item->update($request->all());

        return redirect()->route('gejala.index')->with('success', 'Gejala updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $item = Gejala::findOrFail($id);
        $item->delete();

        return redirect()->route('gejala.index')->with('success', 'Gejala deleted successfully.');
    }
}
