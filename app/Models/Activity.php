<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    protected $fillable = ['user_id', 'action', 'description', 'subject_type', 'subject_id'];

    public function user()
    {
        return $this->belongsTo(Auth::class, 'user_id');
    }
}
