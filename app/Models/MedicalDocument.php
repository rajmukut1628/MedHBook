<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MedicalDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'document_type',
        'doctor_name',
        'hospital_name',
        'title',
        'encrypted_name',
        'original_name',
        'storage_disk',
        'storage_path',
        'file_type',
        'file_size',
        'encryption_mode',
        'notes',
        'document_date',
        'salt',
        'iv',
        'tag',
        'auth_tag',
        'key_hint',
        'privacy_key_hint',
    ];

    protected $casts = [
        'document_date' => 'date',
        'file_size' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getSecureFilePathAttribute(): ?string
    {
        return $this->storage_path;
    }

    public function getPrettySizeAttribute(): string
    {
        $bytes = (int) ($this->file_size ?? 0);

        if ($bytes >= 1048576) {
            return round($bytes / 1048576, 2) . ' MB';
        }

        if ($bytes >= 1024) {
            return round($bytes / 1024, 2) . ' KB';
        }

        return $bytes . ' B';
    }
}