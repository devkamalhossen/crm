<?php

namespace App\Console\Commands;

use App\Models\SmsLog;
use App\Services\SmsService;
use Illuminate\Console\Command;
use Throwable;

class TestSms extends Command
{
    protected $signature = 'sms:test
                            {phone : Recipient phone number}
                            {message : SMS message}';

    protected $description = 'Send a test SMS using BulkSMS';

    public function handle(SmsService $smsService): int
    {
        $phone = $this->argument('phone');
        $message = $this->argument('message');

        try {
            $response = $smsService->send($phone, $message);
            $providerError = $response['error_message'] ?? null;
            $sent = blank($providerError);

            SmsLog::create([
                'phone' => $phone,
                'trigger_type' => 'manual',
                'message' => $message,
                'status' => $sent ? 'sent' : 'failed',
                'sent_at' => $sent ? now() : null,
                'provider_response' => json_encode($response),
                'error_message' => $providerError,
            ]);

            if (! $sent) {
                $this->error('SMS sending failed.');
                $this->error($providerError);

                $this->line(json_encode($response, JSON_PRETTY_PRINT));

                return self::FAILURE;
            }

            $this->info('SMS submitted successfully.');

            $this->line(
                json_encode($response, JSON_PRETTY_PRINT)
            );

            return self::SUCCESS;
        } catch (Throwable $e) {
            SmsLog::create([
                'phone' => $phone,
                'trigger_type' => 'manual',
                'message' => $message,
                'status' => 'failed',
                'error_message' => $e->getMessage(),
            ]);

            $this->error('SMS sending failed.');
            $this->error($e->getMessage());

            return self::FAILURE;
        }
    }
}