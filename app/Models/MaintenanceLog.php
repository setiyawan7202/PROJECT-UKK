<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MaintenanceLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'barang_unit_id',
        'schedule_id',
        'maintenance_date',
        'description',
        'technician_name',
        'performed_by',
        'notes',
    ];

    protected $casts = [
        'maintenance_date' => 'date',
    ];

    public function barangUnit()
    {
        return $this->belongsTo(BarangUnit::class);
    }

    public function schedule()
    {
        return $this->belongsTo(MaintenanceSchedule::class, 'schedule_id');
    }

    public function performer()
    {
        return $this->belongsTo(Auth::class, 'performed_by');
    }

    /**
     * Get logs for a specific unit
     */
    public function scopeForUnit($query, $unitId)
    {
        return $query->where('barang_unit_id', $unitId)
            ->orderBy('maintenance_date', 'desc');
    }

    /**
     * Get recent logs (last 30 days)
     */
    public function scopeRecent($query)
    {
        return $query->where('maintenance_date', '>=', now()->subDays(30))
            ->orderBy('maintenance_date', 'desc');
    }
}
