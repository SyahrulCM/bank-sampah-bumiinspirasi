<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\URL;
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
            'nasabah' => [
                'id_registrasi' => $user->id_registrasi,
                'nama_lengkap' => $user->nama_lengkap,
                'alamat' => $user->alamat,
                'nomer_telepon' => $user->nomer_telepon,
                'nomer_induk_nasabah' => $user->nomer_induk_nasabah,
                'tanggal' => $user->tanggal,
                'foto' => $user->foto ? URL::to($user->foto) : null,
                'created_at' => $user->created_at,
                'updated_at' => $user->updated_at,
            ]
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
            'nasabah' => [
                'id_registrasi' => $nasabah->id_registrasi,
                'nama_lengkap' => $nasabah->nama_lengkap,
                'alamat' => $nasabah->alamat,
                'nomer_telepon' => $nasabah->nomer_telepon,
                'nomer_induk_nasabah' => $nasabah->nomer_induk_nasabah,
                'tanggal' => $nasabah->tanggal,
                'foto' => $nasabah->foto ? URL::to($nasabah->foto) : null,
                'created_at' => $nasabah->created_at,
                'updated_at' => $nasabah->updated_at,
            ]
        ]);
    }
}
