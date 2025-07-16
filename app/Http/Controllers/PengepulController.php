<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pengepul;

class PengepulController extends Controller
{
    // Menampilkan semua pengepul
    public function index()
    {
        $pengepuls = Pengepul::all();
        return view('layout.pengepul', compact('pengepuls'));
    }

    // Menyimpan pengepul baru dari modal
    public function store(Request $request)
    {
        $request->validate([
            'nama_pengepul' => 'required|string|max:255',
            'kontak' => 'required|string|max:255',
            'alamat' => 'required|string|max:255',
        ]);

        Pengepul::create([
            'nama_pengepul' => $request->nama_pengepul,
            'kontak' => $request->kontak,
            'alamat' => $request->alamat,
        ]);

        return redirect()->route('pengepul.index')->with('sukses', 'Pengepul berhasil ditambahkan.');
    }

    // Menampilkan form edit (boleh dibuat modal juga kalau mau)
    public function edit($id)
    {
        $pengepul = Pengepul::findOrFail($id);
        return view('pengepul.edit', compact('pengepul'));
    }

    // Memperbarui data pengepul
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_pengepul' => 'required|string|max:255',
            'kontak' => 'required|string|max:255',
            'alamat' => 'required|string|max:255',
        ]);

        $pengepul = Pengepul::findOrFail($id);
        $pengepul->update([
            'nama_pengepul' => $request->nama_pengepul,
            'kontak' => $request->kontak,
            'alamat' => $request->alamat,
        ]);

        return redirect()->route('pengepul.index')->with('sukses', 'Pengepul berhasil diperbarui.');
    }

    // Menghapus pengepul
    public function destroy($id)
    {
        $pengepul = Pengepul::findOrFail($id);
        $pengepul->delete();

        return redirect()->route('pengepul.index')->with('sukses', 'Pengepul berhasil dihapus.');
    }
}