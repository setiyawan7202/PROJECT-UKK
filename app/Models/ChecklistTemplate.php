<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ChecklistTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'kategori_id',
        'nama',
        'items',
        'created_by',
    ];

    protected $casts = [
        'items' => 'array',
    ];

    public function kategori()
    {
        return $this->belongsTo(Kategori::class);
    }

    public function creator()
    {
        return $this->belongsTo(Auth::class, 'created_by');
    }

    public function inspections()
    {
        return $this->hasMany(Inspection::class, 'checklist_template_id');
    }
}
