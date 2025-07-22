<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Registrasi;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class RegistrasiController extends Controller
{
    public function registrasi()
    {
        return view('layout.registrasi');
    }

    public function getRegistrasi()
    {
        $data = Registrasi::all();
        return view('layout.registrasi', ['data' => $data]);
    }

    // Fungsi input registrasi manual
    public function inputRegistrasi(Request $request)
    {
        dd($request->all());
        $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'alamat' => 'required|string',
            'nomer_telepon' => 'required|string|max:20',
            'nomer_induk_nasabah' => 'required|string|max:50|unique:registrasis',
            'password' => 'required|string|min:6',
            'tanggal' => 'required|date',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $fotoPath = null;
        if ($request->hasFile('foto')) {
            $foto = $request->file('foto');
            $namaFoto = time() . '_' . $foto->getClientOriginalName();
            $foto->move(public_path('uploads/registrasi'), $namaFoto);
            $fotoPath = 'uploads/registrasi/' . $namaFoto;
        }

        Registrasi::create([
            'nama_lengkap' => $request->nama_lengkap,
            'alamat' => $request->alamat,
            'nomer_telepon' => $request->nomer_telepon,
            'nomer_induk_nasabah' => $request->nomer_induk_nasabah,
            'password' => bcrypt($request->password),
            'tanggal' => $request->tanggal,
            'foto' => $fotoPath,
        ]);

        return redirect()->back()->with('sukses', 'Data registrasi berhasil disimpan.');
    }

    // Fungsi tampilkan form edit
    public function editRegistrasi($id_registrasi)
    {
        $registrasi = Registrasi::findOrFail($id_registrasi);
        return view('layout.registrasi_edit', compact('registrasi'));
    }

    // Fungsi update registrasi
    public function updateRegistrasi(Request $request, $id_registrasi)
    {
        $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'alamat' => 'required|string',
            'nomer_telepon' => 'required|string|max:20',
            'nomer_induk_nasabah' => 'required|string|max:50|unique:registrasis,nomer_induk_nasabah,' . $id_registrasi . ',id_registrasi',
            'tanggal' => 'required|date',
            'password' => 'nullable|string|min:6',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = [
            'nama_lengkap' => $request->nama_lengkap,
            'alamat' => $request->alamat,
            'nomer_telepon' => $request->nomer_telepon,
            'nomer_induk_nasabah' => $request->nomer_induk_nasabah,
            'tanggal' => $request->tanggal,
        ];

        if ($request->filled('password')) {
            $data['password'] = bcrypt($request->password);
        }

        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/registrasi'), $filename);
            $update['foto'] = 'uploads/registrasi/' . $filename; // <-- gunakan full path relatif
        }

        Registrasi::where('id_registrasi', $id_registrasi)->update($data);

        return redirect()->route('registrasi.index')->with('sukses', 'Data berhasil diperbarui.');
    }

    // Fungsi hapus registrasi
    public function hapusRegistrasi($id_registrasi)
    {
        $registrasi = Registrasi::findOrFail($id_registrasi);
        $registrasi->delete();

        return redirect('/registrasi')->with('sukses', 'Data berhasil dihapus.');
    }

    // Fungsi import dari Excel
    public function importExcel(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|mimes:xlsx,xls'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->with('gagal', 'File tidak valid.');
        }

        $file = $request->file('file');
        $spreadsheet = IOFactory::load($file->getRealPath());
        $sheet = $spreadsheet->getActiveSheet();
        $rows = $sheet->toArray(null, true, true, true);

        foreach ($rows as $index => $row) {
            if ($index == 1) continue; // skip header

            // Pastikan password terisi, kalau tidak, default password = "123456"
            $password = !empty($row['E']) ? $row['E'] : '123456';

            Registrasi::create([
                'nama_lengkap' => $row['A'] ?? '',
                'alamat' => $row['B'] ?? '',
                'nomer_telepon' => $row['C'] ?? '',
                'nomer_induk_nasabah' => $row['D'] ?? '',
                'password' => bcrypt($password),
                'tanggal' => isset($row['F']) ? date('Y-m-d', strtotime($row['F'])) : now(),
            ]);
        }

        return redirect()->back()->with('sukses', 'Import data berhasil.');
    }
}
