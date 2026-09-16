<?php

namespace App\Filament\Resources\Invoices\Pages;

use App\Filament\Resources\Invoices\InvoiceResource;
use App\Models\Invoice;
use App\Models\SmsLog;
use App\Services\SmsService;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;
use Throwable;

class ViewInvoice extends ViewRecord
{
    protected static string $resource = InvoiceResource::class;

    protected function getHeaderActions(): array
    {
        return [

            Action::make('downloadPdf')
                ->label('Download PDF')
                ->icon('heroicon-o-arrow-down-tray')
                ->action(function () {

                    $invoice = $this->record->load([
                        'client',
                        'clientService',
                        'items',
                    ]);

                    return response()->streamDownload(
                        function () use ($invoice) {
                            echo Pdf::loadView('invoices.pdf', [
                                'invoice' => $invoice,
                            ])->output();
                        },
                        $invoice->invoice_number . '.pdf'
                    );
                }),

            Action::make('sendSms')
                ->label('Send SMS')
                ->icon('heroicon-o-chat-bubble-left-right')
                ->color('success')
                ->form([
                    TextInput::make('phone')
                        ->label('Recipient Phone')
                        ->required()
                        ->disabled(),

                    Textarea::make('message')
                        ->label('Message')
                        ->required()
                        ->maxLength(1000)
                        ->rows(5),
                ])
                ->fillForm(function (Invoice $record): array {
                    $record->loadMissing('client');

                    return [
                        'phone' => $record->client?->phone,
                        'message' => "Dear {$record->client?->name}, your invoice "
                            . "{$record->invoice_number} has an outstanding "
                            . "amount of BDT {$record->due_amount}.",
                    ];
                })
                ->action(function (
                    array $data,
                    Invoice $record,
                    SmsService $smsService
                ): void {
                    $record->loadMissing('client');
                    $phone = $record->client?->phone;
                    $message = $data['message'];

                    try {
                        $response = $smsService->send($phone, $message);
                        $providerError = $response['error_message'] ?? null;
                        $sent = blank($providerError);

                        SmsLog::create([
                            'invoice_id' => $record->id,
                            'user_id' => $record->user_id,
                            'phone' => $phone,
                            'trigger_type' => 'manual',
                            'message' => $message,
                            'status' => $sent ? 'sent' : 'failed',
                            'sent_at' => $sent ? now() : null,
                            'provider_response' => json_encode($response),
                            'error_message' => $providerError,
                        ]);

                        if (! $sent) {
                            Notification::make()
                                ->title('SMS sending failed')
                                ->body($providerError)
                                ->danger()
                                ->send();

                            return;
                        }

                        Notification::make()
                            ->title('SMS sent successfully')
                            ->success()
                            ->send();
                    } catch (Throwable $e) {
                        SmsLog::create([
                            'invoice_id' => $record->id,
                            'user_id' => $record->user_id,
                            'phone' => $phone,
                            'trigger_type' => 'manual',
                            'message' => $message,
                            'status' => 'failed',
                            'error_message' => $e->getMessage(),
                        ]);

                        Notification::make()
                            ->title('SMS sending failed')
                            ->body($e->getMessage())
                            ->danger()
                            ->send();
                    }
                }),

            EditAction::make(),
        ];
    }
}
