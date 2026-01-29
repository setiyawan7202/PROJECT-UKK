<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BarangUnit;
use App\Models\Peminjaman;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    /**
     * Display cart items
     */
    public function index()
    {
        $cart = Session::get('cart', []);

        // Auto-fill dates
        $today = now()->format('Y-m-d');
        $tomorrow = now()->addDay()->format('Y-m-d');

        return view('main.cart.index', compact('cart', 'today', 'tomorrow'));
    }

    /**
     * Add item to cart
     */
    public function add(Request $request)
    {
        $request->validate([
            'unit_id' => 'required|exists:barang_unit,id'
        ]);

        $unit = BarangUnit::with('barang')->findOrFail($request->unit_id);

        // Validation: Check if unit is available
        if ($unit->status !== 'aktif') {
            return response()->json(['success' => false, 'message' => 'Unit tidak tersedia.']);
        }

        // Validation: Check if unit is already in cart
        $cart = Session::get('cart', []);
        if (isset($cart[$unit->id])) {
            return response()->json(['success' => false, 'message' => 'Unit sudah ada di keranjang.']);
        }

        // Validation: Check if unit is currently borrowed (Active/Approved)
        $isBorrowed = Peminjaman::where('barang_unit_id', $unit->id)
            ->whereIn('status', ['approved', 'active'])
            ->exists();

        if ($isBorrowed) {
            return response()->json(['success' => false, 'message' => 'Unit sedang dipinjam orang lain.']);
        }

        // Add to cart
        $cart[$unit->id] = [
            'unit_id' => $unit->id,
            'barang_id' => $unit->barang_id,
            'barang_name' => $unit->barang->nama_barang,
            'unit_code' => $unit->kode_unit,
            'kondisi' => $unit->kondisi,
            'gambar' => $unit->barang->gambar
        ];

        Session::put('cart', $cart);

        return response()->json(['success' => true, 'message' => 'Berhasil ditambahkan ke keranjang!', 'count' => count($cart)]);
    }

    /**
     * Remove item from cart
     */
    public function remove(Request $request)
    {
        $request->validate(['unit_id' => 'required']);

        $cart = Session::get('cart', []);

        if (isset($cart[$request->unit_id])) {
            unset($cart[$request->unit_id]);
            Session::put('cart', $cart);
        }

        return redirect()->route('cart.index')->with('success', 'Item dihapus dari keranjang.');
    }

    /**
     * Checkout items
     */
    public function checkout(Request $request)
    {
        $request->validate([
            'tgl_pinjam' => 'required|date|after_or_equal:today',
            'tgl_kembali_rencana' => 'required|date|after_or_equal:tgl_pinjam',
            'tujuan_pinjam' => 'required|string|max:255',
        ]);

        $cart = Session::get('cart', []);

        if (empty($cart)) {
            return redirect()->route('katalog.index')->with('error', 'Keranjang kosong.');
        }

        // Double check availability for all items
        foreach ($cart as $id => $item) {
            // Check status
            $unit = BarangUnit::find($id);
            if (!$unit || $unit->status !== 'aktif') {
                return back()->with('error', "Unit {$item['unit_code']} tidak tersedia statusnya. Menghentikan proses checkout.");
            }

            // Check overlaps
            $isBooked = Peminjaman::where('barang_unit_id', $id)
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

            if ($isBooked) {
                return back()->with('error', "Unit {$item['unit_code']} sudah dipinjam pada tanggal tersebut. Silakan hapus dr keranjang.");
            }
        }

        // Create Peminjaman Records
        foreach ($cart as $id => $item) {
            Peminjaman::create([
                'user_id' => Auth::id(),
                'barang_id' => $item['barang_id'],
                'barang_unit_id' => $item['unit_id'],
                'jumlah' => 1,
                'tgl_pinjam' => $request->tgl_pinjam,
                'tgl_kembali_rencana' => $request->tgl_kembali_rencana,
                'tujuan_pinjam' => $request->tujuan_pinjam,
                'status' => 'pending',
            ]);
        }

        // Clear cart
        Session::forget('cart');

        return redirect()->route('peminjaman.index')->with('success', 'Pengajuan peminjaman berhasil dikirim! Menunggu persetujuan.');
    }
}
