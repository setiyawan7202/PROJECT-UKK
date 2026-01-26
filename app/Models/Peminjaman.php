<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Peminjaman extends Model
{
    protected $table = 'peminjaman';

    protected $fillable = [
        'user_id',
        'barang_id',
        'barang_unit_id',
        'jumlah',
        'tgl_pinjam',
        'tgl_kembali_rencana',
        'tgl_kembali_aktual',
        'tujuan_pinjam',
        'status',
        'keterangan_penolakan',
    ];

    protected $casts = [
        'tgl_pinjam' => 'date',
        'tgl_kembali_rencana' => 'date',
        'tgl_kembali_aktual' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(Auth::class, 'user_id');
    }

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'barang_id');
    }

    public function barangUnit()
    {
        return $this->belongsTo(BarangUnit::class, 'barang_unit_id');
    }
}
