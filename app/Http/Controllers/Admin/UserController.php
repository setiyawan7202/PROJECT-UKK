<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Auth;
use App\Models\Guru;
use App\Models\Siswa;
use App\Models\Kelas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $filter = $request->get('filter');
        $kelas_id = $request->get('kelas_id');
        $search = $request->get('search');

        $query = Auth::with(['guru', 'siswa.kelas']);

        if ($filter === 'admin') {
            $query->where('role', 'admin');
        } elseif ($filter === 'petugas') {
            $query->where('role', 'petugas');
        } elseif ($filter === 'guru') {
            $query->where('role', 'pengguna')->whereHas('guru');
        } elseif ($filter === 'siswa') {
            $query->where('role', 'pengguna')->whereHas('siswa', function ($q) use ($kelas_id) {
                if ($kelas_id) {
                    $q->where('kelas_id', $kelas_id);
                }
            });
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('email', 'like', "%{$search}%")
                    ->orWhere('nama_lengkap', 'like', "%{$search}%")
                    ->orWhereHas('guru', fn($q2) => $q2->where('nama_lengkap', 'like', "%{$search}%")->orWhere('nip', 'like', "%{$search}%"))
                    ->orWhereHas('siswa', fn($q2) => $q2->where('nama_lengkap', 'like', "%{$search}%")->orWhere('nisn', 'like', "%{$search}%"));
            });
        }

        $users = $query->orderBy('created_at', 'desc')->get();
        $kelas = Kelas::all();

        return view('admin.users.index', compact('users', 'filter', 'search', 'kelas', 'kelas_id'));
    }

    public function create()
    {
        $kelas = Kelas::all();

        return view('admin.users.create', compact('kelas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'role' => 'required|in:admin,petugas,pengguna',
            'identity_type' => 'required_if:role,pengguna|in:guru,siswa',
            'nama_lengkap' => 'required|string|max:255',
            'nip' => 'required_if:identity_type,guru|nullable|string|digits:18|unique:guru,nip',
            'nisn' => 'required_if:identity_type,siswa|nullable|string|digits:10|unique:siswa,nisn',
            'kelas_id' => 'required_if:identity_type,siswa|nullable|exists:kelas,id',
        ]);

        DB::beginTransaction();

        try {
            $user = Auth::create([
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => $request->role,
                'nama_lengkap' => $request->nama_lengkap,
            ]);

            if ($request->role === 'pengguna') {
                if ($request->identity_type === 'guru') {
                    Guru::create([
                        'user_id' => $user->id,
                        'nama_lengkap' => $request->nama_lengkap,
                        'email' => $request->email,
                        'nip' => $request->nip,
                    ]);
                } elseif ($request->identity_type === 'siswa') {
                    Siswa::create([
                        'user_id' => $user->id,
                        'nama_lengkap' => $request->nama_lengkap,
                        'email' => $request->email,
                        'nisn' => $request->nisn,
                        'kelas_id' => $request->kelas_id,
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('admin.users.index')
                ->with('success', 'User berhasil ditambahkan!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Gagal menambahkan user: ' . $e->getMessage()])->withInput();
        }
    }

    public function edit($id)
    {
        $user = Auth::with(['guru', 'siswa'])->findOrFail($id);
        $kelas = Kelas::all();

        return view('admin.users.edit', compact('user', 'kelas'));
    }

    public function update(Request $request, $id)
    {
        $user = Auth::findOrFail($id);

        $request->validate([
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($user->id)],
            'password' => 'nullable|string|min:8',
            'role' => 'required|in:admin,petugas,pengguna',
            'nama_lengkap' => 'required|string|max:255',
            'identity_type' => 'required_if:role,pengguna|in:guru,siswa',
            'nip' => 'required_if:identity_type,guru|nullable|string|digits:18|unique:guru,nip' . ($user->guru ? ',' . $user->guru->id : ''),
            'nisn' => 'required_if:identity_type,siswa|nullable|string|digits:10|unique:siswa,nisn' . ($user->siswa ? ',' . $user->siswa->id : ''),
            'kelas_id' => 'required_if:identity_type,siswa|nullable|exists:kelas,id',
        ]);

        DB::beginTransaction();

        try {
            $userData = [
                'email' => $request->email,
                'nama_lengkap' => $request->nama_lengkap,
                'role' => $request->role,
            ];

            if ($request->filled('password')) {
                $userData['password'] = Hash::make($request->password);
            }

            $user->update($userData);

            if ($request->role === 'pengguna') {
                if ($request->identity_type === 'guru') {
                    $guruData = [
                        'user_id' => $user->id,
                        'nama_lengkap' => $request->nama_lengkap,
                        'email' => $request->email,
                        'nip' => $request->nip,
                    ];

                    $user->guru ? $user->guru->update($guruData) : Guru::create($guruData);

                } elseif ($request->identity_type === 'siswa') {
                    $siswaData = [
                        'user_id' => $user->id,
                        'nama_lengkap' => $request->nama_lengkap,
                        'email' => $request->email,
                        'nisn' => $request->nisn,
                        'kelas_id' => $request->kelas_id,
                    ];

                    $user->siswa ? $user->siswa->update($siswaData) : Siswa::create($siswaData);
                }
            } else {
                if ($user->guru) {
                    $user->guru->update(['nama_lengkap' => $request->nama_lengkap, 'email' => $request->email]);
                }
                if ($user->siswa) {
                    $user->siswa->update(['nama_lengkap' => $request->nama_lengkap, 'email' => $request->email]);
                }
            }

            DB::commit();

            return redirect()->route('admin.users.index')
                ->with('success', 'User berhasil diperbarui!');

        } catch (\Exception $e) {
            DB::rollBack();
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
