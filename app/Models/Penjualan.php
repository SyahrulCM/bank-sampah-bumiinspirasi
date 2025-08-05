<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penjualan extends Model
{
    use HasFactory;
    protected $table = 'penjualans';
    protected $primaryKey = 'id_penjualan';
    protected $fillable = ['tanggal', 'total_harga', 'id_pengepul', 'hasil_negosiasi'];

    public function pengepul()
    {
        return $this->belongsTo(Pengepul::class, 'id_pengepul');
    }

    public function detailPenjualan()
    {
        return $this->hasMany(DetailPenjualan::class, 'id_penjualan');
    }
}
