<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sampah extends Model
{
    use HasFactory;
    protected $primaryKey = 'id_sampah';
    protected $table = 'sampahs';
    protected $fillable = ['jenis_sampah','harga_pengepul','harga_ditabung','deskripsi','foto'];

    public function stok()
    {
        return $this->hasMany(Stok::class, 'id_sampah');
    }
    public function detailPenjualan()
    {
        return $this->hasMany(DetailPenjualan::class, 'id_sampah');
    }
}
