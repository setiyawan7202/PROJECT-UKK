<?php

namespace App\Http\Controllers;

use App\Models\Auth;
use App\Models\Guru;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth as AuthFacade;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'identifier' => 'required|string|min:8|max:18',
            'password' => 'required|string|min:8',
        ]);

        $user = Auth::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'Email tidak ditemukan.'])->withInput();
        }

        if (!Hash::check($request->password, $user->password)) {
            return back()->withErrors(['password' => 'Password salah.'])->withInput();
        }

        if ($user->role === 'admin') {
            AuthFacade::login($user);
            $request->session()->regenerate();
            return redirect()->intended('/admin');
        }

        if ($user->role === 'petugas') {
            AuthFacade::login($user);
            $request->session()->regenerate();
            return redirect()->intended('/staff');
        }

        if ($user->role === 'pengguna') {
            $identifier = $request->identifier;

            $guru = Guru::where('user_id', $user->id)->where('nip', $identifier)->first();
            if ($guru) {
                AuthFacade::login($user);
                $request->session()->regenerate();
                return redirect()->intended('/main');
            }

            $siswa = Siswa::where('user_id', $user->id)->where('nisn', $identifier)->first();
            if ($siswa) {
                AuthFacade::login($user);
                $request->session()->regenerate();
                return redirect()->intended('/main');
            }

            return back()->withErrors(['identifier' => 'NISN/NIP tidak cocok dengan akun ini.'])->withInput();
        }

        return back()->withErrors(['email' => 'Terjadi kesalahan. Silakan coba lagi.'])->withInput();
    }

    public function logout(Request $request)
    {
        AuthFacade::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
