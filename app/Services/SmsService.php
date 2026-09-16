<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use RuntimeException;

class SmsService
{
    public function send(string $phone, string $message): array
    {
        $apiKey = config('services.bulksms.api_key');

        if (! $apiKey) {
            throw new RuntimeException(
                'BulkSMS API credentials are not configured.'
            );
        }

        $response = Http::timeout(30)
            ->acceptJson()
            ->get(config('services.bulksms.base_url') . '/smsapi', [
                'api_key' => $apiKey,
                'type' => 'text',
                'number' => $phone,
                'senderid' => config('services.bulksms.sender_id'),
                'message' => $message,
            ]);

        if ($response->failed()) {
            throw new RuntimeException(
                'BulkSMS API Error: ' . $response->body()
            );
        }

        return $response->json() ?? [];
    }
}