<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Guru extends Model
{
    use HasFactory;

    protected $table = 'guru';

    protected $fillable = [
        'user_id',
        'nama_lengkap',
        'email',
        'nip',
    ];

    public function user()
    {
        return $this->belongsTo(Auth::class, 'user_id');
    }
}
