<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Auth;
use App\Models\Kelas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $filter = $request->get('filter');
        $statusFilter = $request->get('status');
        $kelasFilter = $request->get('kelas');
        $search = $request->get('search');

        $query = Auth::with('kelas');

        if ($filter === 'admin') {
            $query->where('role', 'admin');
        } elseif ($filter === 'petugas') {
            $query->where('role', 'petugas');
        } elseif ($filter === 'pengguna') {
            $query->where('role', 'pengguna');
        }

        if ($statusFilter === 'siswa') {
            $query->where('status', 'siswa');
        } elseif ($statusFilter === 'guru') {
            $query->where('status', 'guru');
        }

        if ($kelasFilter) {
            $query->where('kelas_id', $kelasFilter);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('email', 'like', "%{$search}%")
                    ->orWhere('nama_lengkap', 'like', "%{$search}%");
            });
        }

        $users = $query->orderBy('created_at', 'desc')->get();
        $kelasList = Kelas::orderBy('tingkat')->orderBy('nama_kelas')->get();

        return view('admin.users.index', compact('users', 'filter', 'statusFilter', 'kelasFilter', 'search', 'kelasList'));
    }

    public function create()
    {
        $kelasList = Kelas::orderBy('tingkat')->orderBy('nama_kelas')->get();
        return view('admin.users.create', compact('kelasList'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'role' => 'required|in:admin,petugas,pengguna',
            'nama_lengkap' => 'required|string|max:255',
            'status' => 'nullable|in:siswa,guru',
            'kelas_id' => 'nullable|exists:kelas,id',
        ]);

        try {
            Auth::create([
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => $request->role,
                'nama_lengkap' => $request->nama_lengkap,
                'status' => $request->status,
                'kelas_id' => $request->status === 'siswa' ? $request->kelas_id : null,
            ]);

            return redirect()->route('admin.users.index')
                ->with('success', 'User berhasil ditambahkan!');

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Gagal menambahkan user: ' . $e->getMessage()])->withInput();
        }
    }

    public function edit($id)
    {
        $user = Auth::findOrFail($id);
        $kelasList = Kelas::orderBy('tingkat')->orderBy('nama_kelas')->get();

        return view('admin.users.edit', compact('user', 'kelasList'));
    }

    public function update(Request $request, $id)
    {
        $user = Auth::findOrFail($id);

        $request->validate([
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($user->id)],
            'password' => 'nullable|string|min:8',
            'role' => 'required|in:admin,petugas,pengguna',
            'nama_lengkap' => 'required|string|max:255',
            'status' => 'nullable|in:siswa,guru',
            'kelas_id' => 'nullable|exists:kelas,id',
        ]);

        try {
            $userData = [
                'email' => $request->email,
                'nama_lengkap' => $request->nama_lengkap,
                'role' => $request->role,
                'status' => $request->status,
                'kelas_id' => $request->status === 'siswa' ? $request->kelas_id : null,
            ];

            if ($request->filled('password')) {
                $userData['password'] = Hash::make($request->password);
            }

            $user->update($userData);

            return redirect()->route('admin.users.index')
                ->with('success', 'User berhasil diperbarui!');

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Gagal memperbarui user: ' . $e->getMessage()])->withInput();
        }
    }

    public function destroy($id)
    {
        $user = Auth::findOrFail($id);

        if ($user->id === auth()->id()) {
            return back()->withErrors(['error' => 'Tidak dapat menghapus akun sendiri!']);
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'User berhasil dihapus!');
    }
}
