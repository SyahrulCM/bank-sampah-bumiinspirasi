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
        // Ambil semua data penarikan + data nasabah
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

    // Ambil transaksi terakhir
    $transaksiTerakhir = Transaksi::where('id_registrasi', $registrasiId)->latest()->first();

    if (!$transaksiTerakhir || $transaksiTerakhir->saldo < $request->jumlah) {
        return redirect()->back()->with('error', 'Saldo tidak mencukupi untuk melakukan penarikan.');
    }

    // Simpan penarikan
    Penarikan::create([
        'id_registrasi' => $registrasiId,
        'jumlah' => $request->jumlah,
        'tanggal' => Carbon::now()->toDateString(),
        'keterangan' => $request->keterangan ?? 'Penarikan saldo oleh nasabah',
    ]);

    // Kurangi saldo
    $transaksiTerakhir->saldo -= $request->jumlah;
    $transaksiTerakhir->save();

    return redirect()->route('penarikan.index')->with('sukses', 'Penarikan berhasil disimpan.');
}

}
