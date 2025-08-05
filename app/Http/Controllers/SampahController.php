<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sampah;
use PhpOffice\PhpSpreadsheet\IOFactory;
use App\Imports\RegistrasiImport;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class SampahController extends Controller
{
    public function sampah()
    {
        return $this->getSampah();
    }

    public function getSampah()
    {
        $data = Sampah::all();

        foreach ($data as $d) {
            $d->harga_pengepul_rp = 'Rp ' . number_format($d->harga_pengepul, 0, ',', '.');
            $d->harga_ditabung_rp = 'Rp ' . number_format($d->harga_ditabung ?? 0, 0, ',', '.');
        }

        return view('layout.sampah', compact('data'));
    }

    public function inputSampah(Request $request)
    {
        $request->validate([
            'jenis_sampah' => 'required|string',
            'harga_pengepul' => 'required|integer',
            'harga_ditabung' => 'nullable|integer',
            'deskripsi' => 'nullable|string',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $fotoPath = null;
        if ($request->hasFile('foto')) {
            $foto = $request->file('foto');
            $namaFoto = time() . '_' . $foto->getClientOriginalName();
            $foto->move(public_path('uploads/sampah'), $namaFoto);
            $fotoPath = 'uploads/sampah/' . $namaFoto;
        }

        Sampah::create([
            'jenis_sampah' => $request->jenis_sampah,
            'harga_pengepul' => $request->harga_pengepul,
            'harga_ditabung' => $request->harga_ditabung ?? 0,
            'deskripsi' => $request->deskripsi,
            'foto' => $fotoPath,
        ]);

        return redirect()->back()->with('success', 'Data berhasil ditambahkan!');
    }

    public function editSampah($id_sampah)
    {
        $sampah = Sampah::findOrFail($id_sampah);
        return view('layout.sampah_edit', compact('sampah'));
    }

    public function updateSampah(Request $request, $id_sampah)
    {
        $request->validate([
            'jenis_sampah' => 'required|string|max:255',
            'harga_pengepul' => 'required|numeric|min:0',
            'deskripsi' => 'nullable|string',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $harga_ditabung = $request->harga_ditabung ?? ($request->harga_pengepul * 0.8);

        $update = [
            'jenis_sampah' => $request->jenis_sampah,
            'harga_pengepul' => $request->harga_pengepul,
            'harga_ditabung' => $harga_ditabung,
            'deskripsi' => $request->deskripsi,
        ];

        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/sampah'), $filename);
            $update['foto'] = 'uploads/sampah/' . $filename;
        }

        Sampah::where('id_sampah', $id_sampah)->update($update);

        return redirect()->route('sampah.index')->with('sukses', 'Data berhasil diperbarui.');
    }

    public function hapusSampah($id_sampah)
    {
        $sampah = Sampah::findOrFail($id_sampah);
        $sampah->delete();

        return redirect('/sampah')->with('sukses', 'Data berhasil dihapus.');
    }

    //==================================================================================================================================================

    public function apiSampah()
    {
        $data = Sampah::all();

        foreach ($data as $d) {
            $d->harga_pengepul_rp = 'Rp ' . number_format($d->harga_pengepul, 0, ',', '.');
            $d->harga_ditabung_rp = 'Rp ' . number_format($d->harga_ditabung ?? 0, 0, ',', '.');

            $d->foto_url = url('uploads/sampah/' . basename($d->foto));

        }

        return response()->json([
            'success' => true,
            'message' => 'Data sampah berhasil diambil',
            'data' => $data
        ]);
    }

    public function importSampah(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xls,xlsx',
        ]);

        $file = $request->file('file');
        $spreadsheet = IOFactory::load($file->getPathname());
        $sheet = $spreadsheet->getActiveSheet();
        $rows = $sheet->toArray();

        foreach (array_slice($rows, 1) as $row) {
            $jenisSampah = $row[0] ?? null;
            $hargaPengepul = is_numeric($row[1]) ? floatval($row[1]) : 0;
            $hargaDitabung = $hargaPengepul * 0.8;
            $deskripsi = $row[3] ?? null;
            $foto = isset($row[4]) ? 'uploads/sampah/' . $row[4] : null;

            if (!$jenisSampah) continue;

            $sampah = Sampah::where('jenis_sampah', $jenisSampah)->first();
            if ($sampah) {
                $sampah->update([
                    'harga_pengepul' => $hargaPengepul,
                    'harga_ditabung' => $hargaDitabung,
                    'deskripsi'      => $deskripsi,
                    'foto'           => $foto,
                ]);
            } else {
                Sampah::create([
                    'jenis_sampah'   => $jenisSampah,
                    'harga_pengepul' => $hargaPengepul,
                    'harga_ditabung' => $hargaDitabung,
                    'deskripsi'      => $deskripsi,
                    'foto'           => $foto,
                ]);
            }
        }

        return redirect()->back()->with('sukses', 'Data berhasil diimport!');
    }

}
