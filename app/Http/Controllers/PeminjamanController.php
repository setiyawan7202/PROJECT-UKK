<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\BarangUnit;
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
    public function create(Request $request)
    {
        // Only show items that have stock > 0
        $barangs = Barang::where('jumlah_stok', '>', 0)->get();

        $selectedBarang = null;
        $selectedUnit = null;

        if ($request->has('barang_id')) {
            $selectedBarang = Barang::find($request->barang_id);
        }

        if ($request->has('unit_id')) {
            $selectedUnit = BarangUnit::find($request->unit_id);
            // If unit is selected, ensure it belongs to the selected barang
            if ($selectedUnit && $selectedBarang && $selectedUnit->barang_id != $selectedBarang->id) {
                $selectedUnit = null;
            }
        }

        // Auto-fill dates
        $today = now()->format('Y-m-d');
        $tomorrow = now()->addDay()->format('Y-m-d');

        return view('peminjaman.create', compact('barangs', 'selectedBarang', 'selectedUnit', 'today', 'tomorrow'));
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

        // Double Booking Prevention (Tier 1 Requirement)
        // Check if user already has an active/pending loan for this item with overlapping dates
        $hasOverlappingBooking = Peminjaman::where('user_id', Auth::id())
            ->where('barang_id', $request->barang_id)
            ->whereIn('status', ['pending', 'approved', 'active'])
            ->where(function ($query) use ($request) {
                // Check for date overlap
                $query->whereBetween('tgl_pinjam', [$request->tgl_pinjam, $request->tgl_kembali_rencana])
                    ->orWhereBetween('tgl_kembali_rencana', [$request->tgl_pinjam, $request->tgl_kembali_rencana])
                    ->orWhere(function ($q) use ($request) {
                    $q->where('tgl_pinjam', '<=', $request->tgl_pinjam)
                        ->where('tgl_kembali_rencana', '>=', $request->tgl_kembali_rencana);
                });
            })
            ->exists();

        if ($hasOverlappingBooking) {
            return back()->with('error', 'Anda sudah memiliki peminjaman aktif untuk barang ini pada rentang tanggal yang sama. Silakan pilih tanggal lain atau tunggu peminjaman sebelumnya selesai.');
        }

        // Validate unit availability if specific unit is requested
        if ($request->has('barang_unit_id') && $request->filled('barang_unit_id')) {
            $unit = BarangUnit::find($request->barang_unit_id);
            if ($unit && $unit->status !== 'aktif') {
                return back()->with('error', 'Unit barang ini sedang tidak tersedia (Maintenance/Rusak).');
            }

            // Check if this specific unit is already booked
            $isUnitBooked = Peminjaman::where('barang_unit_id', $request->barang_unit_id)
                ->whereIn('status', ['pending', 'approved', 'active'])
                ->where(function ($query) use ($request) {
                    $query->whereBetween('tgl_pinjam', [$request->tgl_pinjam, $request->tgl_kembali_rencana])
                        ->orWhereBetween('tgl_kembali_rencana', [$request->tgl_pinjam, $request->tgl_kembali_rencana])
                        ->orWhere(function ($q) use ($request) {
                            $q->where('tgl_pinjam', '<=', $request->tgl_pinjam)
                                ->where('tgl_kembali_rencana', '>=', $request->tgl_kembali_rencana);
                        });
                })
                ->exists();

            if ($isUnitBooked) {
                return back()->with('error', 'Unit barang ini sudah dipinjam pada tanggal tersebut.');
            }
        }

        Peminjaman::create([
            'user_id' => Auth::id(),
            'barang_id' => $request->barang_id,
            'barang_unit_id' => $request->barang_unit_id, // Added barang_unit_id
            'jumlah' => $request->jumlah,
            'tgl_pinjam' => $request->tgl_pinjam,
            'tgl_kembali_rencana' => $request->tgl_kembali_rencana,
            'tujuan_pinjam' => $request->tujuan_pinjam,
            'status' => 'pending',
        ]);

        return redirect()->route('peminjaman.index')->with('success', 'Pengajuan peminjaman berhasil dikirim. Menunggu persetujuan.');
    }
}
