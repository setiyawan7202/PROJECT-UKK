<?php

namespace App\Http\Controllers;

use App\Models\Auth; // User model
use App\Models\Siswa;
use App\Models\Guru;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Mail\UserRegistered; // Kita reuse mail ini, tapi mungkin perlu update kontennya
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;

class ActivationController extends Controller
{
    public function showForm()
    {
        return view('auth.activation');
    }

    public function check(Request $request)
    {
        $request->validate([
            'status' => 'required|in:siswa,guru',
            'identifier' => 'required', // NISN or NIP
        ]);

        $user = Auth::where('status', $request->status)
            ->where('data_nip_nisn', $request->identifier)
            ->first();

        // Jika user tidak ditemukan
        if (!$user) {
            return back()->with('error', 'Data tidak ditemukan. Silakan hubungi administrator.');
        }

        // Jika user sudah aktif (active = 1)
        if ($user->is_active) {
            return back()->with('error', 'Akun dengan data ini sudah aktif. Silakan login.');
        }

        // Siapkan data untuk ditampilkan di Step 2
        $userData = [
            'id' => $user->id,
            'name' => $user->username, // Assuming username column stores fullname
            'status' => ucfirst($user->status),
            'identifier' => $user->data_nip_nisn,
        ];

        // Ambil info kelas jika siswa
        if ($user->status === 'siswa') {
            $siswa = Siswa::where('user_id', $user->id)->with('kelas')->first();
            if ($siswa && $siswa->kelas) {
                $userData['kelas'] = $siswa->kelas->nama_kelas;
            }
        }

        return back()->with('user_data', $userData);
    }

    public function submit(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'email' => 'required|email|unique:users,email,' . $request->user_id,
            'no_hp' => 'nullable|numeric',
        ]);

        $user = Auth::findOrFail($request->user_id);

        if ($user->is_active) {
            return redirect()->route('login')->with('error', 'Akun sudah aktif sebelumnya.');
        }

        DB::beginTransaction();
        try {
            // Generate random password
            $rawPassword = Str::random(10); // 8-10 chars

            // Update User
            $user->email = $request->email;
            $user->password = Hash::make($rawPassword);
            $user->is_active = true;
            $user->save();

            // Update Child Table (Siswa/Guru)
            if ($user->status === 'siswa') {
                $siswa = Siswa::where('user_id', $user->id)->first();
                if ($siswa) {
                    $siswa->email = $request->email;
                    $siswa->no_hp = $request->no_hp;
                    $siswa->save();
                }
            } elseif ($user->status === 'guru') {
                $guru = Guru::where('user_id', $user->id)->first();
                if ($guru) {
                    $guru->email = $request->email;
                    $guru->no_hp = $request->no_hp;
                    $guru->save();
                }
            }

            // Kirim Email dengan Password Baru
            $emailSent = false;
            try {
                Mail::to($user->email)->send(new UserRegistered($user, $rawPassword, $user->username));
                $emailSent = true;
            } catch (\Exception $e) {
                // Log error tapi jangan rollback transaksi user, user tetap aktif tapi email gagal
                \Illuminate\Support\Facades\Log::error('Activation Email Error: ' . $e->getMessage());
            }

            DB::commit();

            if ($emailSent) {
                return redirect()->route('login')->with('success', 'Informasi: Akun berhasil terverifikasi. Silakan cek email Anda untuk mendapatkan detail login.');
            } else {
                return redirect()->route('login')->with('success', 'Aktivasi berhasil, namun gagal mengirim email detail login. Silakan hubungi admin atau gunakan fitur Lupa Password.');
            }

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Terjadi kesalahan saat aktivasi: ' . $e->getMessage());
        }
    }
}
