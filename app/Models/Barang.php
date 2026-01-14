<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    protected $table = 'barang';
    protected $fillable = [
        'kode_barang',
        'nama_barang',
        'kategori_id',
        'ruangan_id',
        'jumlah_stok',
        'kondisi_saat_ini'
    ];

    public function kategori()
    {
        return $this->belongsTo(Kategori::class);
    }

    public function ruangan()
    {
        return $this->belongsTo(Ruangan::class);
    }
}
