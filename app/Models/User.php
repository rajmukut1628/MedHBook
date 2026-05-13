<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * Mass assignable fields
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'status',
        'google_id',
        'avatar',
        'email_verified_at',
        'suspended_until',
        'suspend_reason',
        'patient_id',
        'doctor_id',
    ];

    /**
     * Hidden fields
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Attribute casting
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'suspended_until'   => 'datetime',
        'deleted_at'        => 'datetime',
        'password'          => 'hashed',
    ];

    /*
    |--------------------------------------------------------------------------
    | ROLE CHECKS
    |--------------------------------------------------------------------------
    */

    public function isSuperAdmin()
    {
        return $this->role === 'super_admin';
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isDoctor()
    {
        return $this->role === 'doctor';
    }

    public function isPatient()
    {
        return $this->role === 'patient';
    }

    /*
    |--------------------------------------------------------------------------
    | STATUS CHECKS
    |--------------------------------------------------------------------------
    */

    public function isSuspended()
    {
        return $this->suspended_until && now()->lt($this->suspended_until);
    }

    public function isActive()
    {
        return $this->status === 'active';
    }

    public function isBlocked()
    {
        return $this->status === 'blocked';
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONSHIPS
    |--------------------------------------------------------------------------
    */

    public function doctorProfile()
    {
        return $this->hasOne(DoctorProfile::class);
    }

    public function doctorSchedules()
    {
        return $this->hasMany(DoctorSchedule::class);
    }

    public function doctorVacations()
    {
        return $this->hasMany(DoctorVacation::class);
    }

    /*
    |--------------------------------------------------------------------------
    | ACCESSORS (OPTIONAL BUT USEFUL)
    |--------------------------------------------------------------------------
    */

    public function getIsVerifiedAttribute()
    {
        return !is_null($this->email_verified_at);
    }
}