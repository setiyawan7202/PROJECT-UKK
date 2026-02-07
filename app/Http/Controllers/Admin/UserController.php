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
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel; // Add this
use App\Imports\SiswaImport; // Add this
use App\Imports\GuruImport; // Add this
use App\Exports\UserTemplateExport; // Add this

class UserController extends Controller
{
    public function index(Request $request)
    {
        $filter = $request->get('filter');
        $statusFilter = $request->get('status');
        $kelasFilter = $request->get('kelas');
        $search = $request->get('search');

        $query = Auth::with('siswa.kelas');

        if ($filter === 'superadmin') {
            $query->where('role', 'superadmin');
        } elseif ($filter === 'admin') {
            $query->where('role', 'admin');
        } elseif ($filter === 'petugas') {
            $query->where('role', 'petugas');
        } elseif ($filter === 'kepala_lab') {
            $query->where('role', 'kepala_lab');
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

        return view('admin.users.index', compact('users', 'filter', 'statusFilter', 'kelasFilter', 'search', 'kelasList'));
    }

    public function create()
    {
        $kelasList = Kelas::orderBy('tingkat')->orderBy('nama_kelas')->get();
        return view('admin.users.create', compact('kelasList'));
    }

    public function store(Request $request)
    {
        $currentUserRole = auth()->user()->role;
        $activateNow = $request->has('activate_now');

        // Determine allowed roles based on current user's role
        if ($currentUserRole === 'superadmin') {
            $allowedRoles = 'required|in:superadmin,admin,petugas,kepala_lab,pengguna';
        } else {
            // Admin can only create petugas, kepala_lab and pengguna
            $allowedRoles = 'required|in:petugas,kepala_lab,pengguna';
        }

        // Build validation rules based on activation mode
        $rules = [
            'role' => $allowedRoles,
            'nama_lengkap' => 'required|string|max:255',
            'status' => 'nullable|in:siswa,guru',
            // Siswa validation
            'nisn' => 'nullable|required_if:status,siswa|unique:siswa,nisn|numeric',
            'kelas_id' => 'nullable|required_if:status,siswa|exists:kelas,id',
            // Guru validation
            'nip' => 'nullable|required_if:status,guru|unique:guru,nip|numeric',
            'no_hp' => 'nullable|string|max:15',
            'permissions' => 'nullable|array',
            'permissions.*' => 'string',
        ];

        // Email and password required only if activating now
        if ($activateNow) {
            $rules['email'] = 'required|email|unique:users,email';
            $rules['password'] = 'required|string|min:8';
        }

        $request->validate($rules);

        DB::beginTransaction();
        try {
            $nipNisn = null;
            if ($request->status === 'siswa') {
                $nipNisn = $request->nisn;
            } elseif ($request->status === 'guru') {
                $nipNisn = $request->nip;
            }

            // Determine email and password
            if ($activateNow) {
                $email = $request->email;
                $password = $request->password;
                $isActive = true;
            } else {
                // Inactive user: Email = NULL, Password = Random (must activate)
                $email = null;
                $password = Str::random(32);
                $isActive = false;
            }

            $user = Auth::create([
                'username' => $request->nama_lengkap,
                'email' => $email,
                'data_nip_nisn' => $nipNisn,
                'password' => Hash::make($password),
                'role' => $request->role,
                'status' => $request->status,
                'is_active' => $isActive,
                'permissions' => $request->role === 'petugas' ? $request->permissions : null,
            ]);

            // Create related records
            if ($request->status === 'siswa') {
                Siswa::create([
                    'user_id' => $user->id,
                    'nisn' => $request->nisn,
                    'username' => $request->nama_lengkap,
                    'email' => $activateNow ? $request->email : null,
                    'kelas_id' => $request->kelas_id,
                    'no_hp' => $request->no_hp,
                ]);
            } elseif ($request->status === 'guru') {
                Guru::create([
                    'user_id' => $user->id,
                    'nip' => $request->nip,
                    'username' => $request->nama_lengkap,
                    'email' => $activateNow ? $request->email : null,
                    'no_hp' => $request->no_hp,
                ]);
            }

            // Send Email only if activating now
            if ($activateNow) {
                try {
                    Mail::to($user->email)->send(new UserRegistered($user, $password, $request->nama_lengkap));
                } catch (\Exception $e) {
                    \Illuminate\Support\Facades\Log::error('SMTP Error: ' . $e->getMessage());
                }
            }

            DB::commit();

            $message = $activateNow
                ? 'User berhasil ditambahkan dan notifikasi email dikirim!'
                : 'User berhasil ditambahkan. User harus aktivasi sendiri melalui halaman Aktivasi.';

            return redirect()->route('admin.users.index')->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Gagal menambahkan user: ' . $e->getMessage()])->withInput();
        }
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
            'type' => 'required|in:siswa,guru',
        ]);

        try {
            if ($request->type === 'siswa') {
                Excel::import(new SiswaImport, $request->file('file'));
            } elseif ($request->type === 'guru') {
                Excel::import(new GuruImport, $request->file('file'));
            }

            return redirect()->back()->with('success', 'Data berhasil diimport!');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Gagal import data: ' . $e->getMessage()]);
        }
    }

    public function downloadTemplate()
    {
        return Excel::download(new UserTemplateExport, 'template_import_user.xlsx');
    }

    public function edit($id)
    {
        $user = Auth::findOrFail($id);

        if ($user->id === auth()->id()) {
            // Allow user to edit their own profile (or redirect to profile edit if separate)
            // For now, let's allow it, but role changing is disabled in view for non-superadmin
        } else {
            // Restriction: Admin cannot edit superadmin or other admins
            $currentUserRole = auth()->user()->role;
            if ($currentUserRole !== 'superadmin' && in_array($user->role, ['superadmin', 'admin'])) {
                return back()->withErrors(['error' => 'Hanya Super Admin yang dapat mengubah akun Admin atau Super Admin!']);
            }
        }

        $kelasList = Kelas::orderBy('tingkat')->orderBy('nama_kelas')->get();

        return view('admin.users.edit', compact('user', 'kelasList'));
    }

    public function update(Request $request, $id)
    {
        $user = Auth::findOrFail($id);

        // Restriction: Admin cannot edit superadmin or other admins
        $currentUserRole = auth()->user()->role;
        if ($currentUserRole !== 'superadmin' && in_array($user->role, ['superadmin', 'admin'])) {
            return back()->withErrors(['error' => 'Hanya Super Admin yang dapat mengubah akun Admin atau Super Admin!']);
        }

        // Validate password only if it is filled
        $passwordRule = $request->filled('password') ? 'string|min:8' : 'nullable';

        $rules = [
            'email' => ['nullable', 'email', Rule::unique('users', 'email')->ignore($user->id)],
            'password' => $passwordRule,
            'role' => 'required|in:superadmin,admin,petugas,kepala_lab,pengguna',
            'nama_lengkap' => 'required|string|max:255',
            'status' => 'nullable|in:siswa,guru',
            'kelas_id' => 'nullable|exists:kelas,id',
            'no_hp' => 'nullable|string|max:15',
            'permissions' => 'nullable|array',
            'permissions.*' => 'string',
        ];

        // If user is not superadmin, they cannot set role to superadmin/admin
        if ($currentUserRole !== 'superadmin') {
            $rules['role'] = 'required|in:petugas,kepala_lab,pengguna';
        }

        $request->validate($rules);

        try {
            // Determine activation status based on email
            $oldEmail = $user->email;
            $newEmail = $request->email;
            $wasActive = $user->is_active;

            // Check if this is an activation (email added) or deactivation (email removed)
            $isActivating = false;
            $isDeactivating = false;

            if (empty($newEmail)) {
                // Email is empty - deactivate
                $isActive = false;
                $isDeactivating = $wasActive;
                $emailToSave = null;
            } else {
                // Email is valid - activate
                $isActive = true;
                $isActivating = !$wasActive;
                $emailToSave = $newEmail;
            }

            $userData = [
                'username' => $request->nama_lengkap,
                'email' => $emailToSave,
                'role' => $request->role,
                'status' => $request->status,
                'is_active' => $isActive,
                'permissions' => $request->role === 'petugas' ? $request->permissions : null,
            ];

            // Update Username (Nama Lengkap) in Child Tables
            if ($request->status === 'siswa') {
                $user->siswa()->updateOrCreate(
                    ['user_id' => $user->id],
                    [
                        'nisn' => $request->nisn,
                        'username' => $request->nama_lengkap,
                        'email' => $emailToSave,
                        'kelas_id' => $request->kelas_id,
                        'no_hp' => $request->no_hp
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
                        'email' => $emailToSave,
                        'no_hp' => $request->no_hp
                    ]
                );
                // Also update NIP map
                $userData['data_nip_nisn'] = $request->nip;
            }

            // Handle password
            $newPassword = null;
            if ($request->filled('password')) {
                $userData['password'] = Hash::make($request->password);
                $newPassword = $request->password;
            } elseif ($isActivating) {
                // Generate new password when activating account
                $newPassword = Str::random(10);
                $userData['password'] = Hash::make($newPassword);
            }

            $user->update($userData);

            // Send Email Notification only if account is active
            if ($isActive && !empty($emailToSave)) {
                try {
                    $isUpdate = !$isActivating; // New activation = welcome, Update = info update
                    Mail::to($emailToSave)->send(new UserRegistered($user->fresh(), $newPassword, $user->username, $isUpdate));
                } catch (\Exception $e) {
                    \Illuminate\Support\Facades\Log::error('SMTP Error (Update): ' . $e->getMessage());
                }
            }

            // Build success message
            if ($isActivating) {
                $message = 'User berhasil diaktifkan! Detail akun telah dikirim ke email.';
            } elseif ($isDeactivating) {
                $message = 'User berhasil dinonaktifkan. User harus aktivasi ulang.';
            } else {
                $message = 'User berhasil diperbarui!';
            }

            return redirect()->route('admin.users.index')->with('success', $message);

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

        // Admin cannot delete superadmin or admin users
        $currentUserRole = auth()->user()->role;
        if ($currentUserRole !== 'superadmin' && in_array($user->role, ['superadmin', 'admin'])) {
            return back()->withErrors(['error' => 'Hanya Super Admin yang dapat menghapus akun Admin atau Super Admin!']);
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
        // Restriction: Only Super Admin can force delete
        if (auth()->user()->role !== 'superadmin') {
            return back()->withErrors(['error' => 'Akses Ditolak: Hanya Super Admin yang dapat menghapus data secara permanen!']);
        }

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
