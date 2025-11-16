<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penyewa extends Model
{
    use HasFactory;

    protected $table = 'penyewas';
    protected $primaryKey = 'id';
    protected $fillable = ['nama', 'nik', 'telp', 'alamat'];

    public function transaksis()
    {
        return $this->hasMany(Transaksi::class, 'idpenyewa');
    }
}
