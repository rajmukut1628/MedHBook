<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;

class PrivateFileController extends Controller
{
    private string $disk = 'local';

    public function show($folder, $filename)
    {
        $this->checkAuth();

        $path = $this->resolvePath($folder, $filename);

        if (!Storage::disk($this->disk)->exists($path)) {
            abort(404, 'File not found.');
        }

        $content = Storage::disk($this->disk)->get($path);

        if (str_ends_with($filename, '.mhb')) {
            $decryptedBase64 = Crypt::decryptString($content);
            $content = base64_decode($decryptedBase64);
        }

        return response($content, 200, [
            'Content-Type' => $this->mimeType($folder),
            'Cache-Control' => 'private, no-store, no-cache, must-revalidate',
            'Pragma' => 'no-cache',
            'Expires' => '0',
            'X-Content-Type-Options' => 'nosniff',
        ]);
    }

    public function download($folder, $filename)
    {
        $this->checkAuth();

        $path = $this->resolvePath($folder, $filename);

        if (!Storage::disk($this->disk)->exists($path)) {
            abort(404, 'File not found.');
        }

        $content = Storage::disk($this->disk)->get($path);

        if (str_ends_with($filename, '.mhb')) {
            $decryptedBase64 = Crypt::decryptString($content);
            $content = base64_decode($decryptedBase64);
        }

        return response()->streamDownload(function () use ($content) {
            echo $content;
        }, $this->downloadName($folder, $filename), [
            'Content-Type' => 'application/octet-stream',
            'Cache-Control' => 'private, no-store, no-cache, must-revalidate',
            'Pragma' => 'no-cache',
            'Expires' => '0',
            'X-Content-Type-Options' => 'nosniff',
        ]);
    }

    private function checkAuth(): void
    {
        if (!Auth::check()) {
            abort(403, 'Login required.');
        }
    }

    private function resolvePath($folder, $filename): string
    {
        $userId = Auth::id();

        return match ($folder) {
            'profile-pictures' => "private/profile-pictures/user-{$userId}/{$filename}",
            'patient-profiles' => "private/patient-profiles/user-{$userId}/{$filename}",
            'doctor-photos' => "private/doctor-photos/user-{$userId}/{$filename}",
            'doctor-cvs' => "private/doctor-cvs/{$filename}",
            'medical-documents' => "private/medical-documents/user-{$userId}/{$filename}",
            default => abort(404),
        };
    }

    private function mimeType(string $folder): string
    {
        return match ($folder) {
            'profile-pictures',
            'patient-profiles',
            'doctor-photos' => 'image/jpeg',

            'doctor-cvs',
            'medical-documents' => 'application/octet-stream',

            default => 'application/octet-stream',
        };
    }

    private function downloadName(string $folder, string $filename): string
    {
        return match ($folder) {
            'profile-pictures' => 'profile-photo.jpg',
            'patient-profiles' => 'patient-photo.jpg',
            'doctor-photos' => 'doctor-photo.jpg',
            'doctor-cvs' => str_replace('.mhb', '.pdf', $filename),
            default => str_replace('.mhb', '', $filename),
        };
    }
}