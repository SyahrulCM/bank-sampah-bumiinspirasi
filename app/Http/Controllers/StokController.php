<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Stok;
use App\Models\Sampah;
use Illuminate\Support\Facades\DB;

class StokController extends Controller
{
    public function index()
    {
        $stokData = Sampah::with('stok')->get();
        return view('layout.stok', compact('stokData'));
    }
}
