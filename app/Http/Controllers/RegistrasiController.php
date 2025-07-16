<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Registrasi;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\Validator;

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
        $data = $request->only([
            'nama_lengkap', 'usia', 'jenis_kelamin', 'alamat', 'nomer_telepon', 'email',
            'pekerjaan', 'nama_rekening', 'nomor_rekening', 'transportasi',
            'mengetahui', 'alasan', 'tanggal'
        ]);

        Registrasi::create($data);

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
        Registrasi::where('id_registrasi', $id_registrasi)->update($request->only([
            'nama_lengkap', 'usia', 'jenis_kelamin', 'alamat', 'nomer_telepon', 'email',
            'pekerjaan', 'nama_rekening', 'nomor_rekening', 'transportasi',
            'mengetahui', 'alasan', 'tanggal'
        ]));

        return redirect()->route('registrasi.index')->with('sukses', 'Data berhasil diperbarui.');
    }

    // Fungsi hapus registrasi
    public function hapusRegistrasi($id_registrasi)
    {
        $registrasi = Registrasi::findOrFail($id_registrasi);
        $registrasi->delete();

        return redirect('/registrasi')->with('sukses', 'Data berhasil dihapus.');
    }

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
            if ($index == 1) continue;

            Registrasi::create([
                'nama_lengkap'     => $row['A'] ?? '',
                'usia'             => $row['B'] ?? '',
                'jenis_kelamin'    => $row['C'] ?? '',
                'alamat'           => $row['D'] ?? '',
                'nomer_telepon'    => $row['E'] ?? '',
                'email'            => $row['F'] ?? '',
                'pekerjaan'        => $row['G'] ?? '',
                'nama_rekening'    => $row['H'] ?? '',
                'nomor_rekening'   => $row['I'] ?? '',
                'transportasi'     => $row['J'] ?? '',
                'mengetahui'       => $row['K'] ?? '',
                'alasan'           => $row['L'] ?? '',
                'tanggal'          => $row['M'] ?? now(),
            ]);
        }

        return redirect()->back()->with('sukses', 'Import data berhasil.');
    }
}
