<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CatatanPengaduan extends Model
{
    use HasFactory;

    protected $table = 'catatan_pengaduan';

    protected $fillable = [
        'pengaduan_id',
        'user_id',
        'catatan',
    ];

    /**
     * Get the complaint that owns the note.
     */
    public function pengaduan()
    {
        return $this->belongsTo(Pengaduan::class, 'pengaduan_id');
    }

    /**
     * Get the user who created the note.
     */
    public function user()
    {
        return $this->belongsTo(Auth::class, 'user_id');
    }
}
