<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use Illuminate\Http\Request;

class KelasController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');

        $query = Kelas::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nama_kelas', 'like', "%{$search}%")
                    ->orWhere('jurusan', 'like', "%{$search}%");
            });
        }

        $kelasList = $query->withCount('siswa')->orderBy('tingkat')->orderBy('nama_kelas')->get();

        return view('admin.kelas.index', compact('kelasList', 'search'));
    }

    public function create()
    {
        return view('admin.kelas.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_kelas' => 'required|string|max:255|unique:kelas,nama_kelas',
            'jurusan' => 'required|string|max:255',
            'tingkat' => 'required|in:X,XI,XII',
        ]);

        try {
            Kelas::create([
                'nama_kelas' => $request->nama_kelas,
                'jurusan' => $request->jurusan,
                'tingkat' => $request->tingkat,
            ]);

            return redirect()->route('admin.kelas.index')
                ->with('success', 'Kelas berhasil ditambahkan!');

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Gagal menambahkan kelas: ' . $e->getMessage()])->withInput();
        }
    }

    public function edit($id)
    {
        $kelas = Kelas::findOrFail($id);

        return view('admin.kelas.edit', compact('kelas'));
    }

    public function update(Request $request, $id)
    {
        $kelas = Kelas::findOrFail($id);

        $request->validate([
            'nama_kelas' => 'required|string|max:255|unique:kelas,nama_kelas,' . $id,
            'jurusan' => 'required|string|max:255',
            'tingkat' => 'required|in:X,XI,XII',
        ]);

        try {
            $kelas->update([
                'nama_kelas' => $request->nama_kelas,
                'jurusan' => $request->jurusan,
                'tingkat' => $request->tingkat,
            ]);

            return redirect()->route('admin.kelas.index')
                ->with('success', 'Kelas berhasil diperbarui!');

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Gagal memperbarui kelas: ' . $e->getMessage()])->withInput();
        }
    }

    public function destroy($id)
    {
        $kelas = Kelas::withCount('siswa')->findOrFail($id);

        if ($kelas->siswa_count > 0) {
            return back()->withErrors(['error' => 'Tidak dapat menghapus kelas yang masih memiliki siswa!']);
        }

        $kelas->delete();

        return redirect()->route('admin.kelas.index')
            ->with('success', 'Kelas berhasil dihapus!');
    }
    public function trash()
    {
        $kelasList = Kelas::onlyTrashed()->orderBy('deleted_at', 'desc')->get();
        return view('admin.kelas.trash', compact('kelasList'));
    }

    public function restore($id)
    {
        $kelas = Kelas::onlyTrashed()->findOrFail($id);
        $kelas->restore();

        return redirect()->route('admin.kelas.trash')->with('success', 'Kelas berhasil dipulihkan!');
    }

    public function forceDelete($id)
    {
        $kelas = Kelas::onlyTrashed()->findOrFail($id);

        // Cek jika ada siswa (meskipun soft deleted)
        if ($kelas->siswa()->withTrashed()->count() > 0) {
            return back()->withErrors(['error' => 'Tidak dapat menghapus permanen kelas yang masih memiliki siswa (termasuk di sampah)!']);
        }

        $kelas->forceDelete();

        return redirect()->route('admin.kelas.trash')->with('success', 'Kelas berhasil dihapus permanen!');
    }
}
