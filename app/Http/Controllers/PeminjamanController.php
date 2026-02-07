<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\BarangUnit;
use App\Models\Peminjaman;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PeminjamanController extends Controller
{
    public function index()
    {
        // Hanya tampilkan status aktif (pending, approved, active)
        $peminjaman = Peminjaman::where('user_id', Auth::id())
            ->whereIn('status', ['pending', 'approved', 'active'])
            ->with(['barang', 'barangUnit'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('peminjaman.index', compact('peminjaman'));
    }

    public function history()
    {
        // Hanya tampilkan status history (completed, rejected, canceled)
        $peminjaman = Peminjaman::where('user_id', Auth::id())
            ->whereIn('status', ['completed', 'rejected', 'canceled'])
            ->with(['barang', 'barangUnit', 'pengembalian'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('peminjaman.history', compact('peminjaman'));
    }

    public function show($id)
    {
        $peminjaman = Peminjaman::where('user_id', Auth::id())
            ->with(['barang.kategori', 'barangUnit', 'pengembalian'])
            ->findOrFail($id);

        return view('peminjaman.show', compact('peminjaman'));
    }

    public function downloadPdf($id)
    {
        $peminjaman = Peminjaman::where('user_id', Auth::id())
            ->with(['barang.kategori', 'barangUnit', 'user.siswa.kelas', 'user.guru'])
            ->findOrFail($id);

        if (!in_array($peminjaman->status, ['approved', 'active', 'completed'])) {
            return back()->with('error', 'Bukti peminjaman hanya tersedia untuk status Disetujui, Aktif, atau Selesai.');
        }

        // Generate QR Code as SVG Base64 (Reliable, no Imagick needed)
        // Note: Using 'qrImage' variable name to match pdf.peminjaman-bukti view
        $url = route('staff.redirectByKode', $peminjaman->kode); // Use redirection route with correct prefix
        $qrRaw = \SimpleSoftwareIO\QrCode\Facades\QrCode::format('svg')->size(100)->generate($url);
        $qrImage = 'data:image/svg+xml;base64,' . base64_encode($qrRaw);

        $pdf = Pdf::loadView('pdf.peminjaman-bukti', compact('peminjaman', 'qrImage'));
        $pdf->setPaper('A4', 'portrait');

        return $pdf->download('Bukti-Peminjaman-' . $peminjaman->kode . '.pdf');
    }

    public function cetakBukti($id)
    {
        $peminjaman = Peminjaman::where('user_id', Auth::id())
            ->with(['barang.kategori', 'barangUnit', 'user.siswa.kelas', 'user.guru'])
            ->findOrFail($id);

        if (!in_array($peminjaman->status, ['approved', 'active', 'completed'])) {
            return back()->with('error', 'Bukti peminjaman hanya tersedia untuk status Disetujui, Aktif, atau Selesai.');
        }

        // Generate QR Code as SVG
        $url = route('staff.redirectByKode', $peminjaman->kode);
        $qrRaw = \SimpleSoftwareIO\QrCode\Facades\QrCode::format('svg')->size(100)->generate($url);
        $qrImage = 'data:image/svg+xml;base64,' . base64_encode($qrRaw);

        $pdf = Pdf::loadView('pdf.peminjaman-bukti', compact('peminjaman', 'qrImage'));
        $pdf->setPaper('A4', 'portrait');

        // Inject Auto-Print JavaScript
        $pdf->render();

        return $pdf->stream('Bukti-Peminjaman-' . $peminjaman->kode . '.pdf');
    }

    public function create(Request $request)
    {
        // Block access on weekends
        if (now()->isWeekend()) {
            return redirect()->route('peminjaman.index')->with('error', 'Layanan peminjaman tutup pada hari Sabtu dan Minggu.');
        }

        $barangs = Barang::where('jumlah_stok', '>', 0)->get();

        $selectedBarang = null;
        $selectedUnit = null;

        if ($request->has('barang_id')) {
            $selectedBarang = Barang::find($request->barang_id);
        }

        if ($request->has('unit_id')) {
            $selectedUnit = BarangUnit::find($request->unit_id);
            if ($selectedUnit && $selectedBarang && $selectedUnit->barang_id != $selectedBarang->id) {
                $selectedUnit = null;
            }
        }

        // Get next available weekday for borrowing date
        $borrowDate = now();
        while ($borrowDate->isWeekend()) {
            $borrowDate->addDay();
        }
        $today = $borrowDate->format('Y-m-d');

        // Get next weekday after borrow date for return date
        $returnDate = $borrowDate->copy()->addDay();
        while ($returnDate->isWeekend()) {
            $returnDate->addDay();
        }
        $tomorrow = $returnDate->format('Y-m-d');

        // Calculate max return date (7 days from borrow date, excluding weekends)
        $maxReturnDate = $borrowDate->copy()->addDays(7);
        while ($maxReturnDate->isWeekend()) {
            $maxReturnDate->subDay();
        }
        $maxReturn = $maxReturnDate->format('Y-m-d');

        return view('peminjaman.create', compact('barangs', 'selectedBarang', 'selectedUnit', 'today', 'tomorrow', 'maxReturn'));
    }

    public function store(Request $request)
    {
        // Block submission on weekends
        if (now()->isWeekend()) {
            return back()->with('error', 'Layanan peminjaman tutup pada hari Sabtu dan Minggu.');
        }

        $request->validate([
            'barang_id' => 'required|exists:barang,id',
            'tgl_pinjam' => 'required|date|after_or_equal:today',
            'tgl_kembali_rencana' => 'required|date|after_or_equal:tgl_pinjam',
            'tujuan_pinjam' => 'required|string|max:255',
            'jumlah' => 'required|integer|min:1|max:1',
        ], [
            'tgl_pinjam.after_or_equal' => 'Tanggal pinjam harus hari ini atau setelahnya.',
            'tgl_kembali_rencana.after_or_equal' => 'Tanggal kembali harus sama dengan atau setelah tanggal pinjam.',
            'tujuan_pinjam.required' => 'Tujuan peminjaman wajib diisi.',
            'barang_id.required' => 'Silakan pilih barang yang akan dipinjam.',
        ]);

        // Validate: no weekends (Strict Check)
        $tglPinjam = \Carbon\Carbon::createFromFormat('Y-m-d', $request->tgl_pinjam);
        $tglKembali = \Carbon\Carbon::createFromFormat('Y-m-d', $request->tgl_kembali_rencana);

        if ($tglPinjam->isWeekend()) {
            return back()->withInput()->with('error', 'Hari Sabtu/Minggu tidak dapat dipilih. Tidak bisa meminjam/mengembalikan barang di hari libur.');
        }

        if ($tglKembali->isWeekend()) {
            return back()->withInput()->with('error', 'Hari Sabtu/Minggu tidak dapat dipilih. Tidak bisa meminjam/mengembalikan barang di hari libur.');
        }

        // Validate: max 7 days return period
        $daysDiff = $tglPinjam->diffInDays($tglKembali);
        if ($daysDiff > 7) {
            return back()->withInput()->with('error', 'Durasi peminjaman maksimal adalah 7 hari.');
        }

        $barang = Barang::findOrFail($request->barang_id);

        if ($barang->jumlah_stok < $request->jumlah) {
            return back()->with('error', 'Stok barang tidak mencukupi.');
        }

        // Check if user already has ANY active/pending loan for this item
        // Regardless of dates, a user cannot borrow the same item if they have an ongoing loan/request.
        $hasExistingLoan = Peminjaman::where('user_id', Auth::id())
            ->where('barang_id', $request->barang_id)
            ->whereIn('status', ['pending', 'approved', 'active'])
            ->exists();

        if ($hasExistingLoan) {
            return back()->with('error', 'Anda masih memiliki peminjaman aktif atau pengajuan menunggu untuk barang ini. Selesaikan peminjaman sebelumnya terlebih dahulu.');
        }

        if ($request->has('barang_unit_id') && $request->filled('barang_unit_id')) {
            $unit = BarangUnit::find($request->barang_unit_id);
            if ($unit && $unit->status !== 'aktif') {
                return back()->with('error', 'Unit barang ini sedang tidak tersedia (Maintenance/Rusak).');
            }

            // Check if this specific unit is booked by ANYONE in the requested dates
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
                return back()->with('error', 'Unit barang ini sudah dipinjam orang lain pada tanggal tersebut.');
            }
        }

        $peminjaman = Peminjaman::create([
            'kode' => Peminjaman::generateKode(),
            'user_id' => Auth::id(),
            'barang_id' => $request->barang_id,
            'barang_unit_id' => $request->barang_unit_id,
            'jumlah' => $request->jumlah,
            'tgl_pinjam' => $request->tgl_pinjam,
            'tgl_kembali_rencana' => $request->tgl_kembali_rencana,
            'tujuan_pinjam' => $request->tujuan_pinjam,
            'status' => 'pending',
        ]);

        \App\Helpers\ActivityLogger::log('Peminjaman', 'Mengajukan peminjaman barang: ' . $barang->nama_barang, $peminjaman);

        return redirect()->route('peminjaman.index')->with('success', 'Pengajuan peminjaman berhasil dikirim. Menunggu persetujuan.');
    }
    public function destroy($id)
    {
        $peminjaman = Peminjaman::where('user_id', Auth::id())->findOrFail($id);

        if ($peminjaman->status !== 'pending') {
            return back()->with('error', 'Hanya pengajuan yang masih menunggu persetujuan yang dapat dibatalkan.');
        }

        $peminjaman->update(['status' => 'canceled']);

        \App\Helpers\ActivityLogger::log('Batal Peminjaman', 'Membatalkan pengajuan peminjaman: ' . $peminjaman->kode, $peminjaman);

        return redirect()->route('peminjaman.index')->with('success', 'Pengajuan peminjaman berhasil dibatalkan.');
    }
}
