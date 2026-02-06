<?php

namespace App\Http\Controllers;

use App\Models\Auth;
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
            'login' => 'required|string',
            'password' => 'required|string|min:8',
        ]);

        $login = $request->login;
        $user = Auth::where('email', $login)
            ->orWhere('data_nip_nisn', $login)
            ->first();

        if (!$user) {
            return back()->withErrors(['login' => 'Email, NISN, atau NIP tidak ditemukan.'])->withInput();
        }

        if (!Hash::check($request->password, $user->password)) {
            return back()->withErrors(['password' => 'Password salah.'])->withInput();
        }

        AuthFacade::login($user);
        $request->session()->regenerate();

        if ($user->role === 'admin' || $user->role === 'superadmin') {
            return redirect()->intended('/admin');
        }

        if ($user->role === 'petugas') {
            return redirect()->intended('/staff');
        }

        \App\Helpers\ActivityLogger::log('Login', 'User Login: ' . $user->email);

        return redirect()->intended('/main');
    }

    public function logout(Request $request)
    {
        \App\Helpers\ActivityLogger::log('Logout', 'User Logout');
        AuthFacade::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
