<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Storage;

use App\Models\Registrasi;

class AuthNasabahController extends Controller
{

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


        public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_lengkap' => 'required',
            'alamat' => 'required',
            'nomer_telepon' => 'required',
            'nomer_induk_nasabah' => 'required|unique:registrasis',
            'password' => 'required|min:6',
            'tanggal' => 'required|date',
            'foto' => 'nullable|image|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $fotoPath = null;
        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('foto_nasabah'), $filename);
            $fotoPath = 'foto_nasabah/' . $filename;
        }

        $nasabah = Registrasi::create([
            'nama_lengkap' => $request->nama_lengkap,
            'alamat' => $request->alamat,
            'nomer_telepon' => $request->nomer_telepon,
            'nomer_induk_nasabah' => $request->nomer_induk_nasabah,
            'password' => bcrypt($request->password),
            'tanggal' => $request->tanggal,
            'foto' => $fotoPath, // simpan path foto
        ]);

        $token = $nasabah->createToken('nasabah-token')->plainTextToken;

        // Tambahkan URL lengkap pada foto
        $nasabah->foto = $nasabah->foto ? asset($nasabah->foto) : null;

        return response()->json([
            'message' => 'Registrasi berhasil',
            'token' => $token,
            'nasabah' => $nasabah
        ]);
    }

    public function editProfile(Request $request)
    {
        $user = $request->user();
        $validator = Validator::make($request->all(), [
            'nama_lengkap' => 'sometimes',
            'alamat' => 'sometimes',
            'nomer_telepon' => 'sometimes',
            'tanggal' => 'sometimes|date',
            'password' => 'nullable|min:6',
            'foto' => 'nullable|image|max:2048',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        if ($request->has('nama_lengkap')) $user->nama_lengkap = $request->nama_lengkap;
        if ($request->has('alamat')) $user->alamat = $request->alamat;
        if ($request->has('nomer_telepon')) $user->nomer_telepon = $request->nomer_telepon;
        if ($request->has('tanggal')) $user->tanggal = $request->tanggal;
        if ($request->filled('password')) $user->password = bcrypt($request->password);

        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('foto_nasabah'), $filename);
            $user->foto = 'foto_nasabah/' . $filename;
        }

        $user->save();
        $user->foto = $user->foto ? asset($user->foto) : null;

        return response()->json([
            'message' => 'Profil berhasil diperbarui',
            'nasabah' => $user
        ]);
    }

}
