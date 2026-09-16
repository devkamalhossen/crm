<?php

namespace App\Services;

use App\Models\Invoice;

class SmsMessageFormatter
{
    public function format(string $message, Invoice $invoice): string
    {
        $client = $invoice->client;

        return str_replace([
            '{client_name}',
            '{company_name}',
            '{invoice_number}',
            '{total_amount}',
            '{paid_amount}',
            '{due_amount}',
            '{due_date}',
        ], [
            $client?->name ?? '',
            $client?->company_name ?? '',
            $invoice->invoice_number ?? '',
            number_format((float) $invoice->total_amount, 2),
            number_format($invoice->paid_amount, 2),
            number_format($invoice->due_amount, 2),
            $invoice->due_date?->format('d M Y') ?? '',
        ], $message);
    }
}