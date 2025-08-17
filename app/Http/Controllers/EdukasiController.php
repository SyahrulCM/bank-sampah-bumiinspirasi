<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Edukasi;
use Illuminate\Support\Facades\File;

class EdukasiController extends Controller
{
    public function index()
    {
        $edukasis = Edukasi::orderBy('id_edukasi', 'asc')->get();
        return view('layout.edukasi', compact('edukasis'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'isi' => 'required|string',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $fotoPath = null;

        if ($request->hasFile('foto')) {
            $foto = $request->file('foto');
            $namaFoto = time() . '_' . $foto->getClientOriginalName();

            // Gunakan path absolut menuju public_html
            $uploadPath = $_SERVER['DOCUMENT_ROOT'] . '/uploads/edukasi';
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0755, true); // buat folder jika belum ada
            }

            $foto->move($uploadPath, $namaFoto);
            $fotoPath = 'uploads/edukasi/' . $namaFoto;
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
        return view('layout.edukasi_edit', compact('edukasi'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'isi' => 'required|string',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $edukasi = Edukasi::where('id_edukasi', $id)->firstOrFail();

        // Update foto jika ada file baru
        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $filename = time() . '_' . $file->getClientOriginalName();

            // Path absolut ke public_html
            $uploadPath = $_SERVER['DOCUMENT_ROOT'] . '/uploads/edukasi';
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }

            $file->move($uploadPath, $filename);
            $edukasi['foto'] = 'uploads/edukasi/' . $filename;
        }

        $edukasi->judul = $request->judul;
        $edukasi->isi = $request->isi;
        $edukasi->save();

        return redirect('/edukasi')->with('success', 'Edukasi berhasil diupdate');
    }

    public function destroy($id)
    {
        $edukasi = Edukasi::where('id_edukasi', $id)->firstOrFail();

        // Hapus foto dari folder jika ada
        if ($edukasi->foto && File::exists(public_path($edukasi->foto))) {
            File::delete(public_path($edukasi->foto));
        }

        $edukasi->delete();

        return redirect('/edukasi')->with('success', 'Edukasi berhasil dihapus.');
    }
}
