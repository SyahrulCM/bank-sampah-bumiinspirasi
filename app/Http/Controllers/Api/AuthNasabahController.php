<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\Registrasi;

class AuthNasabahController extends Controller
{
    // Fungsi Login API
    public function login(Request $request)
    {
        $request->validate([
            'nomer_induk_nasabah' => 'required',
            'password' => 'required',
        ]);

        $user = Registrasi::where('nomer_induk_nasabah', $request->nomer_induk_nasabah)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Nomer induk atau password salah'], 401);
        }

        $token = $user->createToken('nasabah-token')->plainTextToken;

        return response()->json([
            'message' => 'Login berhasil',
            'token' => $token,
            'nasabah' => $user,
        ]);
    }

    // Fungsi Logout API
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logout berhasil']);
    }

    // (Opsional) Fungsi Register Nasabah via API
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_lengkap' => 'required',
            'alamat' => 'required',
            'nomer_telepon' => 'required',
            'nomer_induk_nasabah' => 'required|unique:registrasis',
            'password' => 'required|min:6',
            'tanggal' => 'required|date',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $nasabah = Registrasi::create([
            'nama_lengkap' => $request->nama_lengkap,
            'alamat' => $request->alamat,
            'nomer_telepon' => $request->nomer_telepon,
            'nomer_induk_nasabah' => $request->nomer_induk_nasabah,
            'password' => bcrypt($request->password),
            'tanggal' => $request->tanggal,
        ]);

        $token = $nasabah->createToken('nasabah-token')->plainTextToken;

        return response()->json([
            'message' => 'Registrasi berhasil',
            'token' => $token,
            'nasabah' => $nasabah
        ]);
    }
}

