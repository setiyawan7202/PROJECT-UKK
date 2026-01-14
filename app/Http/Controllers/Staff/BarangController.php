<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\Kategori;
use App\Models\Ruangan;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class BarangController extends Controller
{
    public function index(Request $request)
    {
        $barangs = Barang::with(['kategori', 'ruangan'])
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('nama_barang', 'like', "%{$search}%")
                        ->orWhere('kode_barang', 'like', "%{$search}%");
                });
            })
            ->when($request->filled('kategori_id'), fn($q) => $q->where('kategori_id', $request->kategori_id))
            ->when($request->filled('ruangan_id'), fn($q) => $q->where('ruangan_id', $request->ruangan_id))
            ->orderBy('created_at', 'desc')
            ->get();

        $kategoris = Kategori::all();
        $ruangans = Ruangan::all();

        return view('staff.barang.index', compact('barangs', 'kategoris', 'ruangans'));
    }

    public function create()
    {
        $kategoris = Kategori::all();
        $ruangans = Ruangan::all();

        return view('staff.barang.create', compact('kategoris', 'ruangans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_barang' => 'required|string|unique:barang,kode_barang',
            'nama_barang' => 'required|string|max:255',
            'kategori_id' => 'required|exists:kategori,id',
            'ruangan_id' => 'required|exists:ruangan,id',
            'jumlah_stok' => 'required|integer|min:0',
            'kondisi_saat_ini' => 'required|string',
        ]);

        Barang::create($request->all());

        return redirect()->route('staff.barang.index')
            ->with('success', 'Barang berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $barang = Barang::findOrFail($id);
        $kategoris = Kategori::all();
        $ruangans = Ruangan::all();

        return view('staff.barang.edit', compact('barang', 'kategoris', 'ruangans'));
    }

    public function update(Request $request, $id)
    {
        $barang = Barang::findOrFail($id);

        $request->validate([
            'kode_barang' => ['required', 'string', Rule::unique('barang', 'kode_barang')->ignore($barang->id)],
            'nama_barang' => 'required|string|max:255',
            'kategori_id' => 'required|exists:kategori,id',
            'ruangan_id' => 'required|exists:ruangan,id',
            'jumlah_stok' => 'required|integer|min:0',
            'kondisi_saat_ini' => 'required|string',
        ]);

        $barang->update($request->all());

        return redirect()->route('staff.barang.index')
            ->with('success', 'Barang berhasil diperbarui!');
    }

    public function destroy($id)
    {
        Barang::findOrFail($id)->delete();

        return redirect()->route('staff.barang.index')
            ->with('success', 'Barang berhasil dihapus!');
    }
}
