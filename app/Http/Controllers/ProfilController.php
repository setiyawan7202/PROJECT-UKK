<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth as AuthFacade;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Models\Auth;
use App\Mail\UserRegistered;

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
            'password' => 'nullable|string|min:8|confirmed',
            'no_hp' => 'nullable|string|max:15',
        ]);

        /** @var \App\Models\Auth $user */
        $passwordChanged = false;
        $newPassword = null;

        if ($request->filled('password')) {
            $user->update([
                'password' => Hash::make($request->password),
            ]);
            $passwordChanged = true;
            $newPassword = $request->password;
        }

        if ($user->status === 'siswa' && $user->siswa) {
            $user->siswa()->update(['no_hp' => $request->no_hp]);
        } elseif ($user->status === 'guru' && $user->guru) {
            $user->guru()->update(['no_hp' => $request->no_hp]);
        }

        // Send email notification if password was changed
        if ($passwordChanged) {
            try {
                Mail::to($user->email)->send(new UserRegistered($user, $newPassword, $user->username, true));
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('SMTP Error (Profile Update): ' . $e->getMessage());
            }
        }

        return back()->with('success', 'Profil berhasil diperbarui.');
    }
}
