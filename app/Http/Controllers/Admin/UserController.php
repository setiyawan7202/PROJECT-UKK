<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Auth;
use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\Guru;
use App\Mail\UserRegistered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $filter = $request->get('filter');
        $statusFilter = $request->get('status');
        $kelasFilter = $request->get('kelas');
        $search = $request->get('search');

        $query = Auth::with('siswa.kelas');

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
            $query->whereHas('siswa', function ($q) use ($kelasFilter) {
                $q->where('kelas_id', $kelasFilter);
            });
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('email', 'like', "%{$search}%")
                    ->orWhere('nama_lengkap', 'like', "%{$search}%");
            });
        }

        $users = $query->orderBy('created_at', 'asc')->get();
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
            // Siswa validation
            'nisn' => 'nullable|required_if:status,siswa|unique:siswa,nisn|numeric',
            'kelas_id' => 'nullable|required_if:status,siswa|exists:kelas,id',
            // Guru validation
            'nip' => 'nullable|required_if:status,guru|unique:guru,nip|numeric',
        ]);

        DB::beginTransaction();
        try {
            $username = null;
            if ($request->status === 'siswa') {
                $username = $request->nisn;
            } elseif ($request->status === 'guru') {
                $username = $request->nip;
            }

            $user = Auth::create([
                'email' => $request->email,
                'data_nip_nisn' => $username,
                'password' => Hash::make($request->password),
                'role' => $request->role,
                'status' => $request->status,
            ]);

            // Create related records
            if ($request->status === 'siswa') {
                Siswa::create([
                    'user_id' => $user->id,
                    'nisn' => $request->nisn,
                    'username' => $request->nama_lengkap, // Store nama_lengkap as requested
                    'email' => $request->email,
                    'kelas_id' => $request->kelas_id,
                ]);
            } elseif ($request->status === 'guru') {
                Guru::create([
                    'user_id' => $user->id,
                    'nip' => $request->nip,
                    'username' => $request->nama_lengkap, // Store nama_lengkap as requested
                    'email' => $request->email,
                ]);
            }

            // Send Email via SMTP
            try {
                Mail::to($user->email)->send(new UserRegistered($user, $request->password, $request->nama_lengkap));
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('SMTP Error: ' . $e->getMessage());
            }

            DB::commit();

            return redirect()->route('admin.users.index')
                ->with('success', 'User berhasil ditambahkan dan notifikasi email dikirim!');

        } catch (\Exception $e) {
            DB::rollBack();
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
                'role' => $request->role,
                'status' => $request->status,
            ];

            // Update Username (Nama Lengkap) in Child Tables
            if ($request->status === 'siswa') {
                $user->siswa()->updateOrCreate(
                    ['user_id' => $user->id],
                    [
                        'nisn' => $request->nisn,
                        'username' => $request->nama_lengkap,
                        'email' => $request->email,
                        'kelas_id' => $request->kelas_id
                    ]
                );
                // Also update NISN map
                $userData['data_nip_nisn'] = $request->nisn;

            } elseif ($request->status === 'guru') {
                $user->guru()->updateOrCreate(
                    ['user_id' => $user->id],
                    [
                        'nip' => $request->nip,
                        'username' => $request->nama_lengkap,
                        'email' => $request->email
                    ]
                );
                // Also update NIP map
                $userData['data_nip_nisn'] = $request->nip;
            }

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

    public function trash()
    {
        $users = Auth::onlyTrashed()->orderBy('deleted_at', 'desc')->get();
        return view('admin.users.trash', compact('users'));
    }

    public function restore($id)
    {
        $user = Auth::onlyTrashed()->findOrFail($id);
        $user->restore();
        // Restore child records if needed
        if ($user->status === 'siswa' && $user->siswa())
            $user->siswa()->restore();
        if ($user->status === 'guru' && $user->guru())
            $user->guru()->restore();

        return redirect()->route('admin.users.trash')->with('success', 'User berhasil dipulihkan!');
    }

    public function forceDelete($id)
    {
        $user = Auth::onlyTrashed()->findOrFail($id);

        // Force delete child records
        if ($user->status === 'siswa' && $user->siswa())
            $user->siswa()->forceDelete();
        if ($user->status === 'guru' && $user->guru())
            $user->guru()->forceDelete();

        $user->forceDelete();
        return redirect()->route('admin.users.trash')->with('success', 'User berhasil dihapus permanen!');
    }
}
