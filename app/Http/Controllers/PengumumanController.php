<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pengumuman;

class PengumumanController extends Controller
{
    // Tampilkan daftar pengumuman
    public function index()
    {
        $data = Pengumuman::all();
        return view('layout.pengumuman', compact('data'));
    }

    // Simpan pengumuman baru
    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'isi' => 'required|string',
            'status' => 'required|in:aktif,nonaktif',
        ]);
        Pengumuman::create($request->only(['judul','isi','status']));
        return redirect()->route('pengumuman.index')->with('sukses', 'Pengumuman berhasil ditambahkan.');
    }

    // Tampilkan form edit pengumuman
    public function edit($id_pengumuman)
    {
        $pengumuman = Pengumuman::findOrFail($id_pengumuman);
        return view('layout.pengumuman_edit', compact('pengumuman'));
    }

    // Update pengumuman
    public function update(Request $request, $id_pengumuman)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'isi' => 'required|string',
            'status' => 'required|in:aktif,nonaktif',
        ]);
        Pengumuman::where('id_pengumuman', $id_pengumuman)
            ->update($request->only(['judul','isi','status']));
        return redirect()->route('pengumuman.index')->with('sukses', 'Pengumuman berhasil diperbarui.');
    }

    // Hapus pengumuman
    public function destroy($id_pengumuman)
    {
        $pengumuman = Pengumuman::findOrFail($id_pengumuman);
        $pengumuman->delete();
        return redirect()->route('pengumuman.index')->with('sukses', 'Pengumuman berhasil dihapus.');
    }
}
