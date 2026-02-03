<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ruangan extends Model
{
    use SoftDeletes;

    protected $table = 'ruangan';
    protected $fillable = ['kode_ruangan', 'nama_ruangan', 'lokasi', 'keterangan'];

    public static function generateKode(): string
    {
        $prefix = 'RNG';
        $lastRuangan = self::withTrashed()
            ->where('kode_ruangan', 'like', $prefix . '-%')
            ->orderBy('kode_ruangan', 'desc')
            ->first();

        if ($lastRuangan) {
            $lastNumber = (int) substr($lastRuangan->kode_ruangan, strlen($prefix) + 1);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return $prefix . '-' . str_pad($newNumber, 5, '0', STR_PAD_LEFT);
    }

    public function barangs()
    {
        return $this->hasMany(Barang::class);
    }
}
