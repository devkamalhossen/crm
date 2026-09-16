<?php

namespace App\Console\Commands;

use App\Models\Invoice;
use App\Models\SmsLog;
use App\Models\SmsReminderSetting;
use App\Services\SmsMessageFormatter;
use App\Services\SmsService;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Throwable;

class SendPaymentReminders extends Command
{
    protected $signature = 'sms:send-payment-reminders';

    protected $description = 'Send automated SMS payment reminders to clients';

    public function handle(
        SmsService $smsService,
        SmsMessageFormatter $formatter
    ): int {
        $today = Carbon::today();

        $settings = SmsReminderSetting::query()
            ->where('status', 'active')
            ->get();

        foreach ($settings as $setting) {

            $targetDate = match ($setting->trigger_type) {
                'before_due' => $today->copy()->addDays($setting->days),

                'after_due' => $today->copy()->subDays($setting->days),

                default => null,
            };

            if (! $targetDate) {
                continue;
            }

            Invoice::query()
                ->with('client')
                ->whereDate('due_date', $targetDate)
                ->whereNotIn('status', ['cancelled', 'draft'])
                ->chunkById(100, function ($invoices) use (
                    $setting,
                    $smsService,
                    $formatter
                ) {
                    foreach ($invoices as $invoice) {

                        $client = $invoice->client;

                        if (! $client || ! $client->phone) {
                            continue;
                        }

                        // Do not send SMS if invoice is already fully paid.
                        if ($invoice->due_amount <= 0) {
                            continue;
                        }

                        // Prevent duplicate reminder.
                        $alreadySent = SmsLog::query()
                            ->where('invoice_id', $invoice->id)
                            ->where(
                                'sms_reminder_setting_id',
                                $setting->id
                            )
                            ->where(
                                'trigger_type',
                                $setting->trigger_type
                            )
                            ->exists();

                        if ($alreadySent) {
                            continue;
                        }

                        $message = $formatter->format(
                            $setting->message,
                            $invoice
                        );

                        $log = SmsLog::create([
                            'invoice_id' => $invoice->id,
                            'user_id' => $client->id,
                            'sms_reminder_setting_id' => $setting->id,
                            'phone' => $client->phone,
                            'trigger_type' => $setting->trigger_type,
                            'message' => $message,
                            'status' => 'pending',
                        ]);

                        try {
                            $response = $smsService->send(
                                $client->phone,
                                $message
                            );

                            $providerError = $response['error_message'] ?? null;

                            if (filled($providerError)) {
                                $log->update([
                                    'status' => 'failed',
                                    'provider_response' => json_encode(
                                        $response
                                    ),
                                    'error_message' => $providerError,
                                ]);

                                $this->error(
                                    "SMS failed: {$client->name} - {$invoice->invoice_number}"
                                );

                                continue;
                            }

                            $log->update([
                                'status' => 'sent',
                                'sent_at' => now(),
                                'provider_response' => json_encode(
                                    $response
                                ),
                            ]);

                            $this->info(
                                "SMS sent: {$client->name} - {$invoice->invoice_number}"
                            );
                        } catch (Throwable $e) {

                            $log->update([
                                'status' => 'failed',
                                'error_message' => $e->getMessage(),
                                'provider_response' => null,
                            ]);

                            $this->error(
                                "SMS failed: {$client->name} - {$invoice->invoice_number}"
                            );
                        }
                    }
                });
        }

        return self::SUCCESS;
    }
}