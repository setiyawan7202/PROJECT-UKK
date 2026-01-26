<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BarangUnit extends Model
{
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

    /**
     * Generate kode prefix 3 huruf dari nama barang
     * Contoh: "Tablet" -> "TAB", "Kursi" -> "KRS", "Meja Belajar" -> "MJB"
     */
    public static function generatePrefix(string $namaBarang): string
    {
        $nama = strtoupper(trim($namaBarang));
        $words = preg_split('/\s+/', $nama);

        if (count($words) === 1) {
            // Single word: ambil 3 huruf pertama
            return substr($nama, 0, 3);
        } else {
            // Multiple words: ambil huruf pertama dari setiap kata (max 3)
            $prefix = '';
            foreach ($words as $word) {
                if (strlen($prefix) < 3 && strlen($word) > 0) {
                    $prefix .= $word[0];
                }
            }
            // Jika kurang dari 3, tambah dari kata pertama
            if (strlen($prefix) < 3) {
                $prefix .= substr($words[0], 1, 3 - strlen($prefix));
            }
            return substr($prefix, 0, 3);
        }
    }

    /**
     * Generate kode unit dengan format PREFIX-XXX-XXX-XXX
     * Contoh: TAB-000-000-001, TAB-000-000-002
     */
    public static function generateKodeUnits(string $namaBarang, int $jumlah): array
    {
        $prefix = self::generatePrefix($namaBarang);

        // Cari nomor terakhir dengan prefix ini
        $lastUnit = self::where('kode_unit', 'like', $prefix . '-%')
            ->orderByRaw("CAST(REPLACE(REPLACE(kode_unit, '{$prefix}-', ''), '-', '') AS UNSIGNED) DESC")
            ->first();

        if ($lastUnit) {
            // Parse nomor dari kode terakhir (PREFIX-XXX-XXX-XXX)
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
