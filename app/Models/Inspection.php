<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Inspection extends Model
{
    use HasFactory;

    protected $fillable = [
        'peminjaman_id',
        'barang_unit_id',
        'type',
        'checklist_data',
        'photo_path',
        'notes',
        'inspector_id',
        'has_damage',
        'damage_details',
        'inspected_at',
    ];

    protected $casts = [
        'checklist_data' => 'array',
        'has_damage' => 'boolean',
        'inspected_at' => 'datetime',
        'notes' => 'encrypted', // Compliance: Encrypt sensitive inspection notes
        'damage_details' => 'encrypted', // Compliance: Encrypt damage details
    ];

    public function peminjaman()
    {
        return $this->belongsTo(Peminjaman::class);
    }

    public function barangUnit()
    {
        return $this->belongsTo(BarangUnit::class);
    }

    public function inspector()
    {
        return $this->belongsTo(Auth::class, 'inspector_id');
    }

    /**
     * Get pre-borrow inspection for a peminjaman
     */
    public function scopePreBorrow($query, $peminjamanId)
    {
        return $query->where('peminjaman_id', $peminjamanId)
            ->where('type', 'pre_borrow');
    }

    /**
     * Get post-return inspection for a peminjaman
     */
    public function scopePostReturn($query, $peminjamanId)
    {
        return $query->where('peminjaman_id', $peminjamanId)
            ->where('type', 'post_return');
    }

    /**
     * Compare two inspections and return differences
     */
    public static function compareInspections($preBorrow, $postReturn)
    {
        $differences = [];

        if (!$preBorrow || !$postReturn) {
            return $differences;
        }

        $preData = $preBorrow->checklist_data ?? [];
        $postData = $postReturn->checklist_data ?? [];

        foreach ($preData as $key => $preValue) {
            $postValue = $postData[$key] ?? null;
            if ($preValue !== $postValue) {
                $differences[] = [
                    'item' => $key,
                    'before' => $preValue,
                    'after' => $postValue,
                ];
            }
        }

        return $differences;
    }
}
