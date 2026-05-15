<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AiDoctorSpecialtyService
{
    public function detectSpecialty(string $query): array
    {
        $query = trim($query);

        if ($query === '') {
            return $this->fallback('');
        }

        try {
            $apiKey = config('services.gemini.key');
            $model = config('services.gemini.model', 'gemini-2.5-flash');

            if (!$apiKey) {
                return $this->fallback($query);
            }

            $prompt = <<<PROMPT
You are an AI assistant for MedHBook, a doctor appointment website.

Important rules:
- Do NOT diagnose disease.
- Do NOT prescribe medicine.
- Only detect the most suitable doctor specialty.
- Patient may write English, Bangla, Banglish, symptoms, or specialty name.
- Return ONLY valid JSON.

Allowed specialties:
Cardiology, Neurology, Dermatology, Pediatrics, Medicine, Orthopedics, Gynecology, ENT, Ophthalmology, Dentistry, Psychiatry, Urology, Gastroenterology, Nephrology, Endocrinology, Pulmonology, General Surgery, Other

Patient search:
{$query}

Return JSON only:
{
  "specialty": "Cardiology",
  "confidence": 90,
  "reason": "Chest pain related search matches cardiology."
}
PROMPT;

            $response = Http::timeout(20)->post(
                "https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key={$apiKey}",
                [
                    'contents' => [
                        [
                            'parts' => [
                                ['text' => $prompt],
                            ],
                        ],
                    ],
                ]
            );

            if (!$response->successful()) {
                Log::warning('Gemini Find Doctor AI failed', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);

                return $this->fallback($query);
            }

            $text = $response->json('candidates.0.content.parts.0.text');
            $json = $this->extractJson($text);

            if (!$json || empty($json['specialty'])) {
                return $this->fallback($query);
            }

            return [
                'specialty' => trim($json['specialty']),
                'confidence' => (int) ($json['confidence'] ?? 80),
                'reason' => $json['reason'] ?? 'AI matched your search with this specialty.',
                'source' => 'ai',
            ];

        } catch (\Throwable $e) {
            Log::error('Find Doctor AI Error', [
                'message' => $e->getMessage(),
            ]);

            return $this->fallback($query);
        }
    }

    private function extractJson(?string $text): ?array
    {
        if (!$text) {
            return null;
        }

        $text = trim($text);
        $text = str_replace(['```json', '```'], '', $text);

        preg_match('/\{.*\}/s', $text, $matches);

        if (empty($matches[0])) {
            return null;
        }

        $decoded = json_decode($matches[0], true);

        return is_array($decoded) ? $decoded : null;
    }

    private function fallback(string $query): array
    {
        $q = mb_strtolower($query);

        $map = [
            'Cardiology' => ['heart', 'cardio', 'chest', 'chest pain', 'buk', 'বুক', 'বুক ব্যথা', 'হার্ট', 'হৃদ'],
            'Neurology' => ['brain', 'head', 'headache', 'migraine', 'neuro', 'matha', 'মাথা', 'মাথা ব্যথা', 'স্নায়ু'],
            'Dermatology' => ['skin', 'rash', 'allergy', 'chulkani', 'চুলকানি', 'চর্ম', 'চামড়া'],
            'Pediatrics' => ['child', 'baby', 'kids', 'children', 'শিশু', 'bachcha', 'বাচ্চা'],
            'Orthopedics' => ['bone', 'joint', 'fracture', 'back pain', 'knee', 'হাড়', 'হাড়', 'জয়েন্ট'],
            'ENT' => ['ear', 'nose', 'throat', 'ent', 'কান', 'নাক', 'গলা'],
            'Ophthalmology' => ['eye', 'eyes', 'vision', 'চোখ'],
            'Dentistry' => ['tooth', 'teeth', 'dental', 'দাঁত'],
            'Gynecology' => ['pregnancy', 'period', 'pregnant', 'women', 'female', 'গাইনি', 'গর্ভ'],
            'Nephrology' => ['kidney', 'কিডনি'],
            'Endocrinology' => ['diabetes', 'thyroid', 'ডায়াবেটিস'],
            'Gastroenterology' => ['stomach', 'gas', 'acidity', 'gastric', 'পেট'],
            'Pulmonology' => ['lung', 'breathing', 'asthma', 'শ্বাস'],
            'Medicine' => ['fever', 'cold', 'cough', 'জ্বর', 'কাশি', 'general'],
        ];

        foreach ($map as $specialty => $keywords) {
            foreach ($keywords as $keyword) {
                if (str_contains($q, mb_strtolower($keyword))) {
                    return [
                        'specialty' => $specialty,
                        'confidence' => 75,
                        'reason' => 'Keyword fallback matched your search.',
                        'source' => 'fallback',
                    ];
                }
            }
        }

        return [
            'specialty' => 'Medicine',
            'confidence' => 60,
            'reason' => 'General symptoms matched with Medicine.',
            'source' => 'fallback',
        ];
    }
}