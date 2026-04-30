<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MedicalDocument extends Model
{
    protected $fillable = [
        'user_id',
        'document_type',
        'title',
        'encrypted_name',
        'original_name',
        'storage_disk',
        'storage_path',
        'file_type',
        'file_size',
        'notes',
        'document_date',
        'encryption_mode',
    ];

    protected $casts = [
        'document_date' => 'date',
    ];

    protected $hidden = [
        'storage_path',
        'encrypted_name',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /*
    |--------------------------------------------------------------------------
    | IMPORTANT
    |--------------------------------------------------------------------------
    | No public URL for medical documents.
    | Files must be downloaded only through secure controller route.
    */
    public function getFileUrlAttribute()
    {
        return null;
    }

    public function getSecureDownloadUrlAttribute()
    {
        return route('medical-documents.download', $this);
    }
}