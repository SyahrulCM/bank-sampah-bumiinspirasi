<?php

namespace App\Http\Controllers;

use App\Models\Edukasi;
use App\Http\Controllers\Controller; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EdukasiController extends Controller
{
    public function index()
    {
        $edukasis = Edukasi::orderBy('id_edukasi', 'asc')->get();
        return view('edukasi.index', compact('edukasis'));
    }

    public function create()
    {
        return view('edukasi.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'isi' => 'required|string',
            'foto' => 'nullable|image|max:2048',
        ]);

        $fotoPath = null;
        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('edukasi', 'public');
        }

        Edukasi::create([
            'judul' => $request->judul,
            'isi' => $request->isi,
            'foto' => $fotoPath
        ]);

        return redirect('/edukasi')->with('success', 'Edukasi berhasil ditambahkan');
    }

    public function edit(Edukasi $edukasi)
    {
        return view('edukasi.edit', compact('edukasi'));
    }

    public function update(Request $request, Edukasi $edukasi)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'isi' => 'required|string',
            'foto' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('foto')) {
            if ($edukasi->foto && Storage::disk('public')->exists($edukasi->foto)) {
                Storage::disk('public')->delete($edukasi->foto);
            }

            $fotoPath = $request->file('foto')->store('edukasi', 'public');
            $edukasi->foto = $fotoPath;
        }

        $edukasi->judul = $request->judul;
        $edukasi->isi = $request->isi;
        $edukasi->save();

        return redirect('/edukasi')->with('success', 'Edukasi berhasil diupdate');
    }

    public function destroy(Edukasi $edukasi)
    {
        if ($edukasi->foto && Storage::disk('public')->exists($edukasi->foto)) {
            Storage::disk('public')->delete($edukasi->foto);
        }

        $edukasi->delete();

        return redirect('/edukasi')->with('success', 'Edukasi berhasil dihapus.');
    }
}
