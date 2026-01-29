<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

class Auth extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $table = 'users';

    protected $fillable = [
        'username',
        'email',
        'data_nip_nisn',
        'password',
        'password',
        'role',
        'status',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    /**
     * Get the class (kelas) this user belongs to via Siswa
     */

    public function peminjaman()
    {
        return $this->hasMany(Peminjaman::class, 'user_id');
    }

    public function siswa()
    {
        return $this->hasOne(Siswa::class, 'user_id');
    }

    public function guru()
    {
        return $this->hasOne(Guru::class, 'user_id');
    }

    /**
     * Accessor for 'nama_lengkap' to handle name resolution dynamics.
     * Prioritizes: users.username -> siswa.username -> guru.username -> users.email
     */
    public function getNamaLengkapAttribute()
    {
        // 1. Check direct username in users table
        if (!empty($this->attributes['username'])) {
            return $this->attributes['username'];
        }

        // 2. Check Siswa relation
        if ($this->siswa && !empty($this->siswa->username)) {
            return $this->siswa->username;
        }

        // 3. Check Guru relation
        if ($this->guru && !empty($this->guru->username)) {
            return $this->guru->username;
        }

        // 4. Fallback
        return $this->email;
    }
}
