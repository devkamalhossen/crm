<?php

namespace App\Filament\Client\Resources\Invoices;

use App\Filament\Resources\Invoices\Schemas\InvoiceInfolist;
use App\Models\Invoice;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use UnitEnum;

class InvoiceResource extends Resource
{
    protected static ?string $model = Invoice::class;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCreditCard;
    protected static UnitEnum|string|null $navigationGroup = 'Client Portal';
    protected static ?int $navigationSort = 3;
    protected static ?string $recordTitleAttribute = 'invoice_number';

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('user_id', auth()->id());
    }

    public static function form(Schema $schema): Schema
    {
        return $schema;
    }

    public static function infolist(Schema $schema): Schema
    {
        return InvoiceInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('invoice_number')->label('Invoice #')->searchable()->sortable()->copyable(),
                TextColumn::make('clientService.service_type')->label('Service')->formatStateUsing(fn (?string $state): string => $state ? ucwords(str_replace('_', ' ', $state)) : '—')->badge()->sortable(),
                TextColumn::make('invoice_date')->date('d M Y')->sortable(),
                TextColumn::make('due_date')->date('d M Y')->placeholder('—')->sortable(),
                TextColumn::make('total_amount')->label('Total')->money('BDT')->sortable(),
                TextColumn::make('paid_amount')->label('Paid')->money('BDT')->state(fn ($record): float => $record->paid_amount),
                TextColumn::make('due_amount')->label('Due')->money('BDT')->state(fn ($record): float => $record->due_amount),
                TextColumn::make('payment_status')->label('Payment Status')->badge()->state(fn ($record): string => $record->payment_status)->sortable(),
            ])
            ->recordActions([\Filament\Actions\ViewAction::make()])
            ->toolbarActions([]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListInvoices::route('/'),
            'view' => Pages\ViewInvoice::route('/{record}'),
        ];
    }
}
