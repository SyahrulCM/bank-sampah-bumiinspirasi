<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Registrasi;
use PhpOffice\PhpSpreadsheet\IOFactory;
use App\Imports\RegistrasiImport;

class RegistrasiController extends Controller
{
    public function getRegistrasi()
    {
        $data = Registrasi::all();
        return view('layout.registrasi', compact('data'));
    }

    public function inputRegistrasi(Request $request)
    {
        $validated = $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'alamat' => 'required|string',
            'nomer_telepon' => 'required|string',
            'nomer_induk_nasabah' => 'nullable|string|unique:registrasis,nomer_induk_nasabah',
            'tanggal' => 'required|date',
            'password' => 'nullable|string|min:4',
            'foto' => 'nullable|image|max:2048',
        ]);

        $fotoPath = null;
        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('foto_nasabah'), $filename);
            $fotoPath = 'foto_nasabah/' . $filename;
        }

        $registrasi = new Registrasi();
        $registrasi->nama_lengkap = $request->nama_lengkap;
        $registrasi->alamat = $request->alamat;
        $registrasi->nomer_telepon = $request->nomer_telepon;
        $registrasi->nomer_induk_nasabah = $request->nomer_induk_nasabah;
        $registrasi->tanggal = $request->tanggal;
        $registrasi->foto = $fotoPath;

        if ($request->filled('password')) {
            $registrasi->password = Hash::make($request->password);
        }

        $registrasi->save();

        return redirect()->back()->with('sukses', 'Data berhasil ditambahkan!');
    }

    public function hapusRegistrasi($id)
    {
        $data = Registrasi::findOrFail($id);

        if ($data->foto && file_exists(public_path($data->foto))) {
            unlink(public_path($data->foto));
        }

        $data->delete();
        return redirect()->back()->with('sukses', 'Data berhasil dihapus!');
    }

    public function editRegistrasi($id)
    {
        $data = Registrasi::findOrFail($id);
        return view('layout.registrasi_edit', compact('data'));
    }

    public function updateRegistrasi(Request $request, $id)
    {
        $registrasi = Registrasi::findOrFail($id);

        $validated = $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'alamat' => 'required|string',
            'nomer_telepon' => 'required|string',
            'nomer_induk_nasabah' => 'required|string|unique:registrasis,nomer_induk_nasabah,' . $id . ',id_registrasi',
            'tanggal' => 'required|date',
            'password' => 'nullable|string|min:4',
            'foto' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('foto')) {
            if ($registrasi->foto && file_exists(public_path($registrasi->foto))) {
                unlink(public_path($registrasi->foto));
            }

            $file = $request->file('foto');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('foto_nasabah'), $filename);
            $registrasi->foto = 'foto_nasabah/' . $filename;
        }

        $registrasi->nama_lengkap = $request->nama_lengkap;
        $registrasi->alamat = $request->alamat;
        $registrasi->nomer_telepon = $request->nomer_telepon;
        $registrasi->nomer_induk_nasabah = $request->nomer_induk_nasabah;
        $registrasi->tanggal = $request->tanggal;

        if ($request->filled('password')) {
            $registrasi->password = Hash::make($request->password);
        }

        $registrasi->save();

        return redirect()->route('registrasi.index')->with('sukses', 'Data berhasil diupdate!');
    }

    public function importRegistrasi(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xls,xlsx',
        ]);

        $file = $request->file('file');
        $spreadsheet = IOFactory::load($file->getPathname());
        $sheet = $spreadsheet->getActiveSheet();
        $rows = $sheet->toArray();

        foreach (array_slice($rows, 1) as $row) {
            // Ambil kolom tanggal
            $tanggalRaw = $row[5];

            // Deteksi apakah angka (Excel timestamp) atau string biasa
            if (is_numeric($tanggalRaw)) {
                $tanggal = Date::excelToDateTimeObject($tanggalRaw)->format('Y-m-d');
            } else {
                try {
                    $tanggal = date('Y-m-d', strtotime($tanggalRaw));
                } catch (\Exception $e) {
                    $tanggal = now()->format('Y-m-d'); // fallback ke hari ini
                }
            }

            Registrasi::create([
                'nama_lengkap'         => $row[0],
                'alamat'               => $row[1],
                'nomer_telepon'        => $row[2],
                'nomer_induk_nasabah'  => $row[3],
                'password'             => bcrypt($row[4]),
                'tanggal'              => $tanggal,
                'saldo'                => $row[6] ?? 0,
                'foto'                 => $row[7] ?? null,
            ]);
        }

        return redirect()->back()->with('sukses', 'Data berhasil diimport!');
    }

    // Tambahan: Fitur Validasi dari Modal
    public function simpanValidasi(Request $request, $id)
    {
        $request->validate([
            'nomer_induk_nasabah' => 'required|unique:registrasis,nomer_induk_nasabah',
        ]);

        $data = Registrasi::findOrFail($id);
        $data->nomer_induk_nasabah = $request->nomer_induk_nasabah;
        $data->save();

        return redirect()->route('registrasi.index')->with('sukses', 'Validasi berhasil dilakukan!');
    }
}
