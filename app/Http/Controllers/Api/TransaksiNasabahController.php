<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaksi;

class TransaksiNasabahController extends Controller
{
    public function getTransaksiNasabah(Request $request)
    {
        $nasabah = $request->user();

        $transaksi = Transaksi::with(['detailTransaksi.sampah'])
            ->where('id_registrasi', $nasabah->id_registrasi)
            ->latest()
            ->get();

        return response()->json([
            'message' => 'Data transaksi nasabah',
            'data' => $transaksi
        ]);
    }
}
