<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailPenjualan extends Model
{
    use HasFactory;
    protected $table = 'detail_penjualans';
    protected $primaryKey = 'id_detail_penjualan';
    protected $fillable = ['id_penjualan', 'id_sampah', 'berat_kg', 'subtotal'];

    public function penjualan()
    {
        return $this->belongsTo(Penjualan::class, 'id_penjualan');
    }

    public function sampah()
    {
        return $this->belongsTo(Sampah::class, 'id_sampah');
    }
}
