<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class PenggunaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::withCount('konsultasis')->orderByDesc('id')->get();

        return view('pages.pengguna.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = new User();
        return view('pages.pengguna.create', compact('user'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama'     => ['required','string','max:255'],
            'username' => ['required','string','max:50','unique:users,username'],
            'email'    => ['required','email','max:255','unique:users,email'],
            'is_admin' => ['nullable','boolean'],
            'password' => ['required','string','min:8','confirmed'],
            'umur' => ['required', 'integer', 'min:0'],
            'jenis_kelamin' => ['required', 'string', 'in:L,P'],
        ]);

        $validated['is_admin'] = (bool) ($validated['is_admin'] ?? false);
        $validated['password'] = Hash::make($validated['password']);

        User::create($validated);

        return redirect()
            ->route('pengguna.index')
            ->with('success', 'Pengguna berhasil ditambahkan');
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
        $user = User::findOrFail($id);

        return view('pages.pengguna.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'nama'     => ['required','string','max:255'],
            'username' => ['required','string','max:50', Rule::unique('users','username')->ignore($user->id)],
            'email'    => ['required','email','max:255', Rule::unique('users','email')->ignore($user->id)],
            'is_admin' => ['nullable','boolean'],
            'password' => ['nullable','string','min:8','confirmed'],
            'umur' => ['required', 'integer', 'min:0'],
            'jenis_kelamin' => ['required', 'string', 'in:L,P'],
        ]);

        $data = collect($validated)->except('password')->toArray();
        $data['is_admin'] = (bool) ($data['is_admin'] ?? false);

        if (!empty($validated['password'])) {
            $data['password'] = Hash::make($validated['password']);
        }

        $user->update($data);

        return redirect()
            ->route('pengguna.index')
            ->with('success', 'Pengguna berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::findOrFail($id);

        $user->delete();
        return redirect()
            ->route('pengguna.index')
            ->with('success', 'Pengguna berhasil dihapus');
    }
}
