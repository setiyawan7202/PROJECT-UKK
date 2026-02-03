<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Kategori extends Model
{
    use SoftDeletes;

    protected $table = 'kategori';
    protected $fillable = ['kode_kategori', 'nama_kategori', 'deskripsi'];

    public static function generateKode(): string
    {
        $prefix = 'KAT';
        $lastKategori = self::withTrashed()
            ->where('kode_kategori', 'like', $prefix . '-%')
            ->orderBy('kode_kategori', 'desc')
            ->first();

        if ($lastKategori) {
            $lastNumber = (int) substr($lastKategori->kode_kategori, strlen($prefix) + 1);
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
