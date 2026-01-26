<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Kategori;
use App\Models\Ruangan;
use Illuminate\Http\Request;

class KatalogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Barang::with('kategori')
            ->where('jumlah_stok', '>', 0); // Only show available items

        if ($request->filled('search')) {
            $query->where('nama_barang', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('kategori_id')) {
            $query->where('kategori_id', $request->kategori_id);
        }

        if ($request->filled('ruangan_id')) {
            $query->where('ruangan_id', $request->ruangan_id);
        }

        if ($request->filled('jenis_aset')) {
            $query->where('jenis_aset', $request->jenis_aset);
        }

        $barangs = $query->paginate(12);
        $kategoris = Kategori::all();
        $ruangans = Ruangan::all();

        return view('main.katalog.index', compact('barangs', 'kategoris', 'ruangans'));
    }
}
