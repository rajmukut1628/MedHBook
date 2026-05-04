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
        'chambers',
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
        'chamber_addresses' => 'array',
        'chambers' => 'array',
        'consultation_fee' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getDoctorSpecialistAttribute()
    {
        return $this->specialist ?: $this->specialization;
    }

    public function getDisplayChambersAttribute()
    {
        if (!empty($this->chambers) && is_array($this->chambers)) {
            return $this->chambers;
        }

        if (!empty($this->chamber_addresses) && is_array($this->chamber_addresses)) {
            return collect($this->chamber_addresses)->map(function ($address) {
                return [
                    'address' => $address,
                    'working_days' => $this->working_days ?? [],
                    'start_time' => $this->start_time,
                    'end_time' => $this->end_time,
                    'fee' => $this->consultation_fee ?? 0,
                ];
            })->toArray();
        }

        if (!empty($this->chamber_address)) {
            return [[
                'address' => $this->chamber_address,
                'working_days' => $this->working_days ?? [],
                'start_time' => $this->start_time,
                'end_time' => $this->end_time,
                'fee' => $this->consultation_fee ?? 0,
            ]];
        }

        return [];
    }
}