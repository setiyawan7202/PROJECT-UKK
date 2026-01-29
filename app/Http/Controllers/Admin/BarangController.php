<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\BarangUnit;
use App\Models\Kategori;
use App\Models\Ruangan;
use App\Services\ImageUploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class BarangController extends Controller
{
    public function index(Request $request)
    {
        $barangs = Barang::with(['kategori', 'ruangan', 'units'])
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('nama_barang', 'like', "%{$search}%")
                        ->orWhere('kode', 'like', "%{$search}%");
                });
            })
            ->when($request->filled('kategori_id'), fn($q) => $q->where('kategori_id', $request->kategori_id))
            ->when($request->filled('ruangan_id'), fn($q) => $q->where('ruangan_id', $request->ruangan_id))
            ->when($request->filled('jenis_aset'), fn($q) => $q->where('jenis_aset', $request->jenis_aset))
            ->orderBy('created_at', 'desc')
            ->get();

        $kategoris = Kategori::all();
        $ruangans = Ruangan::all();

        return view('admin.barang.index', compact('barangs', 'kategoris', 'ruangans'));
    }

    public function create()
    {
        $kategoris = Kategori::all();
        $ruangans = Ruangan::all();

        return view('admin.barang.create', compact('kategoris', 'ruangans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_barang' => 'required|string|max:255',
            'kategori_id' => 'required|exists:kategori,id',
            'ruangan_id' => 'required|exists:ruangan,id',
            'tipe_barang' => 'required|in:maintenance,disposable',
            'jenis_aset' => 'required|in:tik,non_tik',
            'jumlah_stok' => 'required|integer|min:1',
            'gambar' => 'nullable|image|mimes:jpeg,jpg,png,gif,webp|max:2048',
        ]);

        DB::beginTransaction();
        try {
            // Generate kode dari nama barang
            $kode = BarangUnit::generatePrefix($request->nama_barang);

            // Handle image upload
            $gambarPath = null;
            if ($request->hasFile('gambar')) {
                $imageService = new ImageUploadService();
                $gambarPath = $imageService->upload($request->file('gambar'), 'barang_' . time());
            }

            // Create barang record
            $barang = Barang::create([
                'kode' => $kode,
                'nama_barang' => $request->nama_barang,
                'kategori_id' => $request->kategori_id,
                'ruangan_id' => $request->ruangan_id,
                'tipe_barang' => $request->tipe_barang,
                'jenis_aset' => $request->jenis_aset,
                'jumlah_stok' => $request->jumlah_stok,
                'gambar' => $gambarPath,
            ]);

            // Generate unit codes
            $kodes = BarangUnit::generateKodeUnits($request->nama_barang, $request->jumlah_stok);

            // Create individual units with default kondisi 'Baik'
            foreach ($kodes as $kode) {
                BarangUnit::create([
                    'barang_id' => $barang->id,
                    'kategori_id' => $request->kategori_id,
                    'kode_unit' => $kode,
                    'kondisi' => 'Baik',
                ]);
            }

            DB::commit();

            return redirect()->route('admin.barang.index')
                ->with('success', "Barang berhasil ditambahkan dengan {$request->jumlah_stok} unit!");
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Gagal menambahkan barang: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $barang = Barang::with(['kategori', 'ruangan', 'units'])->findOrFail($id);
        return view('admin.barang.show', compact('barang'));
    }

    public function edit($id)
    {
        $barang = Barang::with('units')->findOrFail($id);
        $kategoris = Kategori::all();
        $ruangans = Ruangan::all();

        return view('admin.barang.edit', compact('barang', 'kategoris', 'ruangans'));
    }

    public function update(Request $request, $id)
    {
        $barang = Barang::findOrFail($id);

        $request->validate([
            'nama_barang' => 'required|string|max:255',
            'kategori_id' => 'required|exists:kategori,id',
            'ruangan_id' => 'required|exists:ruangan,id',
            'tipe_barang' => 'required|in:maintenance,disposable',
            'jenis_aset' => 'required|in:tik,non_tik',
            'gambar' => 'nullable|image|mimes:jpeg,jpg,png,gif,webp|max:2048',
        ]);

        // Handle image upload
        if ($request->hasFile('gambar')) {
            // Note: Old images on ImgBB cannot be deleted via API
            $imageService = new ImageUploadService();
            $barang->gambar = $imageService->upload($request->file('gambar'), 'barang_' . time());
        }

        $barang->update([
            'nama_barang' => $request->nama_barang,
            'kategori_id' => $request->kategori_id,
            'ruangan_id' => $request->ruangan_id,
            'tipe_barang' => $request->tipe_barang,
            'jenis_aset' => $request->jenis_aset,
            'gambar' => $barang->gambar,
        ]);

        return redirect()->route('admin.barang.index')
            ->with('success', 'Barang berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $barang = Barang::findOrFail($id);

        // Delete image if exists
        if ($barang->gambar && Storage::disk('public')->exists($barang->gambar)) {
            Storage::disk('public')->delete($barang->gambar);
        }

        $barang->delete();

        return redirect()->route('admin.barang.index')
            ->with('success', 'Barang berhasil dihapus!');
    }

    /**
     * Update kondisi atau status unit
     */
    public function updateUnit(Request $request, $id)
    {
        $unit = BarangUnit::findOrFail($id);
        $field = $request->input('field', 'kondisi');

        if ($field === 'status') {
            $request->validate([
                'status' => 'required|in:aktif,maintenance,rusak',
            ]);
            $unit->update(['status' => $request->status]);
            return back()->with('success', 'Status unit berhasil diperbarui!');
        } else {
            $request->validate([
                'kondisi' => 'required|in:Baik,Rusak Ringan,Rusak Berat',
            ]);
            $unit->update(['kondisi' => $request->kondisi]);
            return back()->with('success', 'Kondisi unit berhasil diperbarui!');
        }
    }
}
