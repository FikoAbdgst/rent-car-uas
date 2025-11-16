<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Expense extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'expenses';
    protected $primaryKey = 'id';

    protected $fillable = [
        'user_id',
        'mobil_id',
        'kategori',
        'deskripsi',
        'jumlah',
        'tanggal',
        'metode_pembayaran',
        'nomor_referensi',
        'status',
        'bukti_pembayaran',
        'catatan'
    ];

    protected $casts = [
        'tanggal' => 'date',
        'jumlah' => 'decimal:2'
    ];

    /**
     * Relasi dengan User
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi dengan Mobil (optional, untuk expense yang terkait mobil tertentu)
     */
    public function mobil(): BelongsTo
    {
        return $this->belongsTo(Mobil::class);
    }

    /**
     * Scope untuk filter berdasarkan kategori
     */
    public function scopeByKategori($query, $kategori)
    {
        return $query->where('kategori', $kategori);
    }

    /**
     * Scope untuk filter berdasarkan status
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope untuk filter berdasarkan bulan
     */
    public function scopeByMonth($query, $month, $year = null)
    {
        $year = $year ?: date('Y');
        return $query->whereYear('tanggal', $year)
            ->whereMonth('tanggal', $month);
    }

    /**
     * Scope untuk filter berdasarkan rentang tanggal
     */
    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('tanggal', [$startDate, $endDate]);
    }

    /**
     * Scope untuk expense yang menunggu approval
     */
    public function scopeWaiting($query)
    {
        return $query->where('status', 'wait');
    }

    /**
     * Scope untuk expense yang sedang diproses
     */
    public function scopeInProgress($query)
    {
        return $query->where('status', 'proses');
    }

    /**
     * Scope untuk expense yang sudah selesai
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'selesai');
    }

    /**
     * Accessor untuk format jumlah
     */
    public function getFormattedJumlahAttribute()
    {
        return 'Rp ' . number_format($this->jumlah, 0, ',', '.');
    }

    /**
     * Accessor untuk status badge - updated untuk status baru
     */
    public function getStatusBadgeAttribute()
    {
        switch ($this->status) {
            case 'wait':
                return '<span class="badge bg-warning text-dark">Menunggu</span>';
            case 'proses':
                return '<span class="badge bg-info">Dalam Proses</span>';
            case 'selesai':
                return '<span class="badge bg-success">Selesai</span>';
            default:
                return '<span class="badge bg-secondary">Unknown</span>';
        }
    }

    /**
     * Accessor untuk status text
     */
    public function getStatusTextAttribute()
    {
        switch ($this->status) {
            case 'wait':
                return 'Menunggu';
            case 'proses':
                return 'Dalam Proses';
            case 'selesai':
                return 'Selesai';
            default:
                return 'Unknown';
        }
    }

    /**
     * Accessor untuk warna status
     */
    public function getStatusColorAttribute()
    {
        switch ($this->status) {
            case 'wait':
                return 'warning';
            case 'proses':
                return 'info';
            case 'selesai':
                return 'success';
            default:
                return 'secondary';
        }
    }

    /**
     * Accessor untuk format tanggal Indonesia
     */
    public function getFormattedTanggalAttribute()
    {
        return $this->tanggal->format('d/m/Y');
    }

    /**
     * Mutator untuk format jumlah dari input
     */
    public function setJumlahAttribute($value)
    {
        // Remove any formatting (dots, commas) before saving
        $this->attributes['jumlah'] = str_replace(['.', ','], ['', '.'], $value);
    }

    /**
     * Method untuk mengecek apakah expense bisa diproses
     */
    public function canBeProcessed()
    {
        return $this->status === 'wait';
    }

    /**
     * Method untuk mengecek apakah expense bisa diselesaikan
     */
    public function canBeCompleted()
    {
        return $this->status === 'proses';
    }

    /**
     * Method untuk mengecek apakah expense terkait maintenance
     */
    public function isMaintenanceRelated()
    {
        $maintenanceCategories = ['maintenance', 'repair', 'spare_parts', 'tire'];
        return in_array($this->kategori, $maintenanceCategories);
    }
}
