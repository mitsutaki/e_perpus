<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;


class ActivityLog extends Model
{
    protected $fillable = ['user_id', 'action', 'description', 'subject_type', 'subject_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Helper untuk catat aktivitas
    public static function record($action, $description, $subject = null)
    {
        return self::create([
            'user_id' => Auth::id(),
            'action' => $action,
            'description' => $description,
            'subject_type' => $subject ? get_class($subject) : null,
            'subject_id' => $subject ? $subject->id : null,
        ]);
    }
}