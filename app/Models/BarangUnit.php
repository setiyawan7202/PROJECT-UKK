<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BarangUnit extends Model
{
    use SoftDeletes;

    protected $table = 'barang_unit';
    protected $fillable = ['barang_id', 'kode_unit', 'kondisi', 'status'];

    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }

    public function peminjaman()
    {
        return $this->hasMany(Peminjaman::class);
    }

    public static function generatePrefix(string $namaBarang): string
    {
        $nama = strtoupper(trim($namaBarang));
        $words = preg_split('/\s+/', $nama);

        if (count($words) === 1) {
            return substr($nama, 0, 3);
        } else {
            $prefix = '';
            foreach ($words as $word) {
                if (strlen($prefix) < 3 && strlen($word) > 0) {
                    $prefix .= $word[0];
                }
            }
            if (strlen($prefix) < 3) {
                $prefix .= substr($words[0], 1, 3 - strlen($prefix));
            }
            return substr($prefix, 0, 3);
        }
    }

    public static function generateKodeUnits(string $namaBarang, int $jumlah): array
    {
        $prefix = self::generatePrefix($namaBarang);

        $lastUnit = self::withTrashed()
            ->where('kode_unit', 'like', $prefix . '-%')
            ->orderByRaw("CAST(REPLACE(REPLACE(kode_unit, '{$prefix}-', ''), '-', '') AS UNSIGNED) DESC")
            ->first();

        if ($lastUnit) {
            $parts = explode('-', $lastUnit->kode_unit);
            if (count($parts) === 4) {
                $lastNumber = (int) ($parts[1] . $parts[2] . $parts[3]);
            } else {
                $lastNumber = 0;
            }
        } else {
            $lastNumber = 0;
        }

        $kodes = [];
        for ($i = 1; $i <= $jumlah; $i++) {
            $newNumber = $lastNumber + $i;
            $formatted = str_pad($newNumber, 9, '0', STR_PAD_LEFT);
            $kode = $prefix . '-' . substr($formatted, 0, 3) . '-' . substr($formatted, 3, 3) . '-' . substr($formatted, 6, 3);
            $kodes[] = $kode;
        }

        return $kodes;
    }
}
