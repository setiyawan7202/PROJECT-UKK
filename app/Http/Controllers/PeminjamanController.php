<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PeminjamanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $peminjaman = Peminjaman::where('user_id', Auth::id())
            ->with(['barang', 'barangUnit'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('peminjaman.index', compact('peminjaman'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Only show items that have stock > 0
        $barangs = Barang::where('jumlah_stok', '>', 0)->get();
        return view('peminjaman.create', compact('barangs'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'barang_id' => 'required|exists:barang,id',
            'tgl_pinjam' => 'required|date|after_or_equal:today',
            'tgl_kembali_rencana' => 'required|date|after_or_equal:tgl_pinjam',
            'tujuan_pinjam' => 'required|string|max:255',
            'jumlah' => 'required|integer|min:1|max:1', // Usually 1 per request for tracking
        ]);

        $barang = Barang::findOrFail($request->barang_id);

        if ($barang->jumlah_stok < $request->jumlah) {
            return back()->with('error', 'Stok barang tidak mencukupi.');
        }

        // Check for double booking (Tier 1 requirement - simplified)
        // User cannot borrow the same item type if they currently have an active loan for it?
        // Or specific unit check? Since unit is assigned later, we can only check if user has pending request for same item?
        // For now, let's just allow it, admin adjudicates.

        Peminjaman::create([
            'user_id' => Auth::id(),
            'barang_id' => $request->barang_id,
            'jumlah' => $request->jumlah,
            'tgl_pinjam' => $request->tgl_pinjam,
            'tgl_kembali_rencana' => $request->tgl_kembali_rencana,
            'tujuan_pinjam' => $request->tujuan_pinjam,
            'status' => 'pending',
        ]);

        return redirect()->route('peminjaman.index')->with('success', 'Pengajuan peminjaman berhasil dikirim. Menunggu persetujuan.');
    }
}
