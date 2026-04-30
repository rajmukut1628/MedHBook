<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    use HasFactory;

    protected $fillable = [
    'user_id',
    'name',
    'email',
    'phone',
    'specialist',
    'specialization',
    'room',
    'degree',
    'experience',
    'license_number',
    'chamber_address',
    'chamber_addresses',
    'working_days',
    'start_time',
    'end_time',
    'consultation_fee',
    'cv',
    'verification_status',
    'profile_photo',
'bio',
'qualification',
'gender',
'blood_group',
];

    protected $casts = [
        'experience' => 'integer',
        'working_days' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getDoctorSpecialistAttribute()
    {
        return $this->specialist ?: $this->specialization;
    }
}