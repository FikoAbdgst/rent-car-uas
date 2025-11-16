<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaksi extends Model
{
    use HasFactory;

    protected $table = 'transaksis';
    protected $primaryKey = 'id';
    protected $fillable = [
        'idmobil',
        'idpenyewa',
        'iduser',
        'tanggalmulai',
        'tanggalkembali',
        'tanggaldikembalikan',
        'totalharga',
        'status',
        'catatan',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'iduser');
    }

    public function mobil(): BelongsTo
    {
        return $this->belongsTo(Mobil::class, 'idmobil');
    }

    public function penyewa(): BelongsTo
    {
        return $this->belongsTo(Penyewa::class, 'idpenyewa');
    }

    public function scopeWait($query)
    {
        return $query->where('status', 'WAIT');
    }

    public function scopeProses($query)
    {
        return $query->where('status', 'PROSES');
    }

    public function scopeSelesai($query)
    {
        return $query->where('status', 'SELESAI');
    }
}
