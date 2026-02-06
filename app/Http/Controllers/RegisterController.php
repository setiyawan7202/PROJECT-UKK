<?php

namespace App\Http\Controllers;

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

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        $kelasList = Kelas::orderBy('tingkat')->orderBy('nama_kelas')->get();
        return view('auth.register', compact('kelasList'));
    }

    public function register(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'nama_lengkap' => 'required|string|max:255',
            'status' => 'required|in:siswa,guru',
            'nisn' => 'nullable|required_if:status,siswa|unique:siswa,nisn|numeric',
            'kelas_id' => 'nullable|required_if:status,siswa|exists:kelas,id',
            'nip' => 'nullable|required_if:status,guru|unique:guru,nip|numeric',
            'no_hp' => 'nullable|string|max:15',
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
                'username' => $request->nama_lengkap,
                'email' => $request->email,
                'data_nip_nisn' => $username,
                'password' => Hash::make($request->password),
                'role' => 'pengguna',
                'status' => $request->status,
                'permissions' => null,
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

            // Send Email Notification
            try {
                Mail::to($user->email)->send(new UserRegistered($user, $request->password, $request->nama_lengkap));
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('Registration Email Error: ' . $e->getMessage());
            }

            DB::commit();

            return redirect()->route('login')
                ->with('success', 'Pendaftaran berhasil! Silakan cek email Anda dan login dengan akun yang telah didaftarkan.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Gagal mendaftar: ' . $e->getMessage()])->withInput();
        }
    }
}
