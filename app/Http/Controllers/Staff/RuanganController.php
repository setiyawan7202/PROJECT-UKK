<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Ruangan;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class RuanganController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');

        $ruangans = Ruangan::when($search, function ($query, $search) {
            $query->where('nama_ruangan', 'like', "%{$search}%")
                ->orWhere('kode_ruangan', 'like', "%{$search}%")
                ->orWhere('lokasi', 'like', "%{$search}%");
        })
            ->orderBy('created_at', 'desc')
            ->get();

        return view('staff.ruangan.index', compact('ruangans', 'search'));
    }

    public function create()
    {
        return view('staff.ruangan.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_ruangan' => 'required|string|unique:ruangan,kode_ruangan',
            'nama_ruangan' => 'required|string|max:255',
            'lokasi' => 'required|string|max:255',
            'keterangan' => 'nullable|string',
        ]);

        Ruangan::create($request->all());

        return redirect()->route('staff.ruangan.index')
            ->with('success', 'Ruangan berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $ruangan = Ruangan::findOrFail($id);

        return view('staff.ruangan.edit', compact('ruangan'));
    }

    public function update(Request $request, $id)
    {
        $ruangan = Ruangan::findOrFail($id);

        $request->validate([
            'kode_ruangan' => ['required', 'string', Rule::unique('ruangan', 'kode_ruangan')->ignore($ruangan->id)],
            'nama_ruangan' => 'required|string|max:255',
            'lokasi' => 'required|string|max:255',
            'keterangan' => 'nullable|string',
        ]);

        $ruangan->update($request->all());

        return redirect()->route('staff.ruangan.index')
            ->with('success', 'Ruangan berhasil diperbarui!');
    }

    public function destroy($id)
    {
        Ruangan::findOrFail($id)->delete();

        return redirect()->route('staff.ruangan.index')
            ->with('success', 'Ruangan berhasil dihapus!');
    }
}
