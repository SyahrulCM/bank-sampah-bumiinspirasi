<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengepul extends Model
{
    use HasFactory;
    protected $table = 'pengepuls';
    protected $primaryKey = 'id_pengepul';
    protected $fillable = ['nama_pengepul', 'kontak', 'alamat'];

    public function penjualan()
    {
        return $this->hasMany(Penjualan::class, 'id_pengepul');
    }
}
