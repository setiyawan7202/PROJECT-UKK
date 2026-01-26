<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth as AuthFacade;
use Illuminate\Support\Facades\Hash;
use App\Models\Auth;

class ProfilController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function index()
    {
        return view('main.profil.index', [
            'user' => AuthFacade::user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(Request $request)
    {
        $user = AuthFacade::user();

        $request->validate([
            'password' => 'required|string|min:8|confirmed',
        ]);

        /** @var \App\Models\Auth $user */
        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return back()->with('success', 'Kata sandi berhasil diperbarui.');
    }
}
