<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class KategoriController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');

        $kategoris = Kategori::when($search, function ($query, $search) {
            $query->where('nama_kategori', 'like', "%{$search}%")
                ->orWhere('kode_kategori', 'like', "%{$search}%");
        })
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.kategori.index', compact('kategoris', 'search'));
    }

    public function create()
    {
        $generatedKode = Kategori::generateKode();
        return view('admin.kategori.create', compact('generatedKode'));
    }

    /**
     * Generate kode kategori via AJAX
     */
    public function generateKode()
    {
        return response()->json([
            'kode' => Kategori::generateKode()
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_kategori' => 'required|string|unique:kategori,kode_kategori',
            'nama_kategori' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
        ]);

        Kategori::create($request->all());

        return redirect()->route('admin.kategori.index')
            ->with('success', 'Kategori berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $kategori = Kategori::findOrFail($id);

        return view('admin.kategori.edit', compact('kategori'));
    }

    public function update(Request $request, $id)
    {
        $kategori = Kategori::findOrFail($id);

        $request->validate([
            'kode_kategori' => ['required', 'string', Rule::unique('kategori', 'kode_kategori')->ignore($kategori->id)],
            'nama_kategori' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
        ]);

        $kategori->update($request->all());

        return redirect()->route('admin.kategori.index')
            ->with('success', 'Kategori berhasil diperbarui!');
    }

    public function destroy($id)
    {
        Kategori::findOrFail($id)->delete();

        return redirect()->route('admin.kategori.index')
            ->with('success', 'Kategori berhasil dihapus!');
    }
    public function trash()
    {
        $kategoris = Kategori::onlyTrashed()->orderBy('deleted_at', 'desc')->get();
        return view('admin.kategori.trash', compact('kategoris'));
    }

    public function restore($id)
    {
        $kategori = Kategori::onlyTrashed()->findOrFail($id);
        $kategori->restore();

        return redirect()->route('admin.kategori.trash')->with('success', 'Kategori berhasil dipulihkan!');
    }

    public function forceDelete($id)
    {
        $kategori = Kategori::onlyTrashed()->findOrFail($id);
        $kategori->forceDelete();

        return redirect()->route('admin.kategori.trash')->with('success', 'Kategori berhasil dihapus permanen!');
    }
}
