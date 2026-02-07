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
        'role',
        'status',
        'is_active',
        'permissions',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'permissions' => 'array',
        ];
    }

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

    public function getNamaLengkapAttribute()
    {
        if (!empty($this->attributes['username'])) {
            return $this->attributes['username'];
        }

        if ($this->siswa && !empty($this->siswa->username)) {
            return $this->siswa->username;
        }

        if ($this->guru && !empty($this->guru->username)) {
            return $this->guru->username;
        }

        return $this->email;
    }

    /**
     * Check if user has specific permission
     */
    public function hasPermission($permission)
    {
        if ($this->role === 'admin') {
            return true;
        }

        if ($this->role !== 'petugas') {
            return false;
        }

        return in_array($permission, $this->permissions ?? []);
    }
}
