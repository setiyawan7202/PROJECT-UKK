<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    protected $table = 'kategori';
    protected $fillable = ['kode_kategori', 'nama_kategori', 'deskripsi'];

    /**
     * Generate kode kategori otomatis dengan format KAT00001
     */
    public static function generateKode(): string
    {
        $prefix = 'KAT';
        $lastKategori = self::where('kode_kategori', 'like', $prefix . '%')
            ->orderBy('kode_kategori', 'desc')
            ->first();

        if ($lastKategori) {
            $lastNumber = (int) substr($lastKategori->kode_kategori, strlen($prefix));
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
