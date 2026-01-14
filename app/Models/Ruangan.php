<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ruangan extends Model
{
    protected $table = 'ruangan';
    protected $fillable = ['kode_ruangan', 'nama_ruangan', 'lokasi', 'keterangan'];

    public function barangs()
    {
        return $this->hasMany(Barang::class);
    }
}
