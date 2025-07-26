<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Penarikan;
use App\Models\Registrasi;
use App\Models\Transaksi;
use Carbon\Carbon;

class PenarikanController extends Controller
{
    public function index()
    {
        $penarikans = Penarikan::with('registrasi')->latest()->get();
        $registrasis = Registrasi::all();

        return view('layout.penarikan', compact('penarikans', 'registrasis'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_registrasi' => 'required|exists:registrasis,id_registrasi',
            'jumlah' => 'required|numeric|min:1000',
        ]);

        $registrasiId = $request->id_registrasi;

        // Hitung jumlah setoran nasabah dari detail_transaksi
        $jumlahSetoran = DB::table('detail_transaksis')
            ->join('transaksis', 'detail_transaksis.id_transaksi', '=', 'transaksis.id_transaksi')
            ->where('transaksis.id_registrasi', $registrasiId)
            ->sum('detail_transaksis.jumlah_setoran');

        if ($jumlahSetoran < 2) {
            return redirect()->back()->with('error', 'Penarikan hanya bisa dilakukan setelah melakukan setoran lebih dari satu kali.');
        }

        // Ambil data registrasi untuk cek saldo
        $registrasi = Registrasi::findOrFail($registrasiId);

        if ($registrasi->saldo < $request->jumlah) {
            return redirect()->back()->with('error', 'Saldo tidak mencukupi untuk melakukan penarikan.');
        }

        // Simpan penarikan (status default 'pending')
        Penarikan::create([
            'id_registrasi' => $registrasiId,
            'jumlah' => $request->jumlah,
            'tanggal' => Carbon::now()->toDateString(),
            'keterangan' => $request->keterangan ?? 'Penarikan saldo oleh nasabah',
            'status' => 'pending',
        ]);

        return redirect()->route('penarikan.index')->with('sukses', 'Penarikan berhasil disimpan dan menunggu validasi.');
    }

    public function validasi(Request $request, $id_penarikan)
    {
        $request->validate([
            'status' => 'required|in:disetujui,ditolak',
            'alasan_ditolak' => 'required_if:status,ditolak'
        ]);

        $penarikan = Penarikan::with('registrasi')->findOrFail($id_penarikan);
        $registrasi = $penarikan->registrasi;

        if ($request->status === 'disetujui') {
            if ($registrasi->saldo < $penarikan->jumlah) {
                return redirect()->back()->with('error', 'Saldo tidak mencukupi untuk menyetujui penarikan.');
            }

            // Kurangi saldo dari tabel registrasi
            $registrasi->saldo -= $penarikan->jumlah;
            $registrasi->save();

            $penarikan->status = 'disetujui';
            $penarikan->alasan_ditolak = null;
        } else {
            $penarikan->status = 'ditolak';
            $penarikan->alasan_ditolak = $request->alasan_ditolak;
        }

        $penarikan->save();

        return redirect()->route('penarikan.index')->with('sukses', 'Status penarikan berhasil divalidasi.');
    }
}
