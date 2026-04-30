<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DoctorSchedule extends Model
{
    protected $fillable = [
        'user_id',
        'schedule_date',
        'day_of_week',
        'start_time',
        'end_time',
        'is_recurring',
        'is_emergency',
        'is_active',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}