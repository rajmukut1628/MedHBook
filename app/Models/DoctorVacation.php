<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DoctorVacation extends Model
{
    protected $fillable = [
        'user_id',
        'start_date',
        'end_date',
        'reason',
        'is_active',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}