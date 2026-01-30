<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pengaduan extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'pengaduan';

    protected $fillable = [
        'kode',
        'user_id',
        'judul',
        'deskripsi',
        'lokasi', // Keep for backward compatibility
        'jenis_sarpras', // Keep for backward compatibility
        'ruangan_id',
        'barang_id',
        'barang_unit_id',
        'kondisi',
        'status',
        'foto',
    ];

    /**
     * Generate kode pengaduan otomatis dengan format PGD00001
     */
    public static function generateKode(): string
    {
        $prefix = 'PGD';
        $lastRecord = self::withTrashed()
            ->where('kode', 'like', $prefix . '-%')
            ->orderBy('kode', 'desc')
            ->first();

        if ($lastRecord) {
            $lastNumber = (int) substr($lastRecord->kode, strlen($prefix) + 1);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return $prefix . '-' . str_pad($newNumber, 5, '0', STR_PAD_LEFT);
    }

    /**
     * Get the user that owns the complaint.
     */
    public function user()
    {
        return $this->belongsTo(Auth::class, 'user_id');
    }

    /**
     * Get the notes for the complaint.
     */
    public function catatan()
    {
        return $this->hasMany(CatatanPengaduan::class, 'pengaduan_id');
    }

    /**
     * Get the ruangan (room) for this complaint.
     */
    public function ruangan()
    {
        return $this->belongsTo(Ruangan::class, 'ruangan_id');
    }

    /**
     * Get the barang (item) for this complaint.
     */
    public function barang()
    {
        return $this->belongsTo(Barang::class, 'barang_id');
    }

    public function barangUnit()
    {
        return $this->belongsTo(BarangUnit::class, 'barang_unit_id');
    }
}
