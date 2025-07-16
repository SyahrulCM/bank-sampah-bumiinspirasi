<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mutasi extends Model
{
    use HasFactory;

    protected $table = 'mutasis'; // Sesuai dengan nama tabel

    protected $primaryKey = 'id_mutasi'; // Karena bukan 'id'

    public $incrementing = true; // Karena pakai increments()

    protected $keyType = 'int';

    protected $fillable = [
        'tanggal',
        'id_sampah',
        'aksi',
        'berat',
        'keterangan',
    ];

    // Relasi ke model Sampah
    public function sampah()
    {
        return $this->belongsTo(Sampah::class, 'id_sampah', 'id_sampah');
    }
}