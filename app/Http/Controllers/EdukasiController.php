<?php

namespace App\Http\Controllers;

use App\Models\Edukasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EdukasiController extends Controller
{
    public function index()
    {
        $edukasis = Edukasi::orderBy('id_edukasi', 'asc')->get();
        return view('edukasi.index', compact('edukasis'));
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
            'foto' => $fotoPath,
        ]);

        return redirect('/edukasi')->with('success', 'Edukasi berhasil ditambahkan');
    }

    public function edit($id)
    {
        $edukasi = Edukasi::where('id_edukasi', $id)->firstOrFail();
        return view('edukasi.edit', compact('edukasi'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'isi' => 'required|string',
            'foto' => 'nullable|image|max:2048',
        ]);

        $edukasi = Edukasi::where('id_edukasi', $id)->firstOrFail();

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

    public function destroy($id)
    {
        $edukasi = Edukasi::where('id_edukasi', $id)->firstOrFail();

        if ($edukasi->foto && Storage::disk('public')->exists($edukasi->foto)) {
            Storage::disk('public')->delete($edukasi->foto);
        }

        $edukasi->delete();

        return redirect('/edukasi')->with('success', 'Edukasi berhasil dihapus.');
    }
}
