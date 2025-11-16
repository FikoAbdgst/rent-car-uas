<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Mobil extends Model
{
    use HasFactory;

    protected $table = 'mobils';
    protected $primaryKey = 'id';
    protected $fillable = [
        'id',
        'user_id',
        'namamobil',
        'merek',
        'tipe',
        'tahun',
        'platnomor',
        'hargasewaperhari',
        'status',
        'gambar',
        'kapasitas',
        'transmisi'
    ];

    // Status constants
    const STATUS_TERSEDIA = 'tersedia';
    const STATUS_DISEWA = 'disewa';
    const STATUS_MAINTENANCE = 'maintenance';

    // Transmisi constants
    const TRANSMISI_MANUAL = 'manual';
    const TRANSMISI_AUTOMATIC = 'automatic';

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function transaksi()
    {
        return $this->hasMany(Transaksi::class, 'idmobil');
    }

    // Accessor untuk format harga
    public function getFormattedHargaAttribute()
    {
        return 'Rp ' . number_format($this->hargasewaperhari, 0, ',', '.');
    }

    // Accessor untuk status badge
    public function getStatusBadgeAttribute()
    {
        switch ($this->status) {
            case self::STATUS_TERSEDIA:
                return '<span class="badge bg-success">Tersedia</span>';
            case self::STATUS_DISEWA:
                return '<span class="badge bg-warning">Disewa</span>';
            case self::STATUS_MAINTENANCE:
                return '<span class="badge bg-danger">Maintenance</span>';
            default:
                return '<span class="badge bg-secondary">Unknown</span>';
        }
    }

    // Accessor untuk format kapasitas (opsional)
    public function getFormattedKapasitasAttribute()
    {
        return $this->kapasitas . ' penumpang';
    }

    // Accessor untuk format transmisi (opsional)
    public function getFormattedTransmisiAttribute()
    {
        switch ($this->transmisi) {
            case self::TRANSMISI_MANUAL:
                return 'Manual';
            case self::TRANSMISI_AUTOMATIC:
                return 'Automatic';
            default:
                return 'Unknown';
        }
    }
}
