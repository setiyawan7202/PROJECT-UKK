<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pengaduan;
use App\Models\CatatanPengaduan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PengaduanController extends Controller
{
    /**
     * Display a listing of complaints.
     */
    public function index(Request $request)
    {
        $query = Pengaduan::with('user')->orderBy('created_at', 'desc');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $pengaduan = $query->paginate(10);
        return view('admin.pengaduan.index', compact('pengaduan'));
    }

    /**
     * Display the specified complaint details.
     */
    public function show($id)
    {
        $pengaduan = Pengaduan::with([
            'user.siswa.kelas',
            'user.guru',
            'catatan.user',
            'ruangan',
            'barang.kategori',
            'barangUnit'
        ])->findOrFail($id);
        return view('admin.pengaduan.show', compact('pengaduan'));
    }

    /**
     * Update the status of the complaint.
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,processed,completed,rejected',
        ]);

        $pengaduan = Pengaduan::findOrFail($id);
        $pengaduan->update(['status' => $request->status]);

        return back()->with('success', 'Status pengaduan diperbarui.');
    }

    /**
     * Add a response/note to the complaint.
     */
    public function storeResponse(Request $request, $id)
    {
        $request->validate([
            'catatan' => 'required|string',
        ]);

        $pengaduan = Pengaduan::findOrFail($id);

        CatatanPengaduan::create([
            'pengaduan_id' => $pengaduan->id,
            'user_id' => Auth::id(),
            'catatan' => $request->catatan,
        ]);

        return back()->with('success', 'Tanggapan berhasil ditambahkan.');
    }
}
