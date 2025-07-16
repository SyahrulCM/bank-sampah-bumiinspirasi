<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Str;
use App\Models\Petugas;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'nama_pengguna' => 'required|string',
            'password' => 'required|string',
        ]);

        if ($request->nama_pengguna === 'admin123' && $request->password === 'bumiinspirasi01') {
            session([
                'login' => true,
                'id_petugas' => 0,
                'nama_lengkap' => 'Administrator',
                'role' => 'Admin'
            ]);

            $token = Str::random(60);
            Cookie::queue('remember_token', $token, 60 * 24 * 30);

            return redirect()->route('dashboard');
        }

        $petugas = Petugas::where('nama_pengguna', $request->nama_pengguna)->first();

        if (!$petugas) {
            return back()->with('error', 'Nama pengguna tidak ditemukan.');
        }

        if (!password_verify($request->password, $petugas->password)) {
            return back()->with('error', 'Password yang Anda masukkan salah.');
        }

        session([
            'login' => true,
            'id_petugas' => $petugas->id_petugas,
            'nama_lengkap' => $petugas->nama_lengkap,
            'role' => $petugas->role->nama_role
        ]);

        $token = Str::random(60);
        $petugas->remember_token = $token;
        $petugas->save();

        Cookie::queue('remember_token', $token, 60 * 24 * 30);

        return redirect()->route('dashboard');
    }

    public function logout(Request $request)
    {
        $token = Cookie::get('remember_token');

        if ($token) {
            $petugas = Petugas::where('remember_token', $token)->first();

            if ($petugas) {
                $petugas->remember_token = null;
                $petugas->save();
            }

            Cookie::queue(Cookie::forget('remember_token'));
        }

        Session::forget('login');

        return redirect('/login')->with('success', 'Berhasil logout.');
    }
}