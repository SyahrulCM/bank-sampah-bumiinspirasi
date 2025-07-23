<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Penarikan;
use App\Models\Transaksi;
use Carbon\Carbon;

class PenarikanApiController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'jumlah' => 'required|numeric|min:1000',
            'keterangan' => 'nullable|string',
        ]);

        $user = $request->user(); // user yang sudah login lewat sanctum
        $registrasiId = $user->id_registrasi;

        // Cek jumlah setoran
        $jumlahSetoran = DB::table('detail_transaksis')
            ->join('transaksis', 'detail_transaksis.id_transaksi', '=', 'transaksis.id_transaksi')
            ->where('transaksis.id_registrasi', $registrasiId)
            ->sum('detail_transaksis.jumlah_setoran');

        if ($jumlahSetoran < 2) {
            return response()->json(['message' => 'Setoran minimal 2 kali sebelum bisa tarik saldo.'], 403);
        }

        $transaksiTerakhir = Transaksi::where('id_registrasi', $registrasiId)->latest()->first();

        if (!$transaksiTerakhir || $transaksiTerakhir->saldo < $request->jumlah) {
            return response()->json(['message' => 'Saldo tidak mencukupi untuk penarikan.'], 403);
        }

        // Simpan penarikan dengan status pending
        $penarikan = Penarikan::create([
            'id_registrasi' => $registrasiId,
            'jumlah' => $request->jumlah,
            'tanggal' => Carbon::now()->toDateString(),
            'keterangan' => $request->keterangan ?? 'Penarikan saldo oleh nasabah',
            'status' => 'pending'
        ]);

        return response()->json([
            'message' => 'Permintaan penarikan berhasil dikirim.',
            'data' => $penarikan
        ], 201);
    }
}
