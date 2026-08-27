<?php

namespace App\Filament\Resources\Invoices\Pages;

use App\Filament\Resources\Invoices\InvoiceResource;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

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


            EditAction::make(),
        ];
    }
}
