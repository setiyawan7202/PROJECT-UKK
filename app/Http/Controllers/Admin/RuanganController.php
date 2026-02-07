<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Auth;
use App\Models\Ruangan;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class RuanganController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');

        $ruangans = Ruangan::with(['kepala1', 'kepala2'])
            ->when($search, function ($query, $search) {
                $query->where('nama_ruangan', 'like', "%{$search}%")
                    ->orWhere('kode_ruangan', 'like', "%{$search}%")
                    ->orWhere('lokasi', 'like', "%{$search}%");
            })
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.ruangan.index', compact('ruangans', 'search'));
    }

    public function create()
    {
        $generatedKode = Ruangan::generateKode();
        $kepalaUsers = Auth::where('role', 'kepala_lab')->orderBy('username')->get();
        return view('admin.ruangan.create', compact('generatedKode', 'kepalaUsers'));
    }

    /**
     * Generate kode ruangan via AJAX
     */
    public function generateKode()
    {
        return response()->json([
            'kode' => Ruangan::generateKode()
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_ruangan' => 'required|string|unique:ruangan,kode_ruangan',
            'nama_ruangan' => 'required|string|max:255',
            'lokasi' => 'required|string|max:255',
            'keterangan' => 'nullable|string',
            'kepala1_id' => 'nullable|exists:users,id',
            'kepala2_id' => 'nullable|exists:users,id|different:kepala1_id',
        ]);

        Ruangan::create($request->only(['kode_ruangan', 'nama_ruangan', 'lokasi', 'keterangan', 'kepala1_id', 'kepala2_id']));

        return redirect()->route('admin.ruangan.index')
            ->with('success', 'Ruangan berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $ruangan = Ruangan::findOrFail($id);
        $kepalaUsers = Auth::where('role', 'kepala_lab')->orderBy('username')->get();

        return view('admin.ruangan.edit', compact('ruangan', 'kepalaUsers'));
    }

    public function update(Request $request, $id)
    {
        $ruangan = Ruangan::findOrFail($id);

        $request->validate([
            'kode_ruangan' => ['required', 'string', Rule::unique('ruangan', 'kode_ruangan')->ignore($ruangan->id)],
            'nama_ruangan' => 'required|string|max:255',
            'lokasi' => 'required|string|max:255',
            'keterangan' => 'nullable|string',
            'kepala1_id' => 'nullable|exists:users,id',
            'kepala2_id' => 'nullable|exists:users,id|different:kepala1_id',
        ]);

        $ruangan->update($request->only(['kode_ruangan', 'nama_ruangan', 'lokasi', 'keterangan', 'kepala1_id', 'kepala2_id']));

        return redirect()->route('admin.ruangan.index')
            ->with('success', 'Ruangan berhasil diperbarui!');
    }

    public function destroy($id)
    {
        Ruangan::findOrFail($id)->delete();

        return redirect()->route('admin.ruangan.index')
            ->with('success', 'Ruangan berhasil dihapus!');
    }
    public function trash()
    {
        $ruangans = Ruangan::onlyTrashed()->orderBy('deleted_at', 'desc')->get();
        return view('admin.ruangan.trash', compact('ruangans'));
    }

    public function restore($id)
    {
        $ruangan = Ruangan::onlyTrashed()->findOrFail($id);
        $ruangan->restore();

        return redirect()->route('admin.ruangan.trash')->with('success', 'Ruangan berhasil dipulihkan!');
    }

    public function forceDelete($id)
    {
        $ruangan = Ruangan::onlyTrashed()->findOrFail($id);
        $ruangan->forceDelete();

        return redirect()->route('admin.ruangan.trash')->with('success', 'Ruangan berhasil dihapus permanen!');
    }
}
