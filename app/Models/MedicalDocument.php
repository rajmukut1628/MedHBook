<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MedicalDocument extends Model
{
    protected $fillable = [
        'user_id',
        'document_type',
        'title',
        'file_path',
        'file_type',
        'file_size',
        'notes',
        'document_date',
    ];

    protected $casts = [
        'document_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getFileUrlAttribute()
    {
        return asset('storage/' . $this->file_path);
    }
}