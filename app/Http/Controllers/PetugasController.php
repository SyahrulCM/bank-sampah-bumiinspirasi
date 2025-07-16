<?php

namespace App\Http\Controllers;

use App\Models\Petugas;
use App\Models\Role;
use Illuminate\Http\Request;

class PetugasController extends Controller
{
    // Menampilkan form tambah petugas
    public function petugas()
    {
        $roles = Role::all();
        return view('layout.petugas', compact('roles'));
    }

    // Menampilkan semua petugas
    public function getPetugas()
    {
        // Gunakan eager loading agar role bisa terbaca di Blade
        $data = Petugas::with('role')->get(); 
        $roles = Role::all();

        return view('layout.petugas', compact('data', 'roles'));
    }

    // Menyimpan data petugas
    public function inputPetugas(Request $request)
    {
        $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'nama_pengguna' => 'required|string|min:8|max:255|unique:petugas,nama_pengguna',
            'password' => 'required|string|min:8',
            'id_role' => 'required|exists:roles,id_role',
        ], [
            'nama_pengguna.unique' => 'Nama pengguna sudah digunakan.',
            'nama_pengguna.min' => 'Nama pengguna minimal 8 karakter.',
            'password.min' => 'Password minimal harus 8 karakter.',
            'id_role.required' => 'Role harus dipilih.',
        ]);
    
        Petugas::create([
            'nama_lengkap' => $request->nama_lengkap,
            'nama_pengguna' => $request->nama_pengguna,
            'password' => bcrypt($request->password),
            'id_role' => $request->id_role,
        ]);
    
        return redirect()->back()->with('sukses', 'Petugas berhasil ditambahkan.');
    }
}