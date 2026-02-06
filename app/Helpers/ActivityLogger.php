<?php

namespace App\Helpers;

use App\Models\Activity;
use Illuminate\Support\Facades\Auth;

class ActivityLogger
{
    /**
     * Log an activity.
     *
     * @param string $action Short action name (e.g., 'Login', 'Create Peminjaman')
     * @param string $description Detailed description
     * @param mixed $subject Optional model instance related to the activity
     */
    public static function log($action, $description, $subject = null)
    {
        // Ensure user is logged in
        if (!Auth::check()) {
            return;
        }

        Activity::create([
            'user_id' => Auth::id(),
            'action' => $action,
            'description' => $description,
            'subject_type' => $subject ? get_class($subject) : null,
            'subject_id' => $subject ? $subject->id : null,
        ]);
    }
}
