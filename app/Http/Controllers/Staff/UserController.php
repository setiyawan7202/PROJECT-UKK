<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Auth;
use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\Guru;
use App\Mail\UserRegistered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $statusFilter = $request->get('status');
        $kelasFilter = $request->get('kelas');
        $search = $request->get('search');

        // Staff can only manage 'pengguna' (Siswa/Guru)
        $query = Auth::with('siswa.kelas')->where('role', 'pengguna');

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
                    ->orWhere('username', 'like', "%{$search}%")
                    ->orWhere('data_nip_nisn', 'like', "%{$search}%")
                    ->orWhereHas('siswa', function ($sq) use ($search) {
                        $sq->where('username', 'like', "%{$search}%");
                    })
                    ->orWhereHas('guru', function ($gq) use ($search) {
                        $gq->where('username', 'like', "%{$search}%");
                    });
            });
        }

        $users = $query->orderBy('created_at', 'asc')->get();
        $kelasList = Kelas::orderBy('tingkat')->orderBy('nama_kelas')->get();

        return view('staff.users.index', compact('users', 'statusFilter', 'kelasFilter', 'search', 'kelasList'));
    }

    public function create()
    {
        $kelasList = Kelas::orderBy('tingkat')->orderBy('nama_kelas')->get();
        return view('staff.users.create', compact('kelasList'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'nama_lengkap' => 'required|string|max:255',
            'status' => 'nullable|in:siswa,guru',
            'nisn' => 'nullable|required_if:status,siswa|unique:siswa,nisn|numeric',
            'kelas_id' => 'nullable|required_if:status,siswa|exists:kelas,id',
            'nip' => 'nullable|required_if:status,guru|unique:guru,nip|numeric',
            'no_hp' => 'nullable|string|max:15',
        ]);

        // Force role to pengguna
        $role = 'pengguna';

        DB::beginTransaction();
        try {
            $username = null;
            if ($request->status === 'siswa') {
                $username = $request->nisn;
            } elseif ($request->status === 'guru') {
                $username = $request->nip;
            }

            $user = Auth::create([
                'username' => $request->nama_lengkap,
                'email' => $request->email,
                'data_nip_nisn' => $username,
                'password' => Hash::make($request->password),
                'role' => $role,
                'status' => $request->status,
                'permissions' => null, // Staff cannot assign permissions
            ]);

            if ($request->status === 'siswa') {
                Siswa::create([
                    'user_id' => $user->id,
                    'nisn' => $request->nisn,
                    'username' => $request->nama_lengkap,
                    'email' => $request->email,
                    'kelas_id' => $request->kelas_id,
                    'no_hp' => $request->no_hp,
                ]);
            } elseif ($request->status === 'guru') {
                Guru::create([
                    'user_id' => $user->id,
                    'nip' => $request->nip,
                    'username' => $request->nama_lengkap,
                    'email' => $request->email,
                    'no_hp' => $request->no_hp,
                ]);
            }

            try {
                Mail::to($user->email)->send(new UserRegistered($user, $request->password, $request->nama_lengkap));
            } catch (\Exception $e) {
                // Log error
            }

            DB::commit();

            return redirect()->route('staff.users.index')
                ->with('success', 'User berhasil ditambahkan!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Gagal menambahkan user: ' . $e->getMessage()])->withInput();
        }
    }

    public function edit($id)
    {
        $user = Auth::findOrFail($id);

        if ($user->role !== 'pengguna') {
            return redirect()->route('staff.users.index')->withErrors(['error' => 'Anda hanya dapat mengedit user Pengguna.']);
        }

        $kelasList = Kelas::orderBy('tingkat')->orderBy('nama_kelas')->get();

        return view('staff.users.edit', compact('user', 'kelasList'));
    }

    public function update(Request $request, $id)
    {
        $user = Auth::findOrFail($id);

        if ($user->role !== 'pengguna') {
            return back()->withErrors(['error' => 'Anda hanya dapat mengubah user Pengguna.']);
        }

        $request->validate([
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($user->id)],
            'password' => 'nullable|string|min:8',
            'nama_lengkap' => 'required|string|max:255',
            'status' => 'nullable|in:siswa,guru',
            'kelas_id' => 'nullable|required_if:status,siswa|exists:kelas,id',
            'no_hp' => 'nullable|string|max:15',
        ]);

        try {
            $userData = [
                'username' => $request->nama_lengkap,
                'email' => $request->email,
                // Role remains unchanged or forced to pengguna
                'status' => $request->status,
            ];

            if ($request->status === 'siswa') {
                $user->siswa()->updateOrCreate(
                    ['user_id' => $user->id],
                    [
                        'nisn' => $request->nisn,
                        'username' => $request->nama_lengkap,
                        'email' => $request->email,
                        'kelas_id' => $request->kelas_id,
                        'no_hp' => $request->no_hp
                    ]
                );
                $userData['data_nip_nisn'] = $request->nisn;

            } elseif ($request->status === 'guru') {
                $user->guru()->updateOrCreate(
                    ['user_id' => $user->id],
                    [
                        'nip' => $request->nip,
                        'username' => $request->nama_lengkap,
                        'email' => $request->email,
                        'no_hp' => $request->no_hp
                    ]
                );
                $userData['data_nip_nisn'] = $request->nip;
            }

            if ($request->filled('password')) {
                $userData['password'] = Hash::make($request->password);
            }

            $user->update($userData);

            return redirect()->route('staff.users.index')
                ->with('success', 'User berhasil diperbarui!');

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Gagal memperbarui user: ' . $e->getMessage()])->withInput();
        }
    }

    public function destroy($id)
    {
        $user = Auth::findOrFail($id);

        if ($user->role !== 'pengguna') {
            return back()->withErrors(['error' => 'Anda hanya dapat menghapus user Pengguna.']);
        }

        $user->delete();

        return redirect()->route('staff.users.index')
            ->with('success', 'User berhasil dihapus!');
    }
}
