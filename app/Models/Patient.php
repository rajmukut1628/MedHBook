<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'privacy_key',
        'profile_photo',
        'name',
        'email',
        'phone',
        'age',
        'gender',
        'blood_group',
        'address',
        'date_of_birth',
        'has_allergy',
        'has_diabetes',
        'has_blood_pressure',
        'emergency_contact',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'has_allergy' => 'boolean',
        'has_diabetes' => 'boolean',
        'has_blood_pressure' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    public function prescriptions()
    {
        return $this->hasMany(Prescription::class);
    }

    public function medicalDocuments()
    {
        return $this->hasMany(MedicalDocument::class);
    }

    public function privacyKey()
    {
        if (!$this->privacy_key) {
            $this->privacy_key = strtoupper(
                'PH-' .
                $this->id .
                '-' .
                substr(md5($this->id . $this->created_at), 0, 6)
            );

            $this->save();
        }

        return $this->privacy_key;
    }
}