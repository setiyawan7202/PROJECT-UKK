<?php

namespace App\Http\Controllers;

use App\Models\Pengaduan;
use App\Models\Ruangan;
use App\Models\Barang;
use App\Services\ImageUploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PengaduanController extends Controller
{
    /**
     * Display a listing of the user's complaints.
     */
    public function index()
    {
        $pengaduan = Pengaduan::where('user_id', Auth::id())
            ->with(['ruangan', 'barang'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('pengaduan.index', compact('pengaduan'));
    }

    /**
     * Show the form for creating a new complaint.
     */
    public function create()
    {
        $ruangans = Ruangan::orderBy('nama_ruangan')->get();

        return view('pengaduan.create', compact('ruangans'));
    }

    /**
     * Get barang by ruangan_id (AJAX endpoint)
     */
    public function getBarangByRuangan($ruangan_id)
    {
        $barangs = Barang::where('ruangan_id', $ruangan_id)
            ->with('kategori')
            ->orderBy('nama_barang')
            ->get(['id', 'nama_barang', 'kategori_id']);

        // Add kategori name to response
        $result = $barangs->map(function ($barang) {
            return [
                'id' => $barang->id,
                'nama_barang' => $barang->nama_barang,
                'kategori' => $barang->kategori ? $barang->kategori->nama : null,
            ];
        });

        return response()->json($result);
    }

    /**
     * Get barang units by barang_id (AJAX endpoint)
     */
    public function getBarangUnits($barang_id)
    {
        $units = \App\Models\BarangUnit::where('barang_id', $barang_id)
            ->orderBy('kode_unit')
            ->get(['id', 'kode_unit', 'kondisi']);

        return response()->json($units);
    }

    /**
     * Store a newly created complaint.
     */
    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'ruangan_id' => 'required|exists:ruangan,id',
            'barang_id' => 'nullable|exists:barang,id',
            'barang_unit_id' => 'nullable|exists:barang_unit,id',
            'kondisi' => 'nullable|in:baik,rusak_ringan,rusak_berat,hilang',
            'deskripsi' => 'required|string',
            'foto' => 'nullable|image|max:2048', // Max 2MB
        ]);

        $fotoPath = null;
        if ($request->hasFile('foto')) {
            $imageService = new ImageUploadService();
            $fotoPath = $imageService->upload($request->file('foto'), 'pengaduan_' . time());
        }

        Pengaduan::create([
            'user_id' => Auth::id(),
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'ruangan_id' => $request->ruangan_id,
            'barang_id' => $request->barang_id,
            'barang_unit_id' => $request->barang_unit_id,
            'kondisi' => $request->kondisi,
            'foto' => $fotoPath,
            'status' => 'pending',
        ]);

        return redirect()->route('pengaduan.index')->with('success', 'Pengaduan berhasil dikirim.');
    }

    /**
     * Display the specified complaint.
     */
    public function show($id)
    {
        $pengaduan = Pengaduan::where('user_id', Auth::id())
            ->with(['catatan.user', 'ruangan', 'barang.kategori', 'barangUnit'])
            ->findOrFail($id);
        return view('pengaduan.show', compact('pengaduan'));
    }

    /**
     * Add user response/reply to complaint.
     */
    public function addResponse(Request $request, $id)
    {
        $pengaduan = Pengaduan::where('user_id', Auth::id())->findOrFail($id);

        $request->validate([
            'catatan' => 'required|string|max:1000',
        ]);

        \App\Models\CatatanPengaduan::create([
            'pengaduan_id' => $pengaduan->id,
            'user_id' => Auth::id(),
            'catatan' => $request->catatan,
        ]);

        return redirect()->route('pengaduan.show', $id)->with('success', 'Balasan berhasil ditambahkan.');
    }
}
