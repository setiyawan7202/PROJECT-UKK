<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Peminjaman;

class ScanController extends Controller
{
    /**
     * Scan QR code for viewing loan proof/receipt
     */
    public function index($kode)
    {
        $peminjaman = Peminjaman::where('kode', $kode)->firstOrFail();
        return redirect()->route('admin.peminjaman.bukti', $peminjaman->id);
    }

    /**
     * Scan QR code for return process (auto-fill return form)
     */
    public function scanReturn($kode)
    {
        $peminjaman = Peminjaman::where('kode', $kode)
            ->whereIn('status', ['active', 'approved'])
            ->firstOrFail();

        // Redirect to return form with auto-filled data
        return redirect()->route('admin.peminjaman.returnForm', $peminjaman->id)
            ->with('success', 'QR Code berhasil di-scan! Form pengembalian siap diisi.');
    }
}
