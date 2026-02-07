<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;

/**
 * @property int $id
 * @property int|null $kategori_id
 * @property int|null $barang_id
 * @property int $interval_days
 * @property \Illuminate\Support\Carbon|null $next_maintenance_at
 * @property array $reminder_days
 * @property int $created_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * 
 * @property-read \App\Models\Kategori|null $kategori
 * @property-read \App\Models\Barang|null $barang
 * @property-read \App\Models\Auth|null $creator
 */
class MaintenanceSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'kategori_id',
        'barang_id',
        'interval_days',
        'next_maintenance_at',
        'reminder_days',
        'created_by',
    ];

    protected $casts = [
        'reminder_days' => 'array',
        'next_maintenance_at' => 'date',
    ];

    public function kategori()
    {
        return $this->belongsTo(Kategori::class);
    }

    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }

    public function creator()
    {
        return $this->belongsTo(Auth::class, 'created_by');
    }

    public function logs()
    {
        return $this->hasMany(MaintenanceLog::class, 'schedule_id');
    }

    /**
     * Get schedules that are due for reminder
     */
    public function scopeDueForReminder($query)
    {
        return $query->whereNotNull('next_maintenance_at')
            ->where('next_maintenance_at', '<=', now()->addDays(7));
    }

    /**
     * Get schedules due today
     */
    public function scopeDueToday($query)
    {
        return $query->whereDate('next_maintenance_at', today());
    }

    /**
     * Calculate next maintenance date after completion
     */
    public function calculateNextMaintenance()
    {
        $this->next_maintenance_at = now()->addDays($this->interval_days);
        $this->save();
    }

    /**
     * Check if maintenance is overdue
     */
    public function isOverdue()
    {
        return $this->next_maintenance_at && $this->next_maintenance_at->isPast();
    }

    /**
     * Get days until next maintenance
     */
    public function getDaysUntilAttribute()
    {
        if (!$this->next_maintenance_at) {
            return null;
        }
        return now()->diffInDays($this->next_maintenance_at, false);
    }
}
