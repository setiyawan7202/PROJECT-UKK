<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ruangan extends Model
{
    protected $table = 'ruangan';
    protected $fillable = ['kode_ruangan', 'nama_ruangan', 'lokasi', 'keterangan'];

    /**
     * Generate kode ruangan otomatis dengan format RNG00001
     */
    public static function generateKode(): string
    {
        $prefix = 'RNG';
        $lastRuangan = self::where('kode_ruangan', 'like', $prefix . '%')
            ->orderBy('kode_ruangan', 'desc')
            ->first();

        if ($lastRuangan) {
            $lastNumber = (int) substr($lastRuangan->kode_ruangan, strlen($prefix));
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return $prefix . str_pad($newNumber, 5, '0', STR_PAD_LEFT);
    }

    public function barangs()
    {
        return $this->hasMany(Barang::class);
    }
}
